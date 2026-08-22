<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatbotMessageRepositoryInterface;
use App\Domains\Chatbot\Models\ChatbotMessage;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatbotMessageRepository implements ChatbotMessageRepositoryInterface
{
    public function __construct(
        private ChatbotMessage $model,
    ) {}

    public function all(): Collection
    {
        return $this->model->with('conversation')->orderByDesc('created_at')->get();
    }

    public function find(int $id): ?ChatbotMessage
    {
        return $this->model->with('conversation')->find($id);
    }

    public function create(array $data): ChatbotMessage
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatbotMessage
    {
        $message = $this->model->findOrFail($id);
        $message->update($data);

        return $message->fresh();
    }

    public function delete(int $id): bool
    {
        $message = $this->model->find($id);

        if (! $message) {
            return false;
        }

        return (bool) $message->delete();
    }

    public function getByConversation(int $conversationId): Collection
    {
        return $this->model
            ->where('conversation_id', $conversationId)
            ->orderBy('created_at')
            ->get();
    }
}
