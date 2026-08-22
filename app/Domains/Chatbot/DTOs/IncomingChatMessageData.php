<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class IncomingChatMessageData
{
    public function __construct(
        public string $message,
        public string $sessionId,
        public ?int $userId = null,
        public string $channel = 'web',
        public ?string $displayLabel = null,
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
            'channel' => $this->channel,
            'display_label' => $this->displayLabel,
        ];
    }
}
