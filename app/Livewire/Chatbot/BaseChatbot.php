<?php

declare(strict_types=1);

namespace App\Livewire\Chatbot;

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotMessageRepositoryInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\Chatbot\Services\ResponseTextPresenter;
use App\Domains\ChatbotAnalytics\Events\FeedbackSubmittedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

trait BaseChatbot
{
    public string $message = '';

    public array $messages = [];

    public bool $loading = false;

    public ?string $validationError = null;

    public string $sessionId;

    public bool $chatEnabled = true;

    public string $disclaimer = '';

    protected bool $skipUserMessage = false;

    protected ?string $pendingUserLabel = null;

    protected $action;

    protected ConversationContextInterface $context;

    protected ChatbotMessageRepositoryInterface $messagesRepo;

    protected ChatbotConversationRepositoryInterface $conversationRepo;

    protected ResponseTextPresenter $presenter;

    public function boot(): void
    {
        $this->action = app(ProcessRuleBasedChatMessageAction::class);
        $this->context = app(ConversationContextInterface::class);
        $this->messagesRepo = app(ChatbotMessageRepositoryInterface::class);
        $this->conversationRepo = app(ChatbotConversationRepositoryInterface::class);
        $this->presenter = app(ResponseTextPresenter::class);
    }

    public function mount(): void
    {
        $this->chatEnabled = config('chatbot.rule_based_enabled', true);
        $this->disclaimer = config('chatbot.public_disclaimer', 'هذا المساعد يقدم معلومات عامة عن الخدمات البلدية ولا يعتبر وثيقة رسمية.');

        $sessionId = session()->getId();
        if (empty($sessionId)) {
            $sessionId = (string) Str::uuid();
        }
        $this->sessionId = $sessionId;

        $this->loadConversationHistory();
    }

    public function sendMessage(): void
    {
        $this->validationError = null;

        $maxLength = config('chatbot.max_message_length', 500);

        $this->validate([
            'message' => [
                'required',
                'string',
                'max:'.$maxLength,
            ],
        ]);

        if ($this->loading) {
            return;
        }

        $this->loading = true;

        try {
            $userMessage = $this->message;
            $displayContent = $this->skipUserMessage && $this->pendingUserLabel !== null
                ? $this->pendingUserLabel
                : $this->resolveDisplayContent($userMessage);

            if (! $this->skipUserMessage) {
                $citizenId = 'citizen-'.Str::random(12);

                $this->messages[] = [
                    'id' => $citizenId,
                    'role' => 'user',
                    'content' => $displayContent,
                    'time' => now()->format('H:i'),
                ];
            }

            $this->message = '';

            $incoming = new IncomingChatMessageData(
                message: $userMessage,
                sessionId: $this->sessionId,
                userId: auth()->id(),
                displayLabel: $this->skipUserMessage ? $this->pendingUserLabel : null,
            );

            $response = $this->action->execute($incoming);

            $botId = 'bot-'.Str::random(12);

            $this->messages[] = [
                'id' => $botId,
                'role' => 'bot',
                'content' => $response->message,
                'type' => $response->type,
                'items' => $response->items,
                'actions' => $response->actions,
                'needs_clarification' => $response->needsClarification,
                'clarification_type' => $response->clarificationType,
                'metadata' => $response->metadata,
                'workflow' => $response->workflow,
                'feedback_eligible' => $response->feedbackEligible,
                'next_conversation_state' => $response->nextConversationState,
                'time' => now()->format('H:i'),
            ];

            $this->dispatch('chatbot-message-added');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $conversationId = null;
            try {
                $conversation = $this->conversationRepo->findActiveBySession($this->sessionId);
                if ($conversation !== null) {
                    $conversationId = $conversation->id;
                }
            } catch (\Throwable) {
                // ignore lookup failure during error handling
            }

            Log::error('Public chatbot message failed', [
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'conversation_id' => $conversationId,
                'session_id_hash' => hash('sha256', $this->sessionId),
            ]);

            $this->validationError = 'عذرًا، حدث خطأ أثناء المعالجة. الرجاء المحاولة مرة أخرى.';
            if (! empty($this->messages) && ($this->messages[array_key_last($this->messages)]['role'] ?? '') === 'user') {
                $this->message = $this->messages[array_key_last($this->messages)]['content'] ?? $this->message;
                array_pop($this->messages);
            }
        } finally {
            $this->loading = false;
        }
    }

