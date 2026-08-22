<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatbotModelVersionRepositoryInterface;
use App\Domains\Chatbot\Models\ChatbotModelVersion;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatbotModelVersionRepository implements ChatbotModelVersionRepositoryInterface
{
    public function __construct(
        private ChatbotModelVersion $model,
    ) {}

    public function all(): Collection
    {
        return $this->model->orderByDesc('created_at')->get();
    }

    public function find(int $id): ?ChatbotModelVersion
    {
        return $this->model->find($id);
    }

    public function create(array $data): ChatbotModelVersion
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatbotModelVersion
    {
        $version = $this->model->findOrFail($id);
        $version->update($data);

        return $version->fresh();
    }

    public function delete(int $id): bool
    {
        $version = $this->model->find($id);

        if (! $version) {
            return false;
        }

        return (bool) $version->delete();
    }

    public function getActive(): ?ChatbotModelVersion
    {
        return $this->model
            ->where('status', 'active')
            ->first();
    }
}
