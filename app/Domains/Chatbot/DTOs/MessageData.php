<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class MessageData
{
    public function __construct(
        public int $conversationId,
        public string $role,
        public string $content,
        public ?string $metadata = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            conversationId: $data['conversation_id'] ?? $data['conversationId'],
            role: $data['role'],
            content: $data['content'],
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'role' => $this->role,
            'content' => $this->content,
            'metadata' => $this->metadata,
        ];
    }
}
