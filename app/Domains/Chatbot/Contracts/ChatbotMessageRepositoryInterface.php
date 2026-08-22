<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Models\ChatbotMessage;
use Illuminate\Database\Eloquent\Collection;

interface ChatbotMessageRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?ChatbotMessage;

    public function create(array $data): ChatbotMessage;

    public function update(int $id, array $data): ChatbotMessage;

    public function delete(int $id): bool;

    public function getByConversation(int $conversationId): Collection;
}
