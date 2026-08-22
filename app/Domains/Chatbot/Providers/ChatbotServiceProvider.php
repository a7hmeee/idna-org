<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Providers;

use App\Domains\Announcements\Services\AnnouncementQueryAdapter;
use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\Contracts\AnnouncementQueryInterface;
use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotFeedbackRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotMessageRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotModelVersionRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatIntentRepositoryInterface;
use App\Domains\Chatbot\Contracts\ChatTrainingExampleRepositoryInterface;
use App\Domains\Chatbot\Contracts\ClarificationResolverInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\Contracts\CouncilDecisionQueryInterface;
use App\Domains\Chatbot\Contracts\CouncilMemberQueryInterface;
use App\Domains\Chatbot\Contracts\DepartmentQueryInterface;
use App\Domains\Chatbot\Contracts\DirectServiceResolverInterface;
use App\Domains\Chatbot\Contracts\EngineeringOfficeQueryInterface;
use App\Domains\Chatbot\Contracts\EntityResolverInterface;
use App\Domains\Chatbot\Contracts\FacilityQueryInterface;
use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Contracts\IntentClassifierInterface;
use App\Domains\Chatbot\Contracts\IntentModelTrainerInterface;
use App\Domains\Chatbot\Contracts\JobsQueryInterface;
use App\Domains\Chatbot\Contracts\MunicipalityDomainRouterInterface;
use App\Domains\Chatbot\Contracts\MunicipalityInfoQueryInterface;
use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\Contracts\NewsQueryInterface;
use App\Domains\Chatbot\Contracts\RuleIntentDetectorInterface;
use App\Domains\Chatbot\Contracts\SmartServiceSearchInterface;
use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\Handlers\AnnouncementDetailsHandler;
use App\Domains\Chatbot\Handlers\AnnouncementSearchHandler;
use App\Domains\Chatbot\Handlers\CancelWorkflowHandler;
use App\Domains\Chatbot\Handlers\ContactRequestHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionByDateHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionDetailsHandler;
use App\Domains\Chatbot\Handlers\CouncilDecisionSearchHandler;
use App\Domains\Chatbot\Handlers\CouncilMemberDetailsHandler;
use App\Domains\Chatbot\Handlers\CouncilMemberSearchHandler;
use App\Domains\Chatbot\Handlers\CouncilMembersListHandler;
use App\Domains\Chatbot\Handlers\CreateComplaintHandler;
use App\Domains\Chatbot\Handlers\DepartmentContactHandler;
use App\Domains\Chatbot\Handlers\DepartmentDetailsHandler;
use App\Domains\Chatbot\Handlers\DepartmentSearchHandler;
use App\Domains\Chatbot\Handlers\DepartmentsListHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeContactHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeDetailsHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficeSearchHandler;
use App\Domains\Chatbot\Handlers\EngineeringOfficesListHandler;
use App\Domains\Chatbot\Handlers\FacilitiesListHandler;
use App\Domains\Chatbot\Handlers\FacilityDetailsHandler;
use App\Domains\Chatbot\Handlers\FacilityLocationHandler;
use App\Domains\Chatbot\Handlers\FacilitySearchHandler;
use App\Domains\Chatbot\Handlers\FacilityWorkingHoursHandler;
use App\Domains\Chatbot\Handlers\GreetingHandler;
use App\Domains\Chatbot\Handlers\JobDeadlineHandler;
use App\Domains\Chatbot\Handlers\JobDetailsHandler;
use App\Domains\Chatbot\Handlers\JobSearchHandler;
use App\Domains\Chatbot\Handlers\LatestAnnouncementsHandler;
use App\Domains\Chatbot\Handlers\LatestCouncilDecisionsHandler;
use App\Domains\Chatbot\Handlers\LatestJobsHandler;
use App\Domains\Chatbot\Handlers\LatestNewsHandler;
use App\Domains\Chatbot\Handlers\MunicipalityAboutHandler;
use App\Domains\Chatbot\Handlers\MunicipalityAddressHandler;
use App\Domains\Chatbot\Handlers\MunicipalityContactHandler;
use App\Domains\Chatbot\Handlers\MunicipalityEmailHandler;
use App\Domains\Chatbot\Handlers\MunicipalityMayorHandler;
use App\Domains\Chatbot\Handlers\MunicipalityPhoneHandler;
use App\Domains\Chatbot\Handlers\MunicipalityWorkingHoursHandler;
use App\Domains\Chatbot\Handlers\NewsDetailsHandler;
use App\Domains\Chatbot\Handlers\NewsSearchHandler;
use App\Domains\Chatbot\Handlers\OpenJobsHandler;
use App\Domains\Chatbot\Handlers\ResumeWorkflowHandler;
use App\Domains\Chatbot\Handlers\ServiceApplicationStepsHandler;
use App\Domains\Chatbot\Handlers\ServiceDurationHandler;
use App\Domains\Chatbot\Handlers\ServiceFeesHandler;
use App\Domains\Chatbot\Handlers\ServiceLocationHandler;
use App\Domains\Chatbot\Handlers\ServiceOnlineLinkHandler;
use App\Domains\Chatbot\Handlers\ServiceOverviewHandler;
use App\Domains\Chatbot\Handlers\ServiceRequirementsHandler;
use App\Domains\Chatbot\Handlers\ServiceSearchHandler;
use App\Domains\Chatbot\Handlers\ThanksHandler;
use App\Domains\Chatbot\Handlers\TrackWorkflowHandler;
use App\Domains\Chatbot\Handlers\UnknownHandler;
use App\Domains\Chatbot\Handlers\WaterAreaSearchHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleNextHandler;
use App\Domains\Chatbot\Handlers\WaterScheduleTodayHandler;
use App\Domains\Chatbot\Models\ChatbotConversation;
use App\Domains\Chatbot\Policies\ChatbotPolicy;
use App\Domains\Chatbot\Repositories\EloquentChatbotConversationRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotFeedbackRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotMessageRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotModelVersionRepository;
use App\Domains\Chatbot\Repositories\EloquentChatbotServiceAliasRepository;
use App\Domains\Chatbot\Repositories\EloquentChatIntentRepository;
use App\Domains\Chatbot\Repositories\EloquentChatTrainingExampleRepository;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ArabicTypoMatcher;
use App\Domains\Chatbot\Services\ChatbotActionRegistry;
use App\Domains\Chatbot\Services\ChatResponseHandlerRegistry;
use App\Domains\Chatbot\Services\ClarificationResolver;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\Chatbot\Services\DirectServiceResolver;
use App\Domains\Chatbot\Services\EntityResolver;
use App\Domains\Chatbot\Services\GuidedServiceDiscoveryService;
use App\Domains\Chatbot\Services\HybridIntentPredictor;
use App\Domains\Chatbot\Services\MunicipalityDomainRouter;
use App\Domains\Chatbot\Services\MunicipalityServiceQueryAdapter;
use App\Domains\Chatbot\Services\MunicipalityTokenizer;
use App\Domains\Chatbot\Services\PhpMlIntentClassifier;
use App\Domains\Chatbot\Services\PhpMlIntentModelTrainer;
use App\Domains\Chatbot\Services\ResponseTextPresenter;
use App\Domains\Chatbot\Services\RuleIntentDetector;
use App\Domains\Chatbot\Services\ServiceSearchScorer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;
use App\Domains\Chatbot\Services\SmartServiceSearch;
use App\Domains\Department\Services\DepartmentQueryAdapter;
use App\Domains\EngineeringOffices\Services\EngineeringOfficeQueryAdapter;
use App\Domains\Jobs\Services\JobsQueryAdapter;
use App\Domains\Municipality\Services\CouncilDecisionQueryAdapter;
use App\Domains\Municipality\Services\CouncilMemberQueryAdapter;
use App\Domains\Municipality\Services\MunicipalityInfoQueryAdapter;
use App\Domains\News\Services\NewsQueryAdapter;
use App\Domains\PublicFacilities\Services\FacilityQueryAdapter;
use App\Domains\WaterSchedule\Services\WaterScheduleQueryAdapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ChatbotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Phase 1 - Repositories
        $this->app->bind(
            ChatbotConversationRepositoryInterface::class,
            EloquentChatbotConversationRepository::class,
        );

        $this->app->bind(
            ChatbotMessageRepositoryInterface::class,
            EloquentChatbotMessageRepository::class,
        );

        $this->app->bind(
            ChatbotFeedbackRepositoryInterface::class,
            EloquentChatbotFeedbackRepository::class,
        );

        $this->app->bind(
            ChatbotModelVersionRepositoryInterface::class,
            EloquentChatbotModelVersionRepository::class,
        );

        $this->app->bind(
            ChatbotServiceAliasRepositoryInterface::class,
            EloquentChatbotServiceAliasRepository::class,
        );

        // Phase 2 - Core Services
        $this->app->singleton(ArabicTextNormalizer::class);
        $this->app->singleton(ResponseTextPresenter::class);

        $this->app->bind(
            RuleIntentDetectorInterface::class,
            RuleIntentDetector::class,
        );

        $this->app->bind(
            MunicipalityServiceQueryInterface::class,
            MunicipalityServiceQueryAdapter::class,
        );

        $this->app->bind(
            DirectServiceResolverInterface::class,
            DirectServiceResolver::class,
        );

        // Phase 2 - Handlers
        $this->app->tag([
            GreetingHandler::class,
            ThanksHandler::class,
            ServiceSearchHandler::class,
            ServiceOverviewHandler::class,
            ServiceApplicationStepsHandler::class,
            ServiceRequirementsHandler::class,
            ServiceFeesHandler::class,
            ServiceDurationHandler::class,
            ServiceLocationHandler::class,
            ServiceOnlineLinkHandler::class,
            UnknownHandler::class,
            MunicipalityMayorHandler::class,
        ], 'chatbot.handlers');

        // Phase 2 - Registry
        $this->app->singleton(ChatResponseHandlerRegistry::class);

        // Phase 2 - Action
        $this->app->singleton(ProcessRuleBasedChatMessageAction::class);

        // Phase 2 - Action Registry
        $this->app->singleton(ChatbotActionRegistry::class);

        // Phase 2 — Guided Service Discovery
        $this->app->singleton(GuidedServiceDiscoveryService::class);

        // Phase 3 - Repositories
        $this->app->bind(
            ChatIntentRepositoryInterface::class,
            EloquentChatIntentRepository::class,
        );

        $this->app->bind(
            ChatTrainingExampleRepositoryInterface::class,
            EloquentChatTrainingExampleRepository::class,
        );

        // Phase 3 - ML Services
        $this->app->singleton(MunicipalityTokenizer::class);

        $this->app->bind(
            IntentModelTrainerInterface::class,
            PhpMlIntentModelTrainer::class,
        );

        $this->app->bind(
            IntentClassifierInterface::class,
            PhpMlIntentClassifier::class,
        );

        $this->app->singleton(HybridIntentPredictorInterface::class, function ($app): HybridIntentPredictor {
            $config = config('chatbot');

            return new HybridIntentPredictor(
                ruleDetector: $app->make(RuleIntentDetectorInterface::class),
                classifier: $app->make(IntentClassifierInterface::class),
                normalizer: $app->make(ArabicTextNormalizer::class),
                defaultThreshold: (float) ($config['ml_default_confidence_threshold'] ?? 0.70),
                mlEnabled: (bool) ($config['ml_enabled'] ?? false),
                fallbackToRules: true,
            );
        });

        // Phase 4 — Conversation Context, Entity Resolver, Clarification
        $this->app->singleton(ConversationContextInterface::class, function ($app): ConversationContextInterface {
            $ttl = (int) (config('chatbot.context.ttl') ?? 1200);

            return new ConversationContextService(
                conversationRepository: $app->make(ChatbotConversationRepositoryInterface::class),
                contextTtl: $ttl,
            );
        });

        $this->app->bind(
            EntityResolverInterface::class,
            EntityResolver::class,
        );

        $this->app->bind(
            ClarificationResolverInterface::class,
            ClarificationResolver::class,
        );

        // Phase 5 — Smart Service Search
        $this->app->singleton(ArabicTypoMatcher::class);

        $this->app->singleton(ServiceSearchTokenizer::class, function ($app): ServiceSearchTokenizer {
            $config = config('chatbot.service_search', []);

            return new ServiceSearchTokenizer(
                normalizer: $app->make(ArabicTextNormalizer::class),
                stopWords: $config['stop_words'] ?? [],
                minimumTokenLength: $config['minimum_token_length'] ?? 2,
            );
        });

        $this->app->singleton(ServiceSearchScorer::class);

        $this->app->singleton(SmartServiceSearchInterface::class, function ($app): SmartServiceSearchInterface {
            $config = config('chatbot.service_search', []);

            return new SmartServiceSearch(
                serviceQuery: $app->make(MunicipalityServiceQueryInterface::class),
                tokenizer: $app->make(ServiceSearchTokenizer::class),
                scorer: $app->make(ServiceSearchScorer::class),
                normalizer: $app->make(ArabicTextNormalizer::class),
                autoSelectThreshold: (float) ($config['auto_select_threshold'] ?? 0.88),
                clarificationThreshold: (float) ($config['clarification_threshold'] ?? 0.55),
                minimumScoreGap: (float) ($config['minimum_score_gap'] ?? 0.15),
                defaultLimit: (int) ($config['result_limit'] ?? 5),
            );
        });

        // Phase 6 — Municipality Domain Router
        $this->app->bind(MunicipalityDomainRouterInterface::class, MunicipalityDomainRouter::class);

        // Phase 6 — Query Contracts → Domain Adapters
        $this->app->bind(
            MunicipalityInfoQueryInterface::class,
            MunicipalityInfoQueryAdapter::class,
        );

        $this->app->bind(
            DepartmentQueryInterface::class,
            DepartmentQueryAdapter::class,
        );

        $this->app->bind(
            WaterScheduleQueryInterface::class,
            WaterScheduleQueryAdapter::class,
        );

        $this->app->bind(
            JobsQueryInterface::class,
            JobsQueryAdapter::class,
        );

        $this->app->bind(
            NewsQueryInterface::class,
            NewsQueryAdapter::class,
        );

        $this->app->bind(
            AnnouncementQueryInterface::class,
            AnnouncementQueryAdapter::class,
        );

        $this->app->bind(
            CouncilDecisionQueryInterface::class,
            CouncilDecisionQueryAdapter::class,
        );

        $this->app->bind(
            FacilityQueryInterface::class,
            FacilityQueryAdapter::class,
        );

        $this->app->bind(
            EngineeringOfficeQueryInterface::class,
            EngineeringOfficeQueryAdapter::class,
        );

        $this->app->bind(
            CouncilMemberQueryInterface::class,
            CouncilMemberQueryAdapter::class,
        );

        // Phase 6 — Handlers tagged
        $this->app->tag([
            // Municipality Information
            MunicipalityContactHandler::class,
            MunicipalityPhoneHandler::class,
            MunicipalityEmailHandler::class,
            MunicipalityAddressHandler::class,
            MunicipalityWorkingHoursHandler::class,
            MunicipalityAboutHandler::class,
            // Departments
            DepartmentsListHandler::class,
            DepartmentSearchHandler::class,
            DepartmentDetailsHandler::class,
            DepartmentContactHandler::class,
            // Water Schedule
            WaterScheduleHandler::class,
            WaterScheduleTodayHandler::class,
            WaterScheduleNextHandler::class,
            WaterAreaSearchHandler::class,
            // Jobs
            OpenJobsHandler::class,
            LatestJobsHandler::class,
            JobSearchHandler::class,
            JobDetailsHandler::class,
            JobDeadlineHandler::class,
            // News
            LatestNewsHandler::class,
            NewsSearchHandler::class,
            NewsDetailsHandler::class,
            // Announcements
            LatestAnnouncementsHandler::class,
            AnnouncementSearchHandler::class,
            AnnouncementDetailsHandler::class,
            // Council Decisions
            LatestCouncilDecisionsHandler::class,
            CouncilDecisionSearchHandler::class,
            CouncilDecisionDetailsHandler::class,
            CouncilDecisionByDateHandler::class,
            // Facilities
            FacilitiesListHandler::class,
            FacilitySearchHandler::class,
            FacilityDetailsHandler::class,
            FacilityLocationHandler::class,
            FacilityWorkingHoursHandler::class,
            // Engineering Offices
            EngineeringOfficesListHandler::class,
            EngineeringOfficeSearchHandler::class,
            EngineeringOfficeDetailsHandler::class,
            EngineeringOfficeContactHandler::class,
            // Council Members
            CouncilMembersListHandler::class,
            CouncilMemberSearchHandler::class,
            CouncilMemberDetailsHandler::class,
            // Phase 7 — Citizen Workflows
            CreateComplaintHandler::class,
            ContactRequestHandler::class,
            TrackWorkflowHandler::class,
            ResumeWorkflowHandler::class,
            CancelWorkflowHandler::class,
        ], 'chatbot.handlers');

        // Rebind action with Phase 5 + Phase 6 dependencies
        $this->app->singleton(ProcessRuleBasedChatMessageAction::class);
    }

    public function boot(): void
    {
        Gate::policy(ChatbotConversation::class, ChatbotPolicy::class);
    }
}
