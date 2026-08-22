<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatbotFeedbackRepositoryInterface;
use App\Domains\Chatbot\Models\ChatbotFeedback;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatbotFeedbackRepository implements ChatbotFeedbackRepositoryInterface
{
    public function __construct(
        private ChatbotFeedback $model,
    ) {}

    public function all(): Collection
    {
        return $this->model->with('message')->orderByDesc('created_at')->get();
    }

    public function find(int $id): ?ChatbotFeedback
    {
        return $this->model->with('message')->find($id);
    }

    public function create(array $data): ChatbotFeedback
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatbotFeedback
    {
        $feedback = $this->model->findOrFail($id);
        $feedback->update($data);

        return $feedback->fresh();
    }

    public function delete(int $id): bool
    {
        $feedback = $this->model->find($id);

        if (! $feedback) {
            return false;
        }

        return (bool) $feedback->delete();
    }

    public function getByMessage(int $messageId): ?ChatbotFeedback
    {
        return $this->model
            ->where('message_id', $messageId)
            ->first();
    }
}
