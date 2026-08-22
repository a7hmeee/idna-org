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

final readonly class CancelWorkflowHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CitizenWorkflowEngine $workflowEngine,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::CancelWorkflow;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $result = $this->workflowEngine->cancel($message->sessionId, $message->userId);

        return new ChatResponseData(
            message: $result->message,
            type: $result->type,
            actions: $result->actions,
            metadata: [
                'workflow_cancelled' => true,
                'workflow_type' => $result->workflowType,
            ],
            nextConversationState: $result->nextConversationState ?? ConversationState::Normal->value,
        );
    }
}
