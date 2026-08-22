<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\DTOs;

final readonly class WorkflowStepResultData
{
    public function __construct(
        public string $message,
        public string $type = 'workflow_question',
        public array $actions = [],
        public bool $completed = false,
        public bool $confirming = false,
        public bool $cancelled = false,
        public ?string $trackingNumber = null,
        public ?string $workflowType = null,
        public ?string $workflowId = null,
        public ?int $draftId = null,
        public ?string $currentStep = null,
        public ?int $totalSteps = null,
        public ?int $completedSteps = null,
        public ?int $stepNumber = null,
        public ?float $progressPercent = null,
        public ?string $currentStepLabel = null,
        public ?string $finalEntityType = null,
        public ?int $finalEntityId = null,
        public ?string $switchIntent = null,
        public ?string $switchLabel = null,
        public ?string $submissionDate = null,
        public ?string $statusLabel = null,
        public array $metadata = [],
        public ?string $nextConversationState = null,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'actions' => $this->actions,
            'completed' => $this->completed,
            'confirming' => $this->confirming,
            'cancelled' => $this->cancelled,
            'tracking_number' => $this->trackingNumber,
            'workflow_type' => $this->workflowType,
            'workflow_id' => $this->workflowId,
            'draft_id' => $this->draftId,
            'current_step' => $this->currentStep,
            'total_steps' => $this->totalSteps,
            'completed_steps' => $this->completedSteps,
            'step_number' => $this->stepNumber,
            'progress_percent' => $this->progressPercent,
            'current_step_label' => $this->currentStepLabel,
            'final_entity_type' => $this->finalEntityType,
            'final_entity_id' => $this->finalEntityId,
            'switch_intent' => $this->switchIntent,
            'switch_label' => $this->switchLabel,
            'submission_date' => $this->submissionDate,
            'status_label' => $this->statusLabel,
            'metadata' => $this->metadata,
            'next_conversation_state' => $this->nextConversationState,
        ];
    }
}