    private function resolveDisplayContent(string $rawMessage): string
    {
        if (! str_contains($rawMessage, ':')) {
            return $rawMessage;
        }

        $parts = explode(':', $rawMessage, 2);
        $prefix = $parts[0];
        $rest = $parts[1] ?? '';

        $displayMap = [
            'main-menu' => static fn (string $key): string => $this->displayLabelForMainMenu($key),
            'service-action' => static fn (string $rest): string => $this->displayLabelForServiceAction($rest),
            'service-category' => static fn (string $id): string => 'تصنيف خدمة',
            'service' => static fn (string $id): string => 'خدمة',
            'water-area' => static fn (string $id): string => 'منطقة مياه',
            'workflow' => static fn (string $cmd): string => match ($cmd) {
                'confirm', 'continue' => 'تأكيد',
                'switch' => 'إلغاء والانتقال',
                'cancel' => 'إلغاء',
                default => $cmd,
            },
        ];

        if (! isset($displayMap[$prefix])) {
            return $rawMessage;
        }

        return $displayMap[$prefix]($rest);
    }

    private function displayLabelForMainMenu(string $key): string
    {
        $labels = [
            'electronic-services' => 'الخدمات الإلكترونية',
            'complaint' => 'تقديم شكوى',
            'contact-request' => 'طلب اتصال',
            'tracking' => 'متابعة طلب',
            'water' => 'جدول توزيع المياه',
            'facilities' => 'المرافق العامة',
            'jobs' => 'الوظائف',
            'council-members' => 'أعضاء المجلس البلدي',
            'council-decisions' => 'قرارات المجلس',
            'municipality-contact' => 'تواصل مع البلدية',
        ];

        return $labels[$key] ?? $key;
    }

    private function displayLabelForServiceAction(string $rest): string
    {
        $parts = explode(':', $rest, 2);
        $actionKey = $parts[0];
        $serviceId = $parts[1] ?? '';

        $labels = [
            'requirements' => 'المتطلبات',
            'fees' => 'الرسوم',
            'steps' => 'خطوات التقديم',
            'duration' => 'مدة الخدمة',
            'location' => 'مكان التقديم',
            'application-link' => 'رابط التقديم',
        ];

        return $labels[$actionKey] ?? ($serviceId ? "خدمة {$serviceId}" : $actionKey);
    }

    public function quickAction(string $value, ?string $label = null): void
    {
        $this->message = $value;
        $this->skipUserMessage = true;
        $this->pendingUserLabel = $label;

        $this->sendMessage();

        $this->skipUserMessage = false;
        $this->pendingUserLabel = null;
    }

    public function selectClarification(int $number): void
    {
        $this->message = (string) $number;
        $this->sendMessage();
    }

    public function selectClarificationOption(int $optionId): void
    {
        $this->message = (string) $optionId;
        $this->sendMessage();
    }

    public function resetContext(): void
    {
        if ($this->isWorkflowActive()) {
            $this->context->updateState($this->sessionId, ['reset_pending' => true]);

            $this->messages[] = [
                'id' => 'system-'.Str::random(8),
                'role' => 'bot',
                'content' => "يوجد طلب قيد الإكمال حالياً. هل أنت متأكد من تصفية المحادثة؟ سيتم إلغاء الطلب غير المكتمل.\n\nاكتب \"تأكيد\" لتصفية المحادثة أو \"إلغاء\" للبقاء.",
                'type' => 'text',
                'actions' => [
                    ['label' => 'تصفية وتأكيد', 'value' => 'تأكيد'],
                    ['label' => 'البدء من جديد', 'value' => 'إلغاء'],
                ],
            ];

            return;
        }

        $this->context->reset($this->sessionId);
        $this->messages = [];

        $this->messages[] = [
            'id' => 'system-'.Str::random(8),
            'role' => 'bot',
            'content' => 'تم تصفية المحادثة. كيف أقدر أساعدك؟',
            'type' => 'text',
        ];
    }

    public function submitFeedback(int $rating, ?string $comment = null, ?int $messageId = null, ?int $conversationId = null): void
    {
        if ($rating < 1 || $rating > 5) {
            return;
        }

        $cid = $conversationId ?? ($this->getConversation()?->id ?? 0);
        if ($cid <= 0) {
            return;
        }

        $conversation = $this->conversationRepo->find($cid);
        if ($conversation === null) {
            return;
        }

        FeedbackSubmittedEvent::dispatch($cid, $rating, $comment, $messageId);

        $this->messages[] = [
            'id' => 'system-'.Str::random(8),
            'role' => 'bot',
            'content' => 'شكراً لك! تم تسجيل ملاحظاتك.',
            'type' => 'text',
        ];
    }

