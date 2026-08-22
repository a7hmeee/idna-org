<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Actions;

use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotMessageRepositoryInterface;
use App\Domains\Chatbot\Contracts\ClarificationResolverInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\Contracts\DirectServiceResolverInterface;
use App\Domains\Chatbot\Contracts\EntityResolverInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Contracts\MunicipalityDomainRouterInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\IntentPredictionData;
use App\Domains\Chatbot\DTOs\MunicipalityDomainRouteData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\DTOs\ServiceSearchResultCollection;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Handlers\GreetingHandler;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ChatbotActionRegistry;
use App\Domains\Chatbot\Services\ChatbotTrace;
use App\Domains\Chatbot\Services\ChatResponseHandlerRegistry;
use App\Domains\Chatbot\Services\GuidedServiceDiscoveryService;
use App\Domains\Chatbot\Services\ServicePropertyIntentDetector;
use App\Domains\ChatbotAnalytics\Events\UnknownQuestionDetectedEvent;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use App\Domains\CitizenWorkflows\Services\ConfirmationFlow;
use App\Domains\CitizenWorkflows\Services\WorkflowResponseBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

final readonly class ProcessRuleBasedChatMessageAction
{
    public function __construct(
        private ArabicTextNormalizer $normalizer,
        private HybridIntentPredictorInterface $predictor,
        private DirectServiceResolverInterface $resolver,
        private EntityResolverInterface $entityResolver,
        private ClarificationResolverInterface $clarificationResolver,
        private ConversationContextInterface $context,
        private ChatResponseHandlerRegistry $registry,
        private ChatbotConversationRepositoryInterface $conversationRepository,
        private ChatbotMessageRepositoryInterface $messageRepository,
        private SmartServiceSearchInterface $smartSearch,
        private MunicipalityDomainRouterInterface $domainRouter,
        private CitizenWorkflowEngine $workflowEngine,
        private WorkflowResponseBuilder $responseBuilder,
        private ConfirmationFlow $confirmationFlow,
        private GuidedServiceDiscoveryService $guidedDiscovery,
        private ChatbotActionRegistry $actionRegistry,
        private ServicePropertyIntentDetector $propertyIntentDetector,
        private WorkflowDraftRepositoryInterface $draftRepository,
    ) {}

    public function execute(IncomingChatMessageData $incoming): ChatResponseData
    {
        $startTime = microtime(true);

        return DB::transaction(function () use ($incoming, $startTime): ChatResponseData {
            $conversation = $this->conversationRepository->findActiveBySession($incoming->sessionId);
            if ($conversation === null) {
                $conversation = $this->conversationRepository->create([
                    'session_id' => $incoming->sessionId,
                    'user_id' => $incoming->userId,
                    'status' => 'active',
                ]);
            }

            $normalizedMessage = $this->normalizer->normalize($incoming->message);

            $state = $this->context->getState($incoming->sessionId);

            if ($state->expired) {
                $this->context->reset($incoming->sessionId);
                $state = $this->context->getState($incoming->sessionId);
            }

            ChatbotTrace::log([
                'event' => 'turn_start',
                'session_id' => $incoming->sessionId,
                'conversation_id' => $conversation->id,
                'raw_user_text' => $incoming->message,
                'normalized_user_text' => $normalizedMessage,
                'loaded_context' => [
                    'state' => $state->state->value,
                    'clarification_type' => $state->pendingField,
                    'clarification_options_count' => count($state->clarificationOptions),
                    'selected_entity' => $state->currentServiceId !== null
                        ? ['entity_type' => 'service', 'id' => $state->currentServiceId, 'name' => $state->currentServiceName]
                        : null,
                    'current_domain' => $state->currentDomain,
                    'current_category_id' => $state->currentCategoryId,
                    'current_area_id' => $state->currentAreaId,
                    'workflow_type' => $state->workflowType,
                ],
            ]);

            // Step 2: Resolve trusted structured action key
            $actionKeyResult = $this->resolveTrustedActionKey($incoming->message);
            if ($actionKeyResult !== null) {
                $prediction = new IntentPredictionData(
                    intent: $actionKeyResult['intent'],
                    confidence: 1.0,
                    source: 'rule',
                    accepted: true,
                );
                $route = new MunicipalityDomainRouteData(
                    domain: $actionKeyResult['domain'],
                    intent: $actionKeyResult['intent'],
                    handlerKey: $actionKeyResult['handler'],
                    confidence: 1.0,
                    source: 'action_key',
                );

                // Trusted guided-flow keys: the user clicked a service offered by
                // the electronic discovery flow. Resolve by ID only — never
                // through intent prediction or the generic pipeline.
                if (isset($actionKeyResult['service_id'])) {
                    $guided = $this->guidedDiscovery->handleServiceId((int) $actionKeyResult['service_id']);

                    $this->context->updateState($incoming->sessionId, array_merge(
                        $guided['context'],
                        ['should_stop_pipeline' => true],
                    ));

                    $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $prediction, $startTime, $route, null, null);

                    return $guided['response'];
                }

                if (isset($actionKeyResult['category_id'])) {
                    $guided = $this->guidedDiscovery->startCategoryServices((int) $actionKeyResult['category_id']);

                    $this->context->updateState($incoming->sessionId, array_merge(
                        $guided['context'],
                        ['should_stop_pipeline' => true],
                    ));

                    $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $prediction, $startTime, $route, null, null);

                    return $guided['response'];
                }

                // Trusted service-action key: resolve the target service and
                // answer with the dedicated handler without leaving a lock.
                if (isset($actionKeyResult['payload']['service_id'])) {
                    $serviceQuery = app(MunicipalityServiceQueryInterface::class);
                    $service = $serviceQuery->getPublishedOverview($actionKeyResult['payload']['service_id']);

                    if ($service === null && $actionKeyResult['payload']['service_id'] === $state->currentServiceId) {
                        $service = new ResolvedServiceData(
                            id: $actionKeyResult['payload']['service_id'],
                            name: $state->currentServiceName ?? '',
                        );
                    }

                    if ($service === null) {
                        $response = new ChatResponseData(
                            message: 'الخدمة المطلوبة غير متوفرة حالياً.',
                            type: 'text',
                        );
                    } else {
                        $response = $this->registry->resolve($actionKeyResult['intent'])->handle($incoming, $service);
                    }

                    $contextUpdate = [
                        'last_intent' => $actionKeyResult['intent']->value,
                        'previous_intent' => $state->lastIntent,
                        'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                        'current_domain' => 'electronic_services',
                        'needs_clarification' => false,
                        'pending_field' => null,
                        'pending_selected_option' => null,
                        'clarification_options' => [],
                    ];

                    if ($service !== null) {
                        $contextUpdate['current_service_id'] = $service->id;
                        $contextUpdate['current_service_name'] = $service->name;
                    }

                    $this->context->updateState($incoming->sessionId, $contextUpdate);

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $service, null);

                    return $response;
                }

                if ($actionKeyResult['intent'] === ChatbotIntent::ServiceSearch) {
                    // "الخدمات الإلكترونية" and its main-menu key open the
                    // DB-backed guided categories flow.
                    $guided = $this->guidedDiscovery->startElectronicDiscovery($incoming);

                    $this->context->updateState($incoming->sessionId, array_merge(
                        $guided['context'],
                        ['should_stop_pipeline' => true],
                    ));

                    $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $prediction, $startTime, $route, null, null);

                    return $guided['response'];
                }

                $response = $this->registry->resolve($actionKeyResult['intent'])->handle($incoming, null);

                $contextUpdate = [
                    'last_intent' => $actionKeyResult['intent']->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                    'current_domain' => $actionKeyResult['domain'],
                    'current_service_id' => null,
                    'current_service_name' => null,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ];

                // A handler that asks for a typed answer keeps the conversation
                // in a clarification state so the next message is resolved as a
                // typed option (water area, service, …) — not as free intent.
                if ($response->needsClarification && $response->nextConversationState === null) {
                    $contextUpdate['state'] = ConversationState::WaitingForClarification->value;
                    $contextUpdate['needs_clarification'] = true;
                    $contextUpdate['pending_field'] = $response->clarificationType ?? 'general';
                    $contextUpdate['clarification_options'] = $this->buildClarificationOptions($response->items);
                }

                if ($actionKeyResult['intent'] === ChatbotIntent::CreateComplaint) {
                    $contextUpdate['workflow_type'] = 'complaint';
                    $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? null;
                } elseif ($actionKeyResult['intent'] === ChatbotIntent::ContactRequest) {
                    $contextUpdate['workflow_type'] = 'contact_request';
                    $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? null;
                } elseif ($actionKeyResult['intent'] === ChatbotIntent::TrackWorkflow) {
                    $contextUpdate['workflow_type'] = 'tracking';

                    if (($response->workflow['current_step'] ?? null) === 'tracking_number' && $response->type === 'workflow_tracking') {
                        $contextUpdate['state'] = ConversationState::WaitingForTrackingNumber->value;
                        $contextUpdate['pending_field'] = 'tracking_number';
                    }
                }

                $this->context->updateState($incoming->sessionId, $contextUpdate);

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, null);

                return $response;
            }

            // Step 2b: Typed water-area key — resolve the area by ID and answer
            // with the real schedule. Works from any conversation state.
            // The normalizer strips ":", so the raw message is also matched.
            $areaKeyMatches = null;
            if (preg_match('/^water-area:(\d+)$/', $normalizedMessage, $m)) {
                $areaKeyMatches = $m;
            } elseif (preg_match('/^water-area:(\d+)$/', $incoming->message, $m)) {
                $areaKeyMatches = $m;
            }

            if ($areaKeyMatches !== null) {
                $area = $this->findWaterAreaById((int) $areaKeyMatches[1]);

                if ($area === null) {
                    $response = new ChatResponseData(
                        message: 'عذرًا، لا توجد هذه المنطقة في جدول المياه.',
                        type: 'text',
                    );
                } else {
                    $waterRequest = new IncomingChatMessageData(
                        message: $area->name,
                        sessionId: $incoming->sessionId,
                        userId: $incoming->userId,
                        channel: $incoming->channel,
                    );

                    $response = $this->registry->resolve(ChatbotIntent::WaterSchedule)->handle($waterRequest, null);
                }

                $waterPrediction = new IntentPredictionData(
                    intent: ChatbotIntent::WaterSchedule,
                    confidence: 1.0,
                    source: 'rule',
                    accepted: true,
                );
                $waterRoute = new MunicipalityDomainRouteData(
                    domain: 'water_schedule',
                    intent: ChatbotIntent::WaterSchedule,
                    handlerKey: 'water_schedule',
                    confidence: 1.0,
                    source: 'action_key',
                );

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => ChatbotIntent::WaterSchedule->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                    'current_domain' => 'water_schedule',
                    'current_area_id' => $area?->id,
                    'current_area_name' => $area?->name,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ]);

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $waterPrediction, $startTime, $waterRoute, null, null);

                return $response;
            }

            // Step 2c: Typed workflow keys (workflow:confirm / workflow:continue
            // / workflow:switch / workflow:cancel) — translate to the equivalent
            // confirmation command and let the active workflow decide.
            $workflowKeyCommand = $this->resolveWorkflowActionKey($normalizedMessage);
            if ($workflowKeyCommand !== null && $this->isWorkflowActive($state)) {
                return $this->handleWorkflowInput($incoming, $workflowKeyCommand, $state, $conversation, $startTime);
            }

            // Step 2d: Global commands work from ANY conversation state and
            // always end with a clean, lock-free outcome. The guided discovery
            // keeps ownership of its own back/cancel inputs inside its flows.
            if ($this->confirmationFlow->isGlobalCommand($normalizedMessage)) {
                $guidedOwnsInput = $state->state === ConversationState::WaitingForServiceSelection
                && $this->guidedDiscovery->isBackOrCancel($normalizedMessage);

                if (! $guidedOwnsInput) {
                    // "إلغاء" truly cancels: an active workflow draft is
                    // cancelled, then the conversation is cleaned up.
                    if ($this->isWorkflowActive($state) && $this->confirmationFlow->isGlobalCancel($normalizedMessage)) {
                        $this->workflowEngine->cancel($incoming->sessionId);
                    }

                    $this->context->reset($incoming->sessionId);
                    $state = $this->context->getState($incoming->sessionId);

                    $commandPrediction = $this->predictor->predict($normalizedMessage);
                    $commandRoute = $this->domainRouter->route($commandPrediction->intent, $normalizedMessage, $state);

                    $isCancelling = $this->confirmationFlow->isGlobalCancel($normalizedMessage);
                    $isLeaving = $this->confirmationFlow->isExit($normalizedMessage);

                    $response = $isLeaving
                        ? new ChatResponseData(
                            message: 'شكراً لاستخدامك مساعد بلدية إذنا. مع السلامة 👋',
                            type: 'text',
                            nextConversationState: ConversationState::Normal->value,
                        )
                        : new ChatResponseData(
                            message: $isCancelling
                                ? 'تم إلغاء الطلب. شو حابب تعمل الآن؟'
                                : 'تم العودة إلى القائمة الرئيسية. شو حابب تعمل الآن؟',
                            type: 'text',
                            actions: GreetingHandler::MAIN_MENU_ACTIONS,
                            nextConversationState: ConversationState::Normal->value,
                        );

                    // The main menu is now the active selection: the next
                    // typed number/label resolves against the menu options
                    // instead of falling into the generic classifier.
                    if (! $isLeaving) {
                        $this->context->updateState($incoming->sessionId, $this->buildMainMenuContextUpdate());
                    }

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $commandPrediction, $startTime, $commandRoute, null, null);

                    return $response;
                }
            }

            // Step 2e: Compound switch — "إلغاء والانتقال إلى X" cancels any
            // active draft and navigates to X. No interruption confirmation.
            if (preg_match('/^الغاء\s+والانتقال\s+الي\s+(.+)$/u', $normalizedMessage, $compoundMatch)) {
                if ($this->isWorkflowActive($state)) {
                    $this->workflowEngine->cancel($incoming->sessionId);
                }

                $this->context->reset($incoming->sessionId);
                $state = $this->context->getState($incoming->sessionId);

                $navigated = $this->navigateToTargetMessage(
                    target: $compoundMatch[1],
                    incoming: $incoming,
                    conversation: $conversation,
                    startTime: $startTime,
                    state: $state,
                    suspensionNote: null,
                    storedNormalized: $normalizedMessage,
                );

                if ($navigated !== null) {
                    return $navigated;
                }
            }

            // Step 2f: A standalone greeting NEVER destroys an in-progress
            // workflow: it only acknowledges and keeps the state + draft.
            if ($this->isStandaloneGreeting($normalizedMessage)) {
                if ($this->isWorkflowActive($state)) {
                    $greetPrediction = $this->predictor->predict($normalizedMessage);
                    $greetRoute = $this->domainRouter->route($greetPrediction->intent, $normalizedMessage, $state);

                    $response = new ChatResponseData(
                        message: "أهلًا فيك 👋 لسه بنكمّل طلبك الحالي.\nاكتب \"متابعة طلب\" للاستمرار أو \"إلغاء\" لإلغائه.",
                        type: 'text',
                        nextConversationState: $state->state->value,
                    );

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $greetPrediction, $startTime, $greetRoute, null, null);

                    return $response;
                }

                return $this->handleStandaloneGreeting($incoming, $conversation, $startTime, $state, $normalizedMessage);
            }

            // Step 2g: Pure thanks ("تم" / "تمام" / "شكرا") never routes
            // randomly. Outside a workflow it is acknowledged; inside a
            // workflow (outside the confirmation step) it is acknowledged
            // while keeping the draft fully intact.
            if ($this->isThanksMessage($normalizedMessage) && ! $this->isWorkflowActive($state)) {
                return $this->handleSimpleIntent($incoming, $conversation, $startTime, $state, ChatbotIntent::Thanks, $normalizedMessage);
            }

            if ($this->isThanksMessage($normalizedMessage)
                && $this->isWorkflowActive($state)
                && $state->state !== ConversationState::WorkflowConfirming) {
                $thanksPrediction = $this->predictor->predict($normalizedMessage);
                $thanksRoute = $this->domainRouter->route($thanksPrediction->intent, $normalizedMessage, $state);

                $response = new ChatResponseData(
                    message: 'تمام 👍 ممكن تكمل طلبك: اكتب "متابعة طلب" للاستمرار أو "إلغاء" للإلغاء.',
                    type: 'text',
                    nextConversationState: $state->state->value,
                );

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $thanksPrediction, $startTime, $thanksRoute, null, null);

                return $response;
            }

            // Step 3: Resume an existing draft BEFORE any intent routing, so
            // "متابعة طلب" / "متابعة الشكوى" / "استكمال" always resume the
            // stored draft and never fall into tracking or the generic
            // pipeline.
            if ($this->hasResumeMarker($normalizedMessage)
                && ! $this->confirmationFlow->isSwitch($normalizedMessage)
                && $this->draftRepository->findActiveBySession($incoming->sessionId) !== null) {
                $resumeResult = $this->workflowEngine->resume($incoming->sessionId);
                $resumeResponse = $this->responseBuilder->build($resumeResult);

                $resumePrediction = new IntentPredictionData(
                    intent: ChatbotIntent::ResumeWorkflow,
                    confidence: 1.0,
                    source: 'rule',
                    accepted: true,
                );
                $resumeRoute = new MunicipalityDomainRouteData(
                    domain: 'citizen_workflow',
                    intent: ChatbotIntent::ResumeWorkflow,
                    handlerKey: 'resume_workflow',
                    confidence: 1.0,
                    source: 'rule',
                );

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => ChatbotIntent::ResumeWorkflow->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $resumeResult->nextConversationState ?? ConversationState::WorkflowCollectingData->value,
                    'current_domain' => 'citizen_workflow',
                    'workflow_type' => $resumeResult->workflowType,
                    'workflow_draft_id' => $resumeResult->draftId !== null ? (string) $resumeResult->draftId : null,
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ]);

                $this->storeMessages($conversation->id, $incoming, $resumeResponse, $normalizedMessage, $resumePrediction, $startTime, $resumeRoute, null, null);

                return $resumeResponse;
            }

            // Step 4: Detect active workflow command
            if ($this->isWorkflowActive($state)) {
                return $this->handleWorkflowInput($incoming, $normalizedMessage, $state, $conversation, $startTime);
            }

            // Step 5: Extract greeting / thanks (always escapes any service lock)
            $greetingResult = $this->tryCompoundGreeting($normalizedMessage, $incoming, $conversation, $startTime, $state);
            if ($greetingResult !== null) {
                return $greetingResult;
            }

            // Step 6: Resolve typed pending clarification BEFORE intent prediction
            $pendingType = $state->pendingField ?? 'service';

            if ($state->state === ConversationState::WaitingForTrackingNumber) {
                // The next message belongs to tracking: it IS the tracking number.
                $handler = $this->registry->resolve(ChatbotIntent::TrackWorkflow);
                $response = $handler->handle($incoming, null);

                $trackingPrediction = new IntentPredictionData(
                    intent: ChatbotIntent::TrackWorkflow,
                    confidence: 1.0,
                    source: 'rule',
                    accepted: true,
                );
                $trackingRoute = new MunicipalityDomainRouteData(
                    domain: 'citizen_workflow',
                    intent: ChatbotIntent::TrackWorkflow,
                    handlerKey: 'track_workflow',
                    confidence: 1.0,
                    source: 'clarification',
                );

                $nextState = $response->type === 'workflow_not_found'
                    ? ConversationState::WaitingForTrackingNumber->value
                    : ConversationState::Normal->value;

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => ChatbotIntent::TrackWorkflow->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $nextState,
                    'current_domain' => 'citizen_workflow',
                    'needs_clarification' => false,
                    'pending_field' => null,
                    'pending_selected_option' => null,
                    'clarification_options' => [],
                ]);

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $trackingPrediction, $startTime, $trackingRoute, null, null);

                return $response;
            }

            if ($state->state === ConversationState::WaitingForServiceSelection) {
                // An explicit municipality-domain switch exits the guided flow
                // instead of being treated as a category/service selection.
                // A message matching one of the currently offered options is a
                // selection, never a domain switch.
                // Pure control intents (cancel/resume) stay inside the flow.
                $guidedPrediction = $this->predictor->predict($normalizedMessage);
                $guidedRoute = $this->domainRouter->route($guidedPrediction->intent, $normalizedMessage, $state);
                ChatbotTrace::log([
                    'event' => 'guided_switch_check',
                    'session_id' => $incoming->sessionId,
                    'normalized' => $normalizedMessage,
                    'predicted_intent' => $guidedPrediction->intent->value,
                    'predicted_source' => $guidedPrediction->source,
                    'predicted_conf' => $guidedPrediction->confidence,
                    'predicted_accepted' => $guidedPrediction->accepted,
                    'route_domain' => $guidedRoute->domain,
                    'state_before' => $state->state->value,
                    'current_domain' => $state->currentDomain ?? null,
                    'pending_field' => $state->pendingField ?? null,
                ]);
                $guidedDomainSwitch = $this->isExplicitDomainSwitch(
                    $guidedPrediction->intent,
                    $guidedRoute,
                    $state,
                    $guidedPrediction,
                ) && ! $this->guidedDiscovery->matchesOfferedOption($normalizedMessage, $state)
                && ! in_array($guidedPrediction->intent, [
                    ChatbotIntent::CancelWorkflow,
                    ChatbotIntent::ResumeWorkflow,
                ], true);

                if ($guidedDomainSwitch) {
                    // Fall through to Step 7 so the domain switch takes over.
                } else {
                    $guided = $this->guidedDiscovery->handle($incoming, $state);
                    if ($guided !== null) {
                        \Log::debug('Pipeline: typed clarification matched', [
                            'session' => $incoming->sessionId,
                            'message' => $incoming->message,
                            'state' => $state->state->value,
                            'response_type' => $guided['response']->type,
                            'context' => $guided['context'] ?? [],
                        ]);

                        $this->context->updateState($incoming->sessionId, array_merge(
                            $guided['context'],
                            ['should_stop_pipeline' => true],
                        ));

                        $guidedPrediction = new IntentPredictionData(
                            intent: ChatbotIntent::ServiceSearch,
                            confidence: 1.0,
                            source: 'rule',
                            accepted: true,
                        );
                        $guidedRoute = new MunicipalityDomainRouteData(
                            domain: $guided['context']['current_domain'] ?? 'electronic_services',
                            intent: ChatbotIntent::ServiceSearch,
                            handlerKey: 'service_search',
                            confidence: 1.0,
                            source: 'clarification',
                        );

                        $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $guidedPrediction, $startTime, $guidedRoute, null, null);

                        return $guided['response'];
                    }
                }
            }

            if ($state->state === ConversationState::WaitingForSelection || $state->state === ConversationState::WaitingForClarification) {
                // municipality_main_menu pending: resolve BEFORE the generic
                // resolver, because menu options are numbers/labels that the
                // generic resolver also understands — but only the menu
                // dispatch knows what each option means. Unresolvable menu
                // input stays inside the menu flow (never the classifier).
                if ($pendingType === 'municipality_main_menu') {
                    $mainMenuResult = $this->tryResolveMainMenuSelection($normalizedMessage, $state, $conversation, $startTime, $incoming);
                    if ($mainMenuResult !== null) {
                        return $mainMenuResult;
                    }

                    return $this->invalidOptionResponse($normalizedMessage, $state, $conversation, $startTime, $incoming);
                }

                // Try the generic option resolver for ALL clarification types
                // (water_area, service, service_category, municipality_main_menu, etc.)
                $optionResult = $this->clarificationResolver->resolveOptionSelection($normalizedMessage, $state);

                if ($optionResult !== null && $optionResult['matched'] === true) {
                    $option = $optionResult['option'];
                    $actionKey = $option['key'] ?? null;
                    $entityType = $option['entity_type'] ?? null;
                    $entityId = $option['entity_id'] ?? null;

                    ChatbotTrace::log([
                        'event' => 'resolution',
                        'session_id' => $incoming->sessionId,
                        'path' => 'clarification',
                        'method' => $optionResult['match_type'] ?? 'option',
                        'entity_type' => $entityType ?? $pendingType,
                        'matched_label' => $option['label'] ?? $option['name'] ?? null,
                        'matched_id' => $entityId,
                        'matched_position' => $option['position'] ?? null,
                    ]);

                    $this->context->updateState($incoming->sessionId, [
                        'state' => ConversationState::Normal->value,
                        'needs_clarification' => false,
                        'pending_field' => null,
                        'pending_selected_option' => null,
                        'clarification_options' => [],
                    ]);

                    if ($pendingType === 'water_area') {
                        $areaName = $option['label'] ?? $option['name'] ?? $normalizedMessage;
                        $this->context->updateState($incoming->sessionId, [
                            'current_domain' => 'water_schedule',
                            'current_area_id' => $entityId,
                            'current_area_name' => $areaName,
                        ]);

                        $handler = $this->registry->resolve(ChatbotIntent::WaterSchedule);

                        $waterRequest = new IncomingChatMessageData(
                            message: $areaName,
                            sessionId: $incoming->sessionId,
                            userId: $incoming->userId,
                            channel: $incoming->channel,
                        );

                        $response = $handler->handle($waterRequest, null);

                        $waterPrediction = new IntentPredictionData(
                            intent: ChatbotIntent::WaterSchedule,
                            confidence: 1.0,
                            source: 'rule',
                            accepted: true,
                        );
                        $waterRoute = new MunicipalityDomainRouteData(
                            domain: 'water_schedule',
                            intent: ChatbotIntent::WaterSchedule,
                            handlerKey: 'water_schedule',
                            confidence: 1.0,
                            source: 'clarification',
                        );

                        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $waterPrediction, $startTime, $waterRoute, null, null);

                        return $response;
                    }

                    if ($pendingType === 'service' || $pendingType === 'electronic_service' || $pendingType === null) {
                        $serviceId = $option['entity_id'] ?? $entityId;
                        $serviceName = $option['label'] ?? $option['name'];

                        if ($serviceId !== null && $serviceName !== null) {
                            $resolvedService = $this->buildResolvedService((int) $serviceId, $serviceName);

                            if ($resolvedService !== null) {
                                $this->context->updateState($incoming->sessionId, [
                                    'current_service_id' => $resolvedService->id,
                                    'current_service_name' => $resolvedService->name,
                                ]);

                                $response = new ChatResponseData(
                                    message: "تم اختيار خدمة \"{$resolvedService->name}\".\nممكن تسأل عن الرسوم، المتطلبات، خطوات التقديم، المدة، أو مكان التقديم.",
                                    type: 'text',
                                    actions: [
                                        ['label' => 'الرسوم', 'key' => 'service-action:fees', 'payload' => ['service_id' => $resolvedService->id]],
                                        ['label' => 'المتطلبات', 'key' => 'service-action:requirements', 'payload' => ['service_id' => $resolvedService->id]],
                                        ['label' => 'خطوات التقديم', 'key' => 'service-action:steps', 'payload' => ['service_id' => $resolvedService->id]],
                                        ['label' => 'المدة', 'key' => 'service-action:duration', 'payload' => ['service_id' => $resolvedService->id]],
                                        ['label' => 'مكان التقديم', 'key' => 'service-action:location', 'payload' => ['service_id' => $resolvedService->id]],
                                    ],
                                );

                                $this->context->updateState($incoming->sessionId, [
                                    'current_domain' => 'electronic_services',
                                    'last_intent' => ChatbotIntent::ServiceSearch->value,
                                ]);

                                $selectPrediction = new IntentPredictionData(
                                    intent: ChatbotIntent::ServiceSearch,
                                    confidence: 1.0,
                                    source: 'rule',
                                    accepted: true,
                                );
                                $selectRoute = new MunicipalityDomainRouteData(
                                    domain: 'electronic_services',
                                    intent: ChatbotIntent::ServiceSearch,
                                    handlerKey: 'service_search',
                                    confidence: 1.0,
                                    source: 'clarification',
                                );

                                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $selectPrediction, $startTime, $selectRoute, $resolvedService, null);

                                return $response;
                            }
                        }
                    }
                    if ($pendingType === 'service_category') {
                        $categoryId = $entityId !== null ? (int) $entityId : 0;
                        $categoryName = $option['label'] ?? $option['name'] ?? null;

                        $guided = $this->guidedDiscovery->startCategoryServices($categoryId, $categoryName);

                        $this->context->updateState($incoming->sessionId, array_merge(
                            $guided['context'],
                            ['should_stop_pipeline' => true],
                        ));

                        $categoryPrediction = new IntentPredictionData(
                            intent: ChatbotIntent::ServiceSearch,
                            confidence: 1.0,
                            source: 'rule',
                            accepted: true,
                        );
                        $categoryRoute = new MunicipalityDomainRouteData(
                            domain: 'electronic_services',
                            intent: ChatbotIntent::ServiceSearch,
                            handlerKey: 'service_search',
                            confidence: 1.0,
                            source: 'clarification',
                        );

                        $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $categoryPrediction, $startTime, $categoryRoute, null, null);

                        return $guided['response'];
                    }
                }

                // Fall through to old numeric/id resolution for backward compatibility
                $selection = $this->clarificationResolver->resolveNumericSelection($normalizedMessage, $state);
                if ($selection === null && ctype_digit($normalizedMessage)) {
                    $selection = $this->clarificationResolver->resolveOptionSelectionById((int) $normalizedMessage, $state);
                }

                if ($selection !== null && $selection->selectedOption !== null) {
                    $this->context->updateState($incoming->sessionId, [
                        'state' => ConversationState::Normal->value,
                        'needs_clarification' => false,
                        'pending_field' => null,
                        'pending_selected_option' => null,
                        'clarification_options' => [],
                    ]);

                    if ($selection->selectedServiceId !== null) {
                        $resolvedService = $this->buildResolvedService($selection->selectedServiceId, $selection->selectedServiceName);

                        if ($resolvedService !== null) {
                            $this->context->updateState($incoming->sessionId, [
                                'current_service_id' => $resolvedService->id,
                                'current_service_name' => $resolvedService->name,
                            ]);

                            $serviceId = $resolvedService->id;
                            $serviceName = $resolvedService->name;

                            $response = new ChatResponseData(
                                message: "تم اختيار خدمة \"{$serviceName}\".\nممكن تسأل عن الرسوم، المتطلبات، خطوات التقديم، المدة، أو مكان التقديم.",
                                type: 'text',
                                actions: [
                                    ['label' => 'الرسوم', 'key' => 'service-action:fees', 'payload' => ['service_id' => $serviceId]],
                                    ['label' => 'المتطلبات', 'key' => 'service-action:requirements', 'payload' => ['service_id' => $serviceId]],
                                    ['label' => 'خطوات التقديم', 'key' => 'service-action:steps', 'payload' => ['service_id' => $serviceId]],
                                    ['label' => 'المدة', 'key' => 'service-action:duration', 'payload' => ['service_id' => $serviceId]],
                                    ['label' => 'مكان التقديم', 'key' => 'service-action:location', 'payload' => ['service_id' => $serviceId]],
                                ],
                            );

                            $selectPrediction = new IntentPredictionData(
                                intent: ChatbotIntent::ServiceSearch,
                                confidence: 1.0,
                                source: 'rule',
                                accepted: true,
                            );
                            $selectRoute = new MunicipalityDomainRouteData(
                                domain: 'electronic_services',
                                intent: ChatbotIntent::ServiceSearch,
                                handlerKey: 'service_search',
                                confidence: 1.0,
                                source: 'clarification',
                            );

                            $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $selectPrediction, $startTime, $selectRoute, $resolvedService, null);

                            return $response;
                        }
                    }

                    if ($selection->selectedAreaId !== null) {
                        $this->context->updateState($incoming->sessionId, [
                            'current_domain' => 'water_schedule',
                            'current_area_id' => $selection->selectedAreaId,
                            'current_area_name' => $selection->selectedAreaName,
                        ]);

                        $handler = $this->registry->resolve(ChatbotIntent::WaterSchedule);

                        $waterRequest = new IncomingChatMessageData(
                            message: $selection->selectedAreaName ?? $normalizedMessage,
                            sessionId: $incoming->sessionId,
                            userId: $incoming->userId,
                            channel: $incoming->channel,
                        );

                        $response = $handler->handle($waterRequest, null);

                        $waterPrediction = new IntentPredictionData(
                            intent: ChatbotIntent::WaterSchedule,
                            confidence: 1.0,
                            source: 'rule',
                            accepted: true,
                        );
                        $waterRoute = new MunicipalityDomainRouteData(
                            domain: 'water_schedule',
                            intent: ChatbotIntent::WaterSchedule,
                            handlerKey: 'water_schedule',
                            confidence: 1.0,
                            source: 'clarification',
                        );

                        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $waterPrediction, $startTime, $waterRoute, null, null);

                        return $response;
                    }
                }
            }

            // Step 6b: Resolve municipality main menu selection (by number, label, or key)
            $mainMenuResult = $this->tryResolveMainMenuSelection($normalizedMessage, $state, $conversation, $startTime, $incoming);
            if ($mainMenuResult !== null) {
                return $mainMenuResult;
            }

            // Step 6c: A pending clarification that could not be resolved
            // never falls through to the generic classifier. FAILED OPTION
            // RESOLUTION != UNKNOWN MESSAGE. The only ways out of a pending
            // clarification are an explicit domain switch (the user asked
            // for another domain entirely) or a cancel/resume command.
            if ($state->pendingField !== null && ! empty($state->clarificationOptions)) {
                $pendingEscapePrediction = $this->predictor->predict($normalizedMessage);
                $pendingEscapeRoute = $this->domainRouter->route($pendingEscapePrediction->intent, $normalizedMessage, $state);
                $pendingEscape = $this->isExplicitDomainSwitch($pendingEscapePrediction->intent, $pendingEscapeRoute, $state, $pendingEscapePrediction)
                    || in_array($pendingEscapePrediction->intent, [
                        ChatbotIntent::CancelWorkflow,
                        ChatbotIntent::ResumeWorkflow,
                    ], true);

                if (! $pendingEscape) {
                    return $this->invalidOptionResponse($normalizedMessage, $state, $conversation, $startTime, $incoming);
                }
            }

            // Step 7: Detect strong explicit domain
            $prediction = $this->predictor->predict($normalizedMessage);
            $intent = $prediction->intent;
            $route = $this->domainRouter->route($intent, $normalizedMessage, $state);

            // A service question that names a real service is an in-domain
            // question, never a domain switch. Resolve it up-front so the
            // switch guard and Step 14b always agree on the same service.
            $entityResolvedService = null;
            if ($intent->isServiceRelated()) {
                $entityResolvedService = $this->entityResolver->resolve(
                    $normalizedMessage,
                    $state->currentServiceName,
                );
            }

            $explicitDomainSwitch = $this->isExplicitDomainSwitch($intent, $route, $state, $prediction)
                && $entityResolvedService === null;

            // Step 8: An explicit navigation NEVER locks the session.
            // INCOMPLETE WORKFLOW != LOCKED SESSION: the active draft is
            // merely suspended (it stays stored and resumable later) while
            // the conversation moves to the requested area.
            if ($explicitDomainSwitch) {
                $workflowWasActive = $this->isWorkflowActive($state);

                $this->context->reset($incoming->sessionId);
                $state = $this->context->getState($incoming->sessionId);

                $suspensionNote = $workflowWasActive
                    ? 'لديك طلب قيد الإكمال — اكتب "متابعة طلب" للاستمرار لاحقًا.'
                    : null;

                return $this->navigateToTargetMessage(
                    target: $incoming->message,
                    incoming: $incoming,
                    conversation: $conversation,
                    startTime: $startTime,
                    state: $state,
                    suspensionNote: $suspensionNote,
                    storedNormalized: $normalizedMessage,
                );
            }

            // Step 10: Handle municipality main/help
            if ($intent === ChatbotIntent::MunicipalityAssistantHome) {
                $this->context->reset($incoming->sessionId);

                $response = new ChatResponseData(
                    message: 'مرحباً بك في المساعد الذكي لبلدية إذنا. يمكنني مساعدتك في الوصول إلى الخدمات والمعلومات البلدية، وتقديم الشكاوى والطلبات ومتابعتها.',
                    type: 'text',
                    actions: GreetingHandler::MAIN_MENU_ACTIONS,
                    nextConversationState: ConversationState::Normal->value,
                );

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => $intent->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => ConversationState::Normal->value,
                    'current_domain' => 'general',
                ]);

                $this->context->updateState($incoming->sessionId, $this->buildMainMenuContextUpdate());

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, null);

                return $response;
            }

            // Step 11: Explicit service-property follow-up with active service.
            // The selected service is passive context: this branch only fires
            // when the message carries explicit property evidence and never
            // blocks greetings, thanks, or explicit municipality domains.
            if ($state->currentServiceId !== null) {
                $propertyIntent = $this->propertyIntentDetector->detect($normalizedMessage);

                if ($propertyIntent !== null) {
                    $otherServiceMentioned = false;

                    foreach ($this->entityResolver->resolveMultiple($normalizedMessage) as $candidate) {
                        if ((int) ($candidate['id'] ?? 0) !== $state->currentServiceId) {
                            $otherServiceMentioned = true;
                            break;
                        }
                    }

                    if (! $otherServiceMentioned) {
                        $resolvedService = $this->buildResolvedService($state->currentServiceId, $state->currentServiceName);

                        if ($resolvedService !== null) {
                            $handler = $this->registry->resolve($propertyIntent);
                            $response = $handler->handle($incoming, $resolvedService);

                            $this->context->updateState($incoming->sessionId, [
                                'last_intent' => $propertyIntent->value,
                                'previous_intent' => $state->lastIntent,
                                'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                                'current_domain' => 'electronic_services',
                                'current_service_id' => $state->currentServiceId,
                                'current_service_name' => $state->currentServiceName,
                                'needs_clarification' => false,
                                'pending_field' => null,
                                'pending_selected_option' => null,
                                'clarification_options' => [],
                            ]);

                            $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $resolvedService, null);

                            return $response;
                        }
                    }
                }
            }

            // Step 12: Guided service discovery
            $guided = $this->guidedDiscovery->handle($incoming, $state);
            if ($guided !== null && ($guided['context']['should_stop_pipeline'] ?? false)) {
                $this->context->updateState($incoming->sessionId, $guided['context']);

                $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $prediction, $startTime, $route, null, null);

                return $guided['response'];
            }

            // Step 13: Current valid context follow-up
            if ($state->currentServiceId !== null && $intent->isServiceRelated()) {
                $resolvedService = $this->buildResolvedService($state->currentServiceId, $state->currentServiceName);

                if ($resolvedService !== null) {
                    $handler = $this->registry->resolve($intent);

                    $response = $handler->handle($incoming, $resolvedService);

                    $this->context->updateState($incoming->sessionId, [
                        'last_intent' => $intent->value,
                        'previous_intent' => $state->lastIntent,
                        'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                        'current_domain' => 'electronic_services',
                    ]);

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $resolvedService, null);

                    return $response;
                }
            }

            // Step 14: Smart Search (only for Unknown intents)
            $service = null;
            if ($intent === ChatbotIntent::Unknown) {
                $searchResult = $this->smartSearch->search(
                    message: $incoming->message,
                    currentServiceId: $state->currentServiceId,
                    limit: 5,
                );

                if ($searchResult->isConfident && $searchResult->bestMatch !== null) {
                    $service = new ResolvedServiceData(
                        id: $searchResult->bestMatch->serviceId,
                        name: $searchResult->bestMatch->serviceName,
                    );
                } elseif ($searchResult->requiresClarification) {
                    $clarificationOptions = [];
                    foreach ($searchResult->matches as $i => $match) {
                        $num = $i + 1;
                        $clarificationOptions[] = [
                            'id' => $match->serviceId,
                            'name' => $match->serviceName,
                            'number' => $num,
                        ];
                    }

                    $this->context->updateState($incoming->sessionId, [
                        'state' => ConversationState::WaitingForSelection->value,
                        'needs_clarification' => true,
                        'pending_field' => 'service',
                        'clarification_options' => $clarificationOptions,
                        'last_intent' => $intent->value,
                    ]);

                    $lines = ['لم أجد خدمة مطابقة تماماً. هل تقصد:'];
                    foreach ($clarificationOptions as $opt) {
                        $lines[] = "{$opt['number']} {$opt['name']}";
                    }
                    $lines[] = 'ممكن تختار رقم الخدمة اللي تقصدها.';

                    $response = new ChatResponseData(
                        message: implode("\n", $lines),
                        type: 'clarification',
                        needsClarification: true,
                        clarificationType: 'service',
                        actions: array_map(fn ($opt) => [
                            'label' => "{$opt['number']} {$opt['name']}",
                            'value' => (string) $opt['number'],
                        ], $clarificationOptions),
                    );

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, $searchResult);

                    return $response;
                }
            }

            // Step 14b: Entity resolution for service-related intents
            if ($intent->isServiceRelated() && $service === null) {
                $service = $entityResolvedService ?? $this->entityResolver->resolve(
                    $normalizedMessage,
                    $state->currentServiceName,
                );

                if ($service === null) {
                    $candidates = $this->entityResolver->resolveMultiple($normalizedMessage);
                    $clarification = $this->clarificationResolver->needsClarification($normalizedMessage, $candidates);

                    if ($clarification !== null && $clarification->needsClarification) {
                        $this->context->updateState($incoming->sessionId, [
                            'state' => ConversationState::WaitingForSelection->value,
                            'needs_clarification' => true,
                            'pending_field' => 'service',
                            'clarification_options' => $clarification->options,
                            'last_intent' => $intent->value,
                        ]);

                        $response = new ChatResponseData(
                            message: $clarification->message,
                            type: 'clarification',
                            needsClarification: true,
                            clarificationType: 'service',
                            actions: array_map(fn ($opt) => [
                                'label' => "{$opt['number']} {$opt['name']}",
                                'value' => (string) $opt['number'],
                            ], $clarification->options),
                        );

                        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, null);

                        return $response;
                    }
                }
            }

            // Step 15: Intent classifier fallback
            if ($intent === ChatbotIntent::Unknown) {
                // Fallback loop guard: after two consecutive unproductive
                // turns the user is returned to a safe main menu instead of
                // receiving an endless chain of "لم أفهمك" messages.
                if ($state->fallbackCount >= 2) {
                    $response = new ChatResponseData(
                        message: 'واضح إني ما فهمت طلبك 😅 خلينا نبدأ من الخيارات الرئيسية.',
                        type: 'text',
                        actions: GreetingHandler::MAIN_MENU_ACTIONS,
                        nextConversationState: ConversationState::Normal->value,
                    );

                    $this->context->updateState($incoming->sessionId, [
                        'last_intent' => $intent->value,
                        'previous_intent' => $state->lastIntent,
                        'state' => ConversationState::Normal->value,
                        'current_domain' => 'general',
                        'needs_clarification' => false,
                        'pending_field' => null,
                        'pending_selected_option' => null,
                        'clarification_options' => [],
                    ]);

                    $this->context->updateState($incoming->sessionId, $this->buildMainMenuContextUpdate());

                    $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $service ?? null, $searchResult ?? null);

                    return $response;
                }

                $response = $this->registry->resolve(ChatbotIntent::Unknown)->handle($incoming, $service);

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => $intent->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                    'current_domain' => $route->domain,
                ]);

                if ($response->clarificationType === 'municipality_main_menu') {
                    $this->context->updateState($incoming->sessionId, [
                        'state' => ConversationState::WaitingForSelection->value,
                        'current_domain' => 'municipality_main_menu',
                        'needs_clarification' => true,
                        'pending_field' => 'municipality_main_menu',
                        'pending_selected_option' => null,
                        'clarification_options' => GreetingHandler::MAIN_MENU_CLARIFICATION_OPTIONS,
                    ]);
                } elseif ($response->needsClarification && $response->nextConversationState === null) {
                    $this->context->updateState($incoming->sessionId, [
                        'state' => ConversationState::WaitingForClarification->value,
                        'needs_clarification' => true,
                        'pending_field' => $response->clarificationType ?? 'general',
                        'clarification_options' => $this->buildClarificationOptions($response->items),
                    ]);
                }

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $service, $searchResult ?? null);

                return $response;
            }

            // Step 16: Domain Router and general intents
            if ($intent !== ChatbotIntent::Unknown) {
                $handler = $this->registry->resolve($intent);
                $resolvedService = ($intent->isServiceRelated() && $service !== null) ? $service : null;

                $response = $handler->handle($incoming, $resolvedService);

                $contextUpdate = [
                    'last_intent' => $intent->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                    'current_domain' => $route->domain,
                ];

                if ($resolvedService !== null) {
                    $contextUpdate['current_service_id'] = $resolvedService->id;
                    $contextUpdate['current_service_name'] = $resolvedService->name;
                }

                if ($intent === ChatbotIntent::CreateComplaint || $intent === ChatbotIntent::ContactRequest || $intent === ChatbotIntent::TrackWorkflow) {
                    $contextUpdate['workflow_type'] = match ($intent) {
                        ChatbotIntent::CreateComplaint => 'complaint',
                        ChatbotIntent::ContactRequest => 'contact_request',
                        ChatbotIntent::TrackWorkflow => 'tracking',
                        default => null,
                    };
                    $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? $response->workflow['draft_id'] ?? null;
                }

                if ($response->needsClarification && $response->nextConversationState === null) {
                    $contextUpdate['state'] = ConversationState::WaitingForClarification->value;
                    $contextUpdate['clarification_options'] = $this->buildClarificationOptions($response->items);
                    $contextUpdate['pending_field'] = $response->clarificationType ?? 'general';
                }

                if ($response->clarificationType === 'municipality_main_menu' && $response->needsClarification) {
                    $contextUpdate['state'] = ConversationState::WaitingForSelection->value;
                    $contextUpdate['current_domain'] = 'municipality_main_menu';
                    $contextUpdate['needs_clarification'] = true;
                    $contextUpdate['pending_field'] = 'municipality_main_menu';
                    $contextUpdate['clarification_options'] = GreetingHandler::MAIN_MENU_CLARIFICATION_OPTIONS;
                }

                $this->context->updateState($incoming->sessionId, $contextUpdate);

                $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $resolvedService, $searchResult ?? null);

                return $response;
            }

            // Step 17: Unknown municipality-wide fallback
            $response = $this->registry->resolve(ChatbotIntent::Unknown)->handle($incoming, $service);

            $this->context->updateState($incoming->sessionId, [
                'last_intent' => $intent->value,
                'previous_intent' => $state->lastIntent,
                'state' => $response->nextConversationState ?? ConversationState::Normal->value,
                'current_domain' => $route->domain,
            ]);

            if ($response->clarificationType === 'municipality_main_menu') {
                $this->context->updateState($incoming->sessionId, [
                    'state' => ConversationState::WaitingForSelection->value,
                    'current_domain' => 'municipality_main_menu',
                    'needs_clarification' => true,
                    'pending_field' => 'municipality_main_menu',
                    'pending_selected_option' => null,
                    'clarification_options' => GreetingHandler::MAIN_MENU_CLARIFICATION_OPTIONS,
                ]);
            }

            $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, $service, $searchResult ?? null);

            return $response;
        });
    }

    private function resolveTrustedActionKey(string $message): ?array
    {
        if (preg_match('/^main-menu:([a-z0-9-]+)$/', $message, $matches)) {
            $actionKey = 'main-menu:'.$matches[1];

            return $this->actionRegistry->resolve($actionKey);
        }

        if (preg_match('/^service-action:([a-z][a-z-]*):(\d+)$/', $message, $matches)) {
            $resolved = $this->actionRegistry->resolveServiceAction($matches[1], (int) $matches[2]);

            if ($resolved === null) {
                return null;
            }

            return [
                'intent' => $resolved['intent'],
                'domain' => $resolved['domain'],
                'handler' => $resolved['handler'],
                'key' => 'service-action:'.$matches[1],
                'payload' => ['service_id' => (int) $matches[2]],
            ];
        }

        if (preg_match('/^service:(\d+)$/', $message, $matches)) {
            return [
                'intent' => ChatbotIntent::ServiceSearch,
                'domain' => 'electronic_services',
                'handler' => 'service_search',
                'key' => 'service:'.$matches[1],
                'service_id' => (int) $matches[1],
            ];
        }

        if (preg_match('/^service-category:(\d+)$/', $message, $matches)) {
            return [
                'intent' => ChatbotIntent::ServiceSearch,
                'domain' => 'electronic_services',
                'handler' => 'service_search',
                'key' => 'service-category:'.$matches[1],
                'category_id' => (int) $matches[1],
            ];
        }

        return null;
    }

    private function parseActionKeyMetadata(string $normalizedMessage): ?array
    {
        if (preg_match('/^main-menu:([a-z0-9-]+)$/', $normalizedMessage, $matches)) {
            return [
                'key' => 'main-menu:'.$matches[1],
                'payload' => [],
            ];
        }

        if (preg_match('/^service-action:([a-z][a-z-]*):(\d+)$/', $normalizedMessage, $matches)) {
            return [
                'key' => 'service-action:'.$matches[1],
                'payload' => ['service_id' => (int) $matches[2]],
            ];
        }

        if (preg_match('/^service:(\d+)$/', $normalizedMessage, $matches)) {
            return [
                'key' => 'service:'.$matches[1],
                'payload' => ['service_id' => (int) $matches[1]],
            ];
        }

        if (preg_match('/^service-category:(\d+)$/', $normalizedMessage, $matches)) {
            return [
                'key' => 'service-category:'.$matches[1],
                'payload' => ['category_id' => (int) $matches[1]],
            ];
        }

        if (preg_match('/^water-area:(\d+)$/', $normalizedMessage, $matches)) {
            return [
                'key' => 'water-area:'.$matches[1],
                'payload' => ['area_id' => (int) $matches[1]],
            ];
        }

        return null;
    }

    private function resolveWorkflowActionKey(string $normalizedMessage): ?string
    {
        return match ($normalizedMessage) {
            'workflow:confirm', 'workflow:continue' => 'تأكيد',
            'workflow:switch' => 'إلغاء والانتقال',
            'workflow:cancel' => 'إلغاء',
            default => null,
        };
    }

    private function isStandaloneGreeting(string $normalizedMessage): bool
    {
        return (bool) preg_match(
            '/^(مرحبا|مرخبا|مر حبا|اهلا|هاي|هلا|هلو|اهلين|السلام عليكم|سلام عليكم|سلام|hello|hi|hey)$/u',
            $normalizedMessage,
        );
    }

    private function handleStandaloneGreeting(
        IncomingChatMessageData $incoming,
        ChatbotConversation $conversation,
        float $startTime,
        ConversationStateData $state,
        string $normalizedMessage,
    ): ChatResponseData {
        // Clear transient locks while keeping the conversation record and
        // messages intact. Any resumable workflow draft stays stored — the
        // greeting never destroys an in-progress request.
        $this->context->reset($incoming->sessionId);

        $freshState = $this->context->getState($incoming->sessionId);

        return $this->handleSimpleIntent($incoming, $conversation, $startTime, $freshState, ChatbotIntent::Greeting, $normalizedMessage);
    }

    /**
     * True when the typed text carries an explicit resume marker
     * (متابعة / استكمال / استمرار / استمر / أكمل / كمل / تابع).
     */
    private function hasResumeMarker(string $normalizedMessage): bool
    {
        return (bool) preg_match('/(?:متابعة|استكمال|استكمل|استمرار|استمر|اكمل|كمل|تابع|تكمل)/u', $normalizedMessage);
    }

    /**
     * Navigates to a free-text target after the conversation state has been
     * cleaned. Used by the compound switch-cancel command and by the
     * suspend-and-navigate path (Step 8).
     */
    private function navigateToTargetMessage(
        string $target,
        IncomingChatMessageData $incoming,
        ChatbotConversation $conversation,
        float $startTime,
        ConversationStateData $state,
        ?string $suspensionNote,
        string $storedNormalized,
    ): ChatResponseData {
        $target = trim($target);
        $targetNormalized = $this->normalizer->normalize($target);

        $targetIncoming = new IncomingChatMessageData(
            message: $target,
            sessionId: $incoming->sessionId,
            userId: $incoming->userId,
            channel: $incoming->channel,
        );

        $targetPrediction = $this->predictor->predict($targetNormalized);
        $targetRoute = $this->domainRouter->route($targetPrediction->intent, $targetNormalized, $state);

        if ($targetPrediction->intent === ChatbotIntent::ServiceSearch) {
            $guided = $this->guidedDiscovery->handle($targetIncoming, $state);

            if ($guided === null || ! ($guided['context']['should_stop_pipeline'] ?? false)) {
                $guided = $this->guidedDiscovery->startElectronicDiscovery($targetIncoming);
            }

            $this->context->updateState($incoming->sessionId, array_merge(
                $guided['context'],
                ['should_stop_pipeline' => true],
            ));

            $guidedPrediction = new IntentPredictionData(
                intent: ChatbotIntent::ServiceSearch,
                confidence: 1.0,
                source: 'rule',
                accepted: true,
            );
            $guidedRoute = new MunicipalityDomainRouteData(
                domain: $guided['context']['current_domain'] ?? 'electronic_services',
                intent: ChatbotIntent::ServiceSearch,
                handlerKey: 'service_search',
                confidence: 1.0,
                source: 'rule',
            );

            $this->storeMessages($conversation->id, $incoming, $guided['response'], $storedNormalized, $guidedPrediction, $startTime, $guidedRoute, null, null);

            return $this->withSuspensionNote($guided['response'], $suspensionNote);
        }

        $handler = $this->registry->resolve($targetPrediction->intent);
        $response = $handler->handle($targetIncoming, null);

        $contextUpdate = [
            'last_intent' => $targetPrediction->intent->value,
            'previous_intent' => $state->lastIntent,
            'state' => $response->nextConversationState ?? ConversationState::Normal->value,
            'current_domain' => $targetRoute->domain ?? $targetPrediction->intent->domain(),
            'needs_clarification' => false,
            'pending_field' => null,
            'pending_selected_option' => null,
            'clarification_options' => [],
        ];

        if ($targetPrediction->intent === ChatbotIntent::CreateComplaint) {
            $contextUpdate['workflow_type'] = 'complaint';
            $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? $response->workflow['draft_id'] ?? null;
        } elseif ($targetPrediction->intent === ChatbotIntent::ContactRequest) {
            $contextUpdate['workflow_type'] = 'contact_request';
            $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? $response->workflow['draft_id'] ?? null;
        } elseif ($targetPrediction->intent === ChatbotIntent::TrackWorkflow) {
            $contextUpdate['workflow_type'] = 'tracking';

            if (($response->workflow['current_step'] ?? null) === 'tracking_number' && $response->type === 'workflow_tracking') {
                $contextUpdate['state'] = ConversationState::WaitingForTrackingNumber->value;
                $contextUpdate['pending_field'] = 'tracking_number';
            }
        }

        $this->context->updateState($incoming->sessionId, $contextUpdate);

        $this->storeMessages($conversation->id, $incoming, $response, $storedNormalized, $targetPrediction, $startTime, $targetRoute, null, null);

        return $this->withSuspensionNote($response, $suspensionNote);
    }

    private function withSuspensionNote(ChatResponseData $response, ?string $suspensionNote): ChatResponseData
    {
        if ($suspensionNote === null || $suspensionNote === '') {
            return $response;
        }

        return new ChatResponseData(
            message: $suspensionNote."\n\n".$response->message,
            type: $response->type,
            items: $response->items,
            actions: $response->actions,
            needsClarification: $response->needsClarification,
            clarificationType: $response->clarificationType,
            nextConversationState: $response->nextConversationState,
            title: $response->title,
            workflow: $response->workflow,
            metadata: $response->metadata,
            feedbackEligible: $response->feedbackEligible,
            isFallbackResponse: $response->isFallbackResponse,
        );
    }

    private function findWaterAreaById(int $areaId)
    {
        $waterQuery = app(WaterScheduleQueryInterface::class);

        foreach ($waterQuery->getPublishedAreas() as $area) {
            if ((int) $area->id === $areaId) {
                return $area;
            }
        }

        return null;
    }

    private function isWorkflowActive(ConversationStateData $state): bool
    {
        return in_array($state->state, [
            ConversationState::WorkflowCollectingData,
            ConversationState::WorkflowConfirming,
            ConversationState::WorkflowInterrupting,
        ], true);
    }

    private function handleWorkflowInput(
        IncomingChatMessageData $incoming,
        string $normalizedMessage,
        ConversationStateData $state,
        ChatbotConversation $conversation,
        float $startTime,
    ): ChatResponseData {
        if ($state->resetPending) {
            return $this->handleResetConfirmation(
                $incoming,
                $normalizedMessage,
                $state,
                $conversation,
                $startTime,
            );
        }

        // If we are in an interrupt confirmation state, check confirmation flow commands first
        if ($state->state === ConversationState::WorkflowInterrupting) {
            if ($this->confirmationFlow->isContinue($normalizedMessage)
                || $this->confirmationFlow->isConfirm($normalizedMessage)
                || $this->confirmationFlow->isSwitch($normalizedMessage)
                || $this->confirmationFlow->isCancel($normalizedMessage)
                || $this->confirmationFlow->isGlobalCancel($normalizedMessage)
            ) {
                $prediction = $this->predictor->predict($normalizedMessage);
                $route = $this->domainRouter->route($prediction->intent, $normalizedMessage, $state);

                $result = $this->workflowEngine->processInput($incoming->sessionId, $normalizedMessage);
                $response = $this->responseBuilder->build($result);

                if ($result->cancelled && $result->switchIntent !== null) {
                    $this->context->reset($incoming->sessionId);

                    return $this->routeAfterSwitch(
                        $result,
                        $route,
                        $incoming,
                        $conversation,
                        $startTime,
                        $prediction,
                        $normalizedMessage,
                        $state,
                    );
                }

                if ($result->completed || $result->cancelled) {
                    $this->storeMessages(
                        $conversation->id,
                        $incoming,
                        $response,
                        $normalizedMessage,
                        $prediction,
                        $startTime,
                        $route,
                        null,
                        null,
                    );

                    $this->context->reset($incoming->sessionId);

                    return $response;
                }

                $this->storeMessages(
                    $conversation->id,
                    $incoming,
                    $response,
                    $normalizedMessage,
                    $prediction,
                    $startTime,
                    $route,
                    null,
                    null,
                );

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => $prediction->intent->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $result->nextConversationState
                        ?? ConversationState::WorkflowInterrupting->value,
                    'current_domain' => 'citizen_workflow',
                ]);

                return $response;
            }
        }

        $prediction = $this->predictor->predict($normalizedMessage);
        $route = $this->domainRouter->route($prediction->intent, $normalizedMessage, $state);

        // Domain switch during an active workflow: a strong, explicit intent
        // for another domain interrupts the collection/confirmation step
        // instead of being swallowed as an answer or rejected by the current
        // step's validator. The draft is suspended (not lost) and the engine
        // asks the user whether to continue or switch.
        $interruptionIntent = $this->detectWorkflowInterruption($prediction, $route, $state, $normalizedMessage);
        if ($interruptionIntent !== null) {
            $interruptionResult = $this->workflowEngine->requestInterruption(
                $incoming->sessionId,
                $interruptionIntent->value,
                $this->workflowSwitchLabel($interruptionIntent),
            );

            if ($interruptionResult !== null) {
                $interruptionResponse = $this->responseBuilder->build($interruptionResult);

                if ($interruptionResult->cancelled && $interruptionResult->switchIntent !== null) {
                    $this->context->reset($incoming->sessionId);

                    return $this->routeAfterSwitch(
                        $interruptionResult,
                        $route,
                        $incoming,
                        $conversation,
                        $startTime,
                        $prediction,
                        $normalizedMessage,
                        $state,
                    );
                }

                $this->storeMessages(
                    $conversation->id,
                    $incoming,
                    $interruptionResponse,
                    $normalizedMessage,
                    $prediction,
                    $startTime,
                    $route,
                    null,
                    null,
                );

                $this->context->updateState($incoming->sessionId, [
                    'last_intent' => $prediction->intent->value,
                    'previous_intent' => $state->lastIntent,
                    'state' => $interruptionResult->nextConversationState
                        ?? ConversationState::WorkflowInterrupting->value,
                    'current_domain' => 'citizen_workflow',
                ]);

                return $interruptionResponse;
            }
        }

        $result = $this->workflowEngine->processInput($incoming->sessionId, $normalizedMessage);
        $response = $this->responseBuilder->build($result);

        if ($result->cancelled && $result->switchIntent !== null) {
            $this->context->reset($incoming->sessionId);

            return $this->routeAfterSwitch(
                $result,
                $route,
                $incoming,
                $conversation,
                $startTime,
                $prediction,
                $normalizedMessage,
                $state,
            );
        }

        if ($result->completed || $result->cancelled) {
            $this->storeMessages(
                $conversation->id,
                $incoming,
                $response,
                $normalizedMessage,
                $prediction,
                $startTime,
                $route,
                null,
                null,
            );

            $this->context->reset($incoming->sessionId);

            return $response;
        }

        $this->storeMessages(
            $conversation->id,
            $incoming,
            $response,
            $normalizedMessage,
            $prediction,
            $startTime,
            $route,
            null,
            null,
        );

        $this->context->updateState($incoming->sessionId, [
            'last_intent' => $prediction->intent->value,
            'previous_intent' => $state->lastIntent,
            'state' => $result->nextConversationState
                ?? ConversationState::WorkflowCollectingData->value,
            'current_domain' => 'citizen_workflow',
        ]);

        return $response;
    }

    private function handleResetConfirmation(
        IncomingChatMessageData $incoming,
        string $normalizedMessage,
        ConversationStateData $state,
        ChatbotConversation $conversation,
        float $startTime,
    ): ChatResponseData {
        if ($this->confirmationFlow->isConfirm($normalizedMessage)) {
            $this->workflowEngine->cancel($incoming->sessionId);
            $this->context->reset($incoming->sessionId);

            $this->context->updateState($incoming->sessionId, $this->buildMainMenuContextUpdate());

            $response = new ChatResponseData(
                message: 'تم تصفية المحادثة. كيف أقدر أساعدك؟',
                type: 'text',
                actions: GreetingHandler::MAIN_MENU_ACTIONS,
                nextConversationState: ConversationState::Normal->value,
            );

            $prediction = $this->predictor->predict($normalizedMessage);
            $route = $this->domainRouter->route($prediction->intent, $normalizedMessage, $state);

            $this->storeMessages(
                $conversation->id,
                $incoming,
                $response,
                $normalizedMessage,
                $prediction,
                $startTime,
                $route,
                null,
                null,
            );

            return $response;
        }

        if ($this->confirmationFlow->isCancel($normalizedMessage)) {
            $this->context->updateState($incoming->sessionId, ['reset_pending' => false]);

            $response = $this->workflowEngine->processInput($incoming->sessionId, $normalizedMessage);

            return $this->responseBuilder->build($response);
        }

        $prediction = $this->predictor->predict($normalizedMessage);
        $route = $this->domainRouter->route($prediction->intent, $normalizedMessage, $state);

        $reminder = new ChatResponseData(
            message: 'للتأكيد التصفية اكتب "تأكيد"، أو "إلغاء" للعودة إلى الطلب.',
            type: 'text',
            nextConversationState: ConversationState::WorkflowInterrupting->value,
        );

        $this->storeMessages(
            $conversation->id,
            $incoming,
            $reminder,
            $normalizedMessage,
            $prediction,
            $startTime,
            $route,
            null,
            null,
        );

        return $reminder;
    }

    private function routeAfterSwitch(
        WorkflowStepResultData $result,
        MunicipalityDomainRouteData $route,
        IncomingChatMessageData $incoming,
        ChatbotConversation $conversation,
        float $startTime,
        IntentPredictionData $prediction,
        string $normalizedMessage,
        ConversationStateData $state,
    ): ChatResponseData {
        $switchIntentStr = $result->switchIntent ?? '';

        if ($switchIntentStr === 'main_menu') {
            $intent = ChatbotIntent::Greeting;
        } else {
            $intent = ChatbotIntent::tryFrom($switchIntentStr);
        }

        if ($intent === null) {
            return $this->responseBuilder->build($result);
        }

        $handler = $this->registry->resolve($intent);
        $switchResponse = $handler->handle($incoming, null);

        $newRoute = $this->domainRouter->route($intent, $normalizedMessage, $state);

        $this->context->updateState($incoming->sessionId, [
            'last_intent' => $intent->value,
            'previous_intent' => $state->lastIntent,
            'state' => $switchResponse->nextConversationState
                ?? ConversationState::Normal->value,
            'current_domain' => $intent->domain(),
        ]);

        if ($switchResponse->clarificationType === 'municipality_main_menu') {
            $this->context->updateState($incoming->sessionId, $this->buildMainMenuContextUpdate());
        }

        $this->storeMessages(
            $conversation->id,
            $incoming,
            $switchResponse,
            $normalizedMessage,
            $prediction,
            $startTime,
            $newRoute,
            null,
            null,
        );

        return $switchResponse;
    }

    private function storeMessages(
        int $conversationId,
        IncomingChatMessageData $incoming,
        ChatResponseData $response,
        string $normalizedMessage,
        $prediction,
        float $startTime,
        MunicipalityDomainRouteData $route,
        ?ResolvedServiceData $resolvedService,
        ?ServiceSearchResultCollection $searchResult,
    ): void {
        $processingTimeMs = (int) round((microtime(true) - $startTime) * 1000);
        $isFallback = in_array($response->type, ['clarification', 'unknown', 'empty_state'], true)
            || $response->isFallbackResponse;

        $detectedEntities = [];
        if ($resolvedService !== null) {
            $detectedEntities = [
                'service_id' => $resolvedService->id,
                'service_name' => $resolvedService->name,
            ];
        }

        $searchMetadata = null;
        if ($searchResult !== null) {
            $searchMetadata = [
                'search_source' => 'smart_search',
                'selected_service_id' => $searchResult->bestMatch?->serviceId,
                'best_score' => $searchResult->bestMatch?->score,
                'score_gap' => $searchResult->scoreGap,
                'matched_by' => $searchResult->bestMatch?->matchedBy,
                'candidate_count' => count($searchResult->matches),
                'required_clarification' => $searchResult->requiresClarification,
                'search_no_match' => $searchResult->noMatch,
            ];
        }

        $actionKeyMetadata = $this->parseActionKeyMetadata($normalizedMessage);

        $baseMetadata = [
            'normalized_message' => $normalizedMessage,
            'predicted_intent' => $prediction->intent->value,
            'prediction_source' => $prediction->source,
            'prediction_confidence' => $prediction->confidence,
            'prediction_accepted' => $prediction->accepted,
            'rejection_reason' => $prediction->rejectionReason,
            'model_version_id' => $prediction->modelVersionId,
            'matched_rule' => $prediction->matchedRule,
            'detected_entities' => $detectedEntities,
            'search_metadata' => $searchMetadata,
            'source_domain' => $route->domain,
            'source_entity_type' => $route->requiredEntityType,
            'handler_key' => $route->handlerKey,
            'is_fallback' => $isFallback,
            'processing_time_ms' => $processingTimeMs,
            'phase' => $route->isMunicipalityDomain() ? 'domain_routing' : 'intent_classification',
        ];

        if ($actionKeyMetadata !== null) {
            $baseMetadata['action_key'] = $actionKeyMetadata['key'];
            $baseMetadata['action_payload'] = $actionKeyMetadata['payload'];
        }

        $this->messageRepository->create([
            'conversation_id' => $conversationId,
            'role' => 'citizen',
            'content' => $incoming->displayLabel ?? $incoming->message,
            'metadata' => $baseMetadata,
        ]);

        $this->messageRepository->create([
            'conversation_id' => $conversationId,
            'role' => 'bot',
            'content' => $response->message,
            'metadata' => array_merge($baseMetadata, [
                'response_payload' => $response->toArray(),
            ]),
        ]);

        $afterState = $this->context->getState($incoming->sessionId);

        // Track consecutive fallback turns: a successful turn resets the
        // counter, a fallback turn increments it. The router uses this
        // counter to break the fallback loop with a safe main menu.
        $newFallbackCount = $isFallback ? $afterState->fallbackCount + 1 : 0;

        if ($newFallbackCount !== $afterState->fallbackCount) {
            $this->context->updateState($incoming->sessionId, ['fallback_count' => $newFallbackCount]);
            $afterState = $this->context->getState($incoming->sessionId);
        }

        ChatbotTrace::log([
            'event' => 'turn_end',
            'session_id' => $incoming->sessionId,
            'conversation_id' => $conversationId,
            'final_action' => $route->handlerKey,
            'path' => match ($route->source) {
                'action_key' => 'trusted-key',
                'clarification' => 'clarification',
                'rule' => 'rule',
                'intent' => 'classifier',
                default => 'fallback',
            },
            'response_type' => $response->type,
            'final_intent' => $prediction->intent->value,
            'metadata_after' => [
                'state' => $afterState->state->value,
                'clarification_type' => $afterState->pendingField,
                'clarification_options_count' => count($afterState->clarificationOptions),
                'selected_entity' => $afterState->currentServiceId !== null
                    ? ['entity_type' => 'service', 'id' => $afterState->currentServiceId, 'name' => $afterState->currentServiceName]
                    : null,
                'current_domain' => $afterState->currentDomain,
                'current_category_id' => $afterState->currentCategoryId,
            ],
        ]);

        if ($prediction->intent === ChatbotIntent::Unknown || in_array($response->type, ['unknown', 'empty_state'], true)) {
            Event::dispatch(new UnknownQuestionDetectedEvent(
                question: $incoming->message,
                normalizedQuestion: $normalizedMessage,
                conversationId: $conversationId,
                detectedIntent: $prediction->intent->value,
                predictionConfidence: $prediction->confidence,
                suggestedDomain: null,
            ));
        }
    }

    private function buildResolvedService(?int $serviceId, ?string $serviceName): ?ResolvedServiceData
    {
        if ($serviceId === null || $serviceName === null) {
            return null;
        }

        $serviceQuery = app(MunicipalityServiceQueryInterface::class);

        return $serviceQuery->getPublishedOverview($serviceId) ?? new ResolvedServiceData(
            id: $serviceId,
            name: $serviceName,
        );
    }

    private function buildClarificationOptions(array $items): array
    {
        $options = [];
        foreach ($items as $index => $item) {
            $label = $item['label'] ?? ($item['name'] ?? '');
            $options[] = [
                'id' => $item['id'] ?? null,
                'name' => $label,
                'label' => $label,
                'number' => $item['position'] ?? $index + 1,
                'position' => $item['position'] ?? $index + 1,
                'key' => $item['key'] ?? null,
                'type' => $item['type'] ?? null,
                'entity_type' => $item['entity_type'] ?? ($item['type'] ?? null),
                'entity_id' => $item['entity_id'] ?? ($item['id'] ?? null),
                'normalized_label' => $item['normalized_label'] ?? null,
            ];
        }

        return $options;
    }

    private function isExplicitDomainSwitch(
        ChatbotIntent $intent,
        MunicipalityDomainRouteData $route,
        ConversationStateData $state,
        IntentPredictionData $prediction,
    ): bool {
        $newDomain = $route->domain;
        $currentDomain = $state->currentDomain ?? ($state->currentServiceId !== null ? 'electronic_services' : ($state->workflowType !== null ? 'citizen_workflow' : 'general'));

        if ($newDomain === $currentDomain) {
            if ($state->workflowType !== null) {
                $workflowSwitchIntents = [
                    ChatbotIntent::TrackWorkflow,
                    ChatbotIntent::ContactRequest,
                    ChatbotIntent::CreateComplaint,
                ];

                if (in_array($intent, $workflowSwitchIntents, true) && $intent !== $this->intentForWorkflowType($state->workflowType)) {
                    return true;
                }
            }

            return false;
        }

        if ($intent === ChatbotIntent::Unknown) {
            return false;
        }

        if ($newDomain === 'general') {
            return false;
        }

        $isStrongPrediction = $prediction->source === 'rule' || ($prediction->source === 'ml' && $prediction->confidence >= 0.80);

        if (! $isStrongPrediction) {
            return false;
        }

        return true;
    }

    private function intentForWorkflowType(string $workflowType): ?ChatbotIntent
    {
        return match ($workflowType) {
            'complaint' => ChatbotIntent::CreateComplaint,
            'contact_request' => ChatbotIntent::ContactRequest,
            'tracking' => ChatbotIntent::TrackWorkflow,
            default => null,
        };
    }

    /**
     * Detects a strong, explicit domain switch while a workflow is actively
     * collecting or confirming answers. Weak signals (Unknown, general
     * intents, bare "اتصال" without request semantics) never interrupt a
     * free-text step — the message stays a workflow answer.
     */
    private function detectWorkflowInterruption(
        IntentPredictionData $prediction,
        MunicipalityDomainRouteData $route,
        ConversationStateData $state,
        string $normalizedMessage,
    ): ?ChatbotIntent {
        if (! in_array($state->state, [
            ConversationState::WorkflowCollectingData,
            ConversationState::WorkflowConfirming,
        ], true)) {
            return null;
        }

        $intent = $prediction->intent;

        if ($intent === ChatbotIntent::ContactRequest && ! $this->isExplicitContactRequestPhrase($normalizedMessage)) {
            return null;
        }

        if (! $this->isExplicitDomainSwitch($intent, $route, $state, $prediction)) {
            return null;
        }

        return $intent;
    }

    private function isExplicitContactRequestPhrase(string $normalizedMessage): bool
    {
        return str_contains($normalizedMessage, 'طلب')
            || str_contains($normalizedMessage, 'تواصل')
            || str_contains($normalizedMessage, 'اتصل ب');
    }

    private function workflowSwitchLabel(ChatbotIntent $intent): string
    {
        return match ($intent) {
            ChatbotIntent::ServiceSearch => 'الخدمات',
            ChatbotIntent::WaterSchedule => 'جدول المياه',
            ChatbotIntent::DepartmentsList => 'الأقسام',
            ChatbotIntent::JobsOpen, ChatbotIntent::LatestJobs => 'الوظائف',
            ChatbotIntent::LatestNews => 'الاخبار',
            ChatbotIntent::LatestAnnouncements => 'الاعلانات',
            ChatbotIntent::LatestCouncilDecisions => 'قرارات المجلس',
            ChatbotIntent::FacilitiesList => 'المرافق العامة',
            ChatbotIntent::EngineeringOfficesList => 'المكاتب الهندسية',
            ChatbotIntent::CouncilMembersList => 'أعضاء المجلس البلدي',
            ChatbotIntent::MunicipalityPhone => 'رقم الهاتف',
            ChatbotIntent::MunicipalityEmail => 'البريد الإلكتروني',
            ChatbotIntent::MunicipalityAddress => 'العنوان',
            ChatbotIntent::MunicipalityWorkingHours => 'ساعات العمل',
            ChatbotIntent::MunicipalityAbout => 'عن البلدية',
            ChatbotIntent::MunicipalityMayor => 'رئيس البلدية',
            ChatbotIntent::MunicipalityContact => 'تواصل مع البلدية',
            ChatbotIntent::CreateComplaint => 'تقديم شكوى',
            ChatbotIntent::ContactRequest => 'طلب اتصال',
            ChatbotIntent::TrackWorkflow => 'متابعة طلب',
            default => $intent->value,
        };
    }

    private function tryCompoundGreeting(
        string $normalizedMessage,
        IncomingChatMessageData $incoming,
        ChatbotConversation $conversation,
        float $startTime,
        ConversationStateData $state,
    ): ?ChatResponseData {
        // Pure thanks: always answered, never swallowed by service context.
        if ($this->isThanksMessage($normalizedMessage)) {
            return $this->handleSimpleIntent($incoming, $conversation, $startTime, $state, ChatbotIntent::Thanks, $normalizedMessage);
        }

        $greetingPatterns = [
            '/^(مرحبا|السلام|هلا|اهلين|هاي|هلو|نورت|صباح|مساء)/u',
        ];

        $hasGreeting = false;
        foreach ($greetingPatterns as $pattern) {
            if (preg_match($pattern, $normalizedMessage)) {
                $hasGreeting = true;
                break;
            }
        }

        if (! $hasGreeting) {
            return null;
        }

        $greetingPrefix = 'أهلًا وسهلًا. ';

        $actionablePart = $normalizedMessage;
        foreach ($greetingPatterns as $pattern) {
            $actionablePart = preg_replace($pattern, '', $actionablePart);
        }
        $actionablePart = trim($actionablePart);

        if ($actionablePart === '') {
            return $this->handleSimpleIntent($incoming, $conversation, $startTime, $state, ChatbotIntent::Greeting, $normalizedMessage);
        }

        $prediction = $this->predictor->predict($actionablePart);
        $intent = $prediction->intent;
        $subRoute = $this->domainRouter->route($intent, $actionablePart, $this->context->getState($incoming->sessionId));

        if ($subRoute->isMunicipalityDomain()) {
            $handler = $this->registry->resolve($intent);
            $response = $handler->handle($incoming, null);

            $combinedMessage = $greetingPrefix.$response->message;

            $this->storeMessages($conversation->id, $incoming, new ChatResponseData(
                message: $combinedMessage,
                type: $response->type,
                items: $response->items,
                actions: $response->actions,
                needsClarification: $response->needsClarification,
                clarificationType: $response->clarificationType,
                nextConversationState: $response->nextConversationState,
            ), $normalizedMessage, $prediction, $startTime, $subRoute, null, null);

            return new ChatResponseData(
                message: $combinedMessage,
                type: $response->type,
                items: $response->items,
                actions: $response->actions,
                needsClarification: $response->needsClarification,
                clarificationType: $response->clarificationType,
                nextConversationState: $response->nextConversationState,
            );
        }

        return null;
    }

    private function tryResolveMainMenuSelection(
        string $normalizedMessage,
        ConversationStateData $state,
        ChatbotConversation $conversation,
        float $startTime,
        IncomingChatMessageData $incoming,
    ): ?ChatResponseData {
        // Only attempt main menu resolution when there is an active
        // municipality_main_menu clarification pending.
        if ($state->pendingField !== 'municipality_main_menu' && $state->pendingField !== null) {
            return null;
        }

        if ($state->pendingField !== 'municipality_main_menu') {
            return null;
        }

        if (empty($state->clarificationOptions)) {
            return null;
        }

        $result = $this->clarificationResolver->resolveOptionSelection($normalizedMessage, $state);

        if ($result === null || ! isset($result['matched']) || $result['matched'] !== true) {
            return null;
        }

        $option = $result['option'];
        $actionKey = $option['key'] ?? $option['label'];

        ChatbotTrace::log([
            'event' => 'resolution',
            'session_id' => $incoming->sessionId,
            'path' => 'clarification',
            'method' => $result['match_type'] ?? 'option',
            'entity_type' => 'municipality_main_menu',
            'matched_label' => $option['label'] ?? null,
            'matched_id' => $option['entity_id'] ?? null,
            'matched_position' => $option['position'] ?? null,
        ]);

        $resolved = $this->actionRegistry->resolve($actionKey);

        if ($resolved === null) {
            return null;
        }

        $intent = $resolved['intent'];

        if ($intent === ChatbotIntent::ServiceSearch) {
            // Keep the guided-flow context intact: state, clarification
            // options and the waiting mode must survive this turn so the
            // next typed number/name resolves against the offered list.
            $guided = $this->guidedDiscovery->startElectronicDiscovery($incoming);

            $this->context->updateState($incoming->sessionId, array_merge(
                $guided['context'],
                [
                    'current_domain' => $resolved['domain'],
                    'last_intent' => ChatbotIntent::ServiceSearch->value,
                    'previous_intent' => $state->lastIntent,
                ],
            ));

            $guidedPrediction = new IntentPredictionData(
                intent: ChatbotIntent::ServiceSearch,
                confidence: 1.0,
                source: 'rule',
                accepted: true,
            );

            $guidedRoute = new MunicipalityDomainRouteData(
                domain: $resolved['domain'],
                intent: ChatbotIntent::ServiceSearch,
                handlerKey: $resolved['handler'],
                confidence: 1.0,
                source: 'clarification',
            );

            $this->storeMessages($conversation->id, $incoming, $guided['response'], $normalizedMessage, $guidedPrediction, $startTime, $guidedRoute, null, null);

            return $guided['response'];
        }

        $handler = $this->registry->resolve($intent);
        $response = $handler->handle($incoming, null);

        $contextUpdate = [
            'current_domain' => $resolved['domain'],
            'current_service_id' => null,
            'current_service_name' => null,
            'last_intent' => $intent->value,
            'previous_intent' => $state->lastIntent,
        ];

        if ($response->needsClarification && $response->nextConversationState === null) {
            $contextUpdate['state'] = ConversationState::WaitingForClarification->value;
            $contextUpdate['needs_clarification'] = true;
            $contextUpdate['pending_field'] = $response->clarificationType ?? 'general';
            $contextUpdate['pending_selected_option'] = null;
            $contextUpdate['clarification_options'] = $this->buildClarificationOptions($response->items);
        } else {
            $contextUpdate['state'] = ConversationState::Normal->value;
            $contextUpdate['needs_clarification'] = false;
            $contextUpdate['pending_field'] = null;
            $contextUpdate['pending_selected_option'] = null;
            $contextUpdate['clarification_options'] = [];
        }

        if ($intent === ChatbotIntent::CreateComplaint) {
            $contextUpdate['workflow_type'] = 'complaint';
            $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? $response->workflow['draft_id'] ?? null;
        } elseif ($intent === ChatbotIntent::ContactRequest) {
            $contextUpdate['workflow_type'] = 'contact_request';
            $contextUpdate['workflow_draft_id'] = $response->metadata['draft_id'] ?? $response->workflow['draft_id'] ?? null;
        } elseif ($intent === ChatbotIntent::TrackWorkflow) {
            $contextUpdate['workflow_type'] = 'tracking';
        }

        $this->context->updateState($incoming->sessionId, $contextUpdate);

        $route = new MunicipalityDomainRouteData(
            domain: $resolved['domain'],
            intent: $intent,
            handlerKey: $resolved['handler'],
            confidence: 1.0,
            source: 'clarification',
        );

        $prediction = new IntentPredictionData(
            intent: $intent,
            confidence: 1.0,
            source: 'rule',
            accepted: true,
        );

        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, null);

        return $response;
    }

    /**
     * Failed option resolution while a clarification is pending. The user
     * stays inside the pending flow — FAILED OPTION RESOLUTION IS NOT AN
     * UNKNOWN MESSAGE, so the classifier never gets a chance to hijack the
     * conversation. The fallback counter still increments so the loop guard
     * can eventually bounce the user back to the main menu.
     */
    private function invalidOptionResponse(
        string $normalizedMessage,
        ConversationStateData $state,
        ChatbotConversation $conversation,
        float $startTime,
        IncomingChatMessageData $incoming,
    ): ChatResponseData {
        $pendingType = $state->pendingField ?? 'general';
        $options = $state->clarificationOptions;

        $actions = array_values(array_map(
            fn (array $option): array => [
                'label' => $option['label'] ?? $option['name'] ?? '',
                'value' => (string) ($option['key'] ?? $option['number'] ?? $option['position'] ?? ''),
            ],
            $options,
        ));

        $response = new ChatResponseData(
            message: 'اختيار غير صالح. اختر رقم أو اسم من القائمة المعروضة.',
            type: 'text',
            items: [],
            actions: $actions,
            needsClarification: true,
            clarificationType: $pendingType,
            nextConversationState: $state->state->value,
            isFallbackResponse: true,
        );

        $this->context->updateState($incoming->sessionId, [
            'last_intent' => ChatbotIntent::Unknown->value,
            'previous_intent' => $state->lastIntent,
            'state' => $state->state->value,
            'current_domain' => $state->currentDomain ?? 'general',
            'needs_clarification' => true,
            'pending_field' => $pendingType,
            'pending_selected_option' => null,
            'clarification_options' => $options,
        ]);

        $invalidPrediction = new IntentPredictionData(
            intent: ChatbotIntent::Unknown,
            confidence: 0.0,
            source: 'rule',
            accepted: false,
        );

        $invalidRoute = new MunicipalityDomainRouteData(
            domain: $state->currentDomain ?? 'general',
            intent: ChatbotIntent::Unknown,
            handlerKey: 'unknown',
            confidence: 0.0,
            source: 'rule',
        );

        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $invalidPrediction, $startTime, $invalidRoute, null, null);

        return $response;
    }

    private function isThanksMessage(string $normalizedMessage): bool
    {
        $thanksTokens = [
            'شكرا', 'شكر', 'مشكور', 'مشكوره', 'تسلم', 'تسلمو', 'يسلمو',
            'يعطيك العافية', 'بارك الله فيك', 'متشكر', 'تم', 'تمام',
            'thanks', 'thank you',
        ];

        $remaining = $normalizedMessage;

        foreach ($thanksTokens as $token) {
            $remaining = str_replace($token, ' ', $remaining);
        }

        $remaining = preg_replace('/\s+/u', ' ', $remaining);

        return trim($remaining) === '';
    }

    private function handleSimpleIntent(
        IncomingChatMessageData $incoming,
        ChatbotConversation $conversation,
        float $startTime,
        ConversationStateData $state,
        ChatbotIntent $intent,
        string $normalizedMessage,
    ): ChatResponseData {
        $handler = $this->registry->resolve($intent);
        $response = $handler->handle($incoming, null);

        $prediction = new IntentPredictionData(
            intent: $intent,
            confidence: 1.0,
            source: 'rule',
            accepted: true,
        );

        $route = new MunicipalityDomainRouteData(
            domain: $intent->domain(),
            intent: $intent,
            handlerKey: $intent->value,
            confidence: 1.0,
            source: 'rule',
        );

        $contextUpdate = [
            'last_intent' => $intent->value,
            'previous_intent' => $state->lastIntent,
            'state' => $response->nextConversationState ?? ConversationState::Normal->value,
            'current_domain' => $intent->domain(),
            'needs_clarification' => $response->needsClarification || $response->clarificationType !== null,
            'pending_field' => $response->clarificationType ?? null,
            'pending_selected_option' => null,
            'clarification_options' => $response->items ? $this->buildClarificationOptions($response->items) : [],
        ];

        if ($response->clarificationType === 'municipality_main_menu') {
            $contextUpdate = array_merge($contextUpdate, $this->buildMainMenuContextUpdate());
        }

        $this->context->updateState($incoming->sessionId, $contextUpdate);

        $this->storeMessages($conversation->id, $incoming, $response, $normalizedMessage, $prediction, $startTime, $route, null, null);

        return $response;
    }

    private function buildMainMenuContextUpdate(): array
    {
        return [
            'state' => ConversationState::WaitingForSelection->value,
            'current_domain' => 'municipality_main_menu',
            'needs_clarification' => true,
            'pending_field' => 'municipality_main_menu',
            'pending_selected_option' => null,
            'clarification_options' => GreetingHandler::MAIN_MENU_CLARIFICATION_OPTIONS,
        ];
    }
}
