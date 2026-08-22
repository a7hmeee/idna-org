<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatbotConversation;
use Illuminate\Database\Eloquent\Collection;

interface ChatbotConversationRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?ChatbotConversation;

    public function create(array $data): ChatbotConversation;

    public function update(int $id, array $data): ChatbotConversation;

    public function delete(int $id): bool;

    public function findActiveBySession(string $sessionId): ?ChatbotConversation;
}
