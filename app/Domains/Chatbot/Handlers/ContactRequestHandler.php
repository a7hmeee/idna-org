<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Handlers;

use App\Domains\Chatbot\Contracts\ChatResponseHandlerInterface;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use App\Domains\CitizenWorkflows\Services\WorkflowResponseBuilder;

final readonly class ContactRequestHandler implements ChatResponseHandlerInterface
{
    public function __construct(
        private CitizenWorkflowEngine $workflowEngine,
        private WorkflowResponseBuilder $responseBuilder,
    ) {}

    public function supports(ChatbotIntent $intent): bool
    {
        return $intent === ChatbotIntent::ContactRequest;
    }

    public function handle(
        IncomingChatMessageData $message,
        ?ResolvedServiceData $service,
    ): ChatResponseData {
        $result = $this->workflowEngine->start(
            sessionId: $message->sessionId,
            type: WorkflowType::ContactRequest,
            userId: $message->userId,
        );

        return $this->responseBuilder->build($result);
    }
}