    public function loadConversationHistory(): void
    {
        $conversation = $this->conversationRepo->findActiveBySession($this->sessionId);
        if ($conversation === null) {
            $this->addWelcomeMessage();

            return;
        }

        $history = $this->messagesRepo->getByConversation($conversation->id);
        $normalized = [];
        $seen = [];

        foreach ($history as $msg) {
            if ($msg->role === 'citizen') {
                $key = "citizen-{$msg->content}-{$msg->created_at}";
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;

                $normalized[] = [
                    'id' => (string) $msg->id,
                    'role' => 'user',
                    'content' => $this->presenter->normalizeDisplayText($msg->content),
                    'time' => $msg->created_at?->format('H:i') ?? '',
                ];
            } else {
                $metadata = $msg->metadata ?? [];
                $payload = $metadata['response_payload'] ?? [];

                $key = "bot-{$msg->content}-{$msg->created_at}";
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;

                $normalized[] = [
                    'id' => (string) $msg->id,
                    'role' => 'bot',
                    'content' => $msg->content,
                    'type' => $payload['type'] ?? 'text',
                    'items' => $payload['items'] ?? [],
                    'actions' => $payload['actions'] ?? [],
                    'needs_clarification' => $payload['needs_clarification'] ?? false,
                    'clarification_type' => $payload['clarification_type'] ?? null,
                    'metadata' => $payload['metadata'] ?? [],
                    'workflow' => $payload['workflow'] ?? null,
                    'feedback_eligible' => $payload['feedback_eligible'] ?? false,
                    'next_conversation_state' => $payload['next_conversation_state'] ?? null,
                    'time' => $msg->created_at?->format('H:i') ?? '',
                ];
            }
        }

        $this->messages = $normalized;

        if (empty($this->messages)) {
            $this->addWelcomeMessage();
        }
    }

    public function getConversation(): ?ChatbotConversation
    {
        return $this->conversationRepo->findActiveBySession($this->sessionId);
    }

    public function isWorkflowActive(): bool
    {
        foreach (array_reverse($this->messages) as $msg) {
            if (($msg['role'] ?? '') !== 'bot') {
                continue;
            }

            $workflow = $msg['workflow'] ?? null;
            $metadata = $msg['metadata'] ?? [];
            $nextState = $msg['next_conversation_state'] ?? $metadata['next_conversation_state'] ?? null;

            if (($workflow !== null || ($metadata['workflow_type'] ?? null) !== null)
                && $nextState !== null
                && in_array($nextState, [
                    ConversationState::WorkflowCollectingData->value,
                    ConversationState::WorkflowConfirming->value,
                    ConversationState::WorkflowInterrupting->value,
                ], true)) {
                return true;
            }
        }

        return false;
    }

    private function addWelcomeMessage(): void
    {
        $welcomeContent = $this->presenter->normalizeDisplayText("مرحباً بك في المساعد الذكي لبلدية إذنا\n\nيمكنني مساعدتك في الوصول إلى الخدمات والمعلومات البلدية، وتقديم الشكاوى والطلبات ومتابعتها.\n\nيمكنك كتابة سؤالك مباشرة أو اختيار أحد الخيارات السريعة أدناه.");

        $this->messages[] = [
            'id' => 'welcome-'.Str::random(8),
            'role' => 'bot',
            'content' => $welcomeContent,
            'type' => 'text',
            'actions' => [
                ['label' => 'الخدمات الإلكترونية', 'value' => 'الخدمات الإلكترونية'],
                ['label' => 'تقديم شكوى', 'value' => 'تقديم شكوى'],
                ['label' => 'طلب اتصال', 'value' => 'طلب اتصال'],
                ['label' => 'متابعة طلب', 'value' => 'تتبع طلب'],
                ['label' => 'جدول توزيع المياه', 'value' => 'جدول توزيع المياه'],
                ['label' => 'المرافق العامة', 'value' => 'المرافق العامة'],
                ['label' => 'الوظائف', 'value' => 'الوظائف'],
                ['label' => 'أعضاء المجلس البلدي', 'value' => 'أعضاء المجلس البلدي'],
                ['label' => 'قرارات المجلس', 'value' => 'قرارات المجلس'],
                ['label' => 'تواصل مع البلدية', 'value' => 'تواصل مع البلدية'],
            ],
        ];
    }
}
