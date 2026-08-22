<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Providers;

use App\Domains\ChatbotAnalytics\Contracts\DatasetVersionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\IntentAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\PerformanceLogRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\SearchAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Contracts\WorkflowAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Events\FeedbackSubmittedEvent;
use App\Domains\ChatbotAnalytics\Events\UnknownQuestionDetectedEvent;
use App\Domains\ChatbotAnalytics\Events\WorkflowCancelledEvent;
use App\Domains\ChatbotAnalytics\Events\WorkflowCompletedEvent;
use App\Domains\ChatbotAnalytics\Listeners\RecordFeedbackListener;
use App\Domains\ChatbotAnalytics\Listeners\RecordUnknownQuestionListener;
use App\Domains\ChatbotAnalytics\Listeners\RecordWorkflowAnalyticsListener;
use App\Domains\ChatbotAnalytics\Repositories\EloquentDatasetVersionRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentIntentAnalyticsRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentPerformanceLogRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentSearchAnalyticsRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentUnknownQuestionRepository;
use App\Domains\ChatbotAnalytics\Repositories\EloquentWorkflowAnalyticsRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

final class ChatbotAnalyticsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(IntentAnalyticsRepositoryInterface::class, EloquentIntentAnalyticsRepository::class);
        $this->app->bind(UnknownQuestionRepositoryInterface::class, EloquentUnknownQuestionRepository::class);
        $this->app->bind(SearchAnalyticsRepositoryInterface::class, EloquentSearchAnalyticsRepository::class);
        $this->app->bind(WorkflowAnalyticsRepositoryInterface::class, EloquentWorkflowAnalyticsRepository::class);
        $this->app->bind(PerformanceLogRepositoryInterface::class, EloquentPerformanceLogRepository::class);
        $this->app->bind(DatasetVersionRepositoryInterface::class, EloquentDatasetVersionRepository::class);
    }

    public function boot(): void
    {
        Event::listen(
            UnknownQuestionDetectedEvent::class,
            RecordUnknownQuestionListener::class,
        );

        Event::listen(
            WorkflowCompletedEvent::class,
            [RecordWorkflowAnalyticsListener::class, 'handleCompleted'],
        );

        Event::listen(
            WorkflowCancelledEvent::class,
            [RecordWorkflowAnalyticsListener::class, 'handleCancelled'],
        );

        Event::listen(
            FeedbackSubmittedEvent::class,
            RecordFeedbackListener::class,
        );
    }
}
