<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Services;

use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;

final readonly class WorkflowResponseBuilder
{
    public function build(WorkflowStepResultData $result): ChatResponseData
    {
        $metadata = $this->buildMetadata($result);

        $feedbackEligible = in_array(
            $result->type,
            ['workflow_success', 'workflow_completed'],
            true,
        );

        return new ChatResponseData(
            message: $result->message,
            type: $result->type,
            actions: $this->dedupeActions($result->actions),
            workflow: $this->buildWorkflowBlock($result),
            metadata: $metadata,
            nextConversationState: $result->nextConversationState ?? $this->deriveNextState($result),
            feedbackEligible: $feedbackEligible,
        );
    }

    private function deriveNextState(WorkflowStepResultData $result): ?string
    {
        if ($result->completed || $result->cancelled) {
            return ConversationState::Normal->value;
        }

        if ($result->confirming) {
            return ConversationState::WorkflowConfirming->value;
        }

        if ($result->type === 'workflow_question') {
            return ConversationState::WorkflowCollectingData->value;
        }

        return null;
    }

    public function buildWorkflowBlock(WorkflowStepResultData $result): array
    {
        return [
            'type' => $result->workflowType,
            'workflow_id' => $result->workflowId,
            'draft_id' => $result->draftId,
            'current_step' => $result->currentStep,
            'total_steps' => $result->totalSteps,
            'completed_steps' => $result->completedSteps,
            'step_number' => $result->stepNumber,
            'progress_percent' => $result->progressPercent,
            'current_step_label' => $result->currentStepLabel,
            'tracking_number' => $result->trackingNumber,
            'submission_date' => $result->submissionDate,
            'status_label' => $result->statusLabel,
            'switch_intent' => $result->switchIntent,
            'switch_label' => $result->switchLabel,
        ];
    }

    public function buildMetadata(WorkflowStepResultData $result): array
    {
        $data = [
            'workflow_type' => $result->workflowType,
            'current_step' => $result->currentStep,
            'total_steps' => $result->totalSteps,
            'completed_steps' => $result->completedSteps,
            'step_number' => $result->stepNumber,
            'progress_percent' => $result->progressPercent,
            'current_step_label' => $result->currentStepLabel,
            'next_conversation_state' => $result->nextConversationState ?? $this->deriveNextState($result),
        ];

        if ($result->completed) {
            $data['workflow_result'] = 'completed';
            $data['tracking_number'] = $result->trackingNumber;
            $data['submission_date'] = $result->submissionDate;
            $data['status_label'] = $result->statusLabel;
        }

        if ($result->cancelled) {
            $data['workflow_result'] = 'cancelled';
        }

        if ($result->confirming) {
            $data['workflow_confirmation'] = true;
        }

        return $data;
    }

    public function dedupeActions(array $actions): array
    {
        $deduped = [];
        $seen = [];

        foreach ($actions as $action) {
            $key = $action['key'] ?? $action['value'] ?? $action['label'] ?? null;

            if ($key === null || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $deduped[] = $action;
        }

        return $deduped;
    }
}
