<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ConversationStateData;

interface ConversationContextInterface
{
    public function getState(string $sessionId): ConversationStateData;

    public function updateState(string $sessionId, array $data): void;

    public function expire(string $sessionId): void;

    public function isExpired(string $sessionId): bool;

    public function reset(string $sessionId): void;
}
