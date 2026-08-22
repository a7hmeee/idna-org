<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Models\ChatbotConversation;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatbotConversationRepository implements ChatbotConversationRepositoryInterface
{
    public function __construct(
        private ChatbotConversation $model,
    ) {}

    public function all(): Collection
    {
        return $this->model->with('messages')->orderByDesc('created_at')->get();
    }

    public function find(int $id): ?ChatbotConversation
    {
        return $this->model->with('messages')->find($id);
    }

    public function create(array $data): ChatbotConversation
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatbotConversation
    {
        $conversation = $this->model->findOrFail($id);
        $conversation->update($data);

        return $conversation->fresh();
    }

    public function delete(int $id): bool
    {
        $conversation = $this->model->find($id);

        if (! $conversation) {
            return false;
        }

        return (bool) $conversation->delete();
    }

    public function findActiveBySession(string $sessionId): ?ChatbotConversation
    {
        return $this->model
            ->where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();
    }
}
