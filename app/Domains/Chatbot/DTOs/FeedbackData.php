<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class FeedbackData
{
    public function __construct(
        public int $messageId,
        public string $type,
        public ?string $comment = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            messageId: $data['message_id'] ?? $data['messageId'],
            type: $data['type'],
            comment: $data['comment'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'message_id' => $this->messageId,
            'type' => $this->type,
            'comment' => $this->comment,
        ];
    }
}
