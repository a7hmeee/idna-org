<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ConversationData
{
    public function __construct(
        public string $sessionId,
        public ?int $userId = null,
        public string $status = 'active',
        public ?string $metadata = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            sessionId: $data['session_id'] ?? $data['sessionId'],
            userId: $data['user_id'] ?? $data['userId'] ?? null,
            status: $data['status'] ?? 'active',
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'status' => $this->status,
            'metadata' => $this->metadata,
        ];
    }
}
