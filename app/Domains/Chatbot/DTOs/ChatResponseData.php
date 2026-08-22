<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ChatResponseData
{
    public function __construct(
        public string $message,
        public string $type = 'text',
        public array $items = [],
        public array $actions = [],
        public bool $needsClarification = false,
        public ?string $clarificationType = null,
        public ?string $nextConversationState = null,
        public ?string $title = null,
        public ?array $workflow = null,
        public array $metadata = [],
        public bool $feedbackEligible = false,
        public bool $isFallbackResponse = false,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'items' => $this->items,
            'actions' => $this->actions,
            'needs_clarification' => $this->needsClarification,
            'clarification_type' => $this->clarificationType,
            'next_conversation_state' => $this->nextConversationState,
            'title' => $this->title,
            'workflow' => $this->workflow,
            'metadata' => $this->metadata,
            'feedback_eligible' => $this->feedbackEligible,
            'is_fallback_response' => $this->isFallbackResponse,
        ];
    }
}
