<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

final readonly class ResumeWorkflowHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CitizenWorkflowEngine $workflowEngine,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ResumeWorkflow;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $result = $this->workflowEngine->resume($message->sessionId, $message->userId);

        return new ChatResponseData(
            message: $result->message,
            type: $result->type,
            actions: $result->actions,
            metadata: [
                'workflow_resumed' => true,
                'workflow_type' => $result->workflowType,
                'draft_id' => $result->draftId,
                'current_step' => $result->currentStep,
                'total_steps' => $result->totalSteps,
                'completed_steps' => $result->completedSteps,
                'progress_percent' => $result->progressPercent,
            ],
            nextConversationState: $result->nextConversationState ?? ConversationState::WorkflowCollectingData->value,
        );
    }
}
