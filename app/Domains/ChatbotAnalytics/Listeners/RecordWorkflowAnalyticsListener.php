<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Listeners;

use App\Domains\ChatbotAnalytics\Contracts\WorkflowAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Events\WorkflowCancelledEvent;
use App\Domains\ChatbotAnalytics\Events\WorkflowCompletedEvent;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * Records workflow start/completion/cancellation analytics.
 */
final class RecordWorkflowAnalyticsListener implements ShouldQueue
{
    use InteractsWithQueue;

    public string $queue = 'analytics';

    public int $tries = 3;

    public function __construct(
        private readonly WorkflowAnalyticsRepositoryInterface $workflowRepository,
    ) {}

    public function handleCompleted(WorkflowCompletedEvent $event): void
    {
        $existing = $this->workflowRepository->findByDraftId($event->draftId);

        if ($existing !== null) {
            $this->workflowRepository->updateCompletion($existing->id, [
                'was_completed' => true,
                'completed_at' => Carbon::now(),
                'completion_percentage' => 100.0,
                'duration_ms' => $event->durationMs,
                'total_steps' => $event->totalSteps,
                'current_step' => $event->totalSteps,
            ]);

            return;
        }

        $this->workflowRepository->create([
            'conversation_id' => $event->conversationId,
            'workflow_type' => $event->workflowType,
            'workflow_draft_id' => $event->draftId,
            'was_completed' => true,
            'completed_at' => Carbon::now(),
            'started_at' => Carbon::now()->subMilliseconds($event->durationMs),
            'completion_percentage' => 100.0,
            'total_steps' => $event->totalSteps,
            'current_step' => $event->totalSteps,
            'duration_ms' => $event->durationMs,
        ]);
    }

    public function handleCancelled(WorkflowCancelledEvent $event): void
    {
        $completionPercentage = $event->totalSteps > 0
            ? round($event->currentStep / $event->totalSteps * 100, 2)
            : 0.0;

        if ($event->draftId !== null) {
            $existing = $this->workflowRepository->findByDraftId($event->draftId);
            if ($existing !== null) {
                $this->workflowRepository->updateCompletion($existing->id, [
                    'was_cancelled' => true,
                    'cancelled_at' => Carbon::now(),
                    'current_step' => $event->currentStep,
                    'completion_percentage' => $completionPercentage,
                ]);

                return;
            }
        }

        $this->workflowRepository->create([
            'conversation_id' => $event->conversationId,
            'workflow_type' => $event->workflowType,
            'workflow_draft_id' => $event->draftId,
            'was_cancelled' => true,
            'cancelled_at' => Carbon::now(),
            'current_step' => $event->currentStep,
            'total_steps' => $event->totalSteps,
            'completion_percentage' => $completionPercentage,
        ]);
    }
}
