<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatbotServiceAliasRepositoryInterface;
use App\Domains\Chatbot\Models\ChatbotServiceAlias;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatbotServiceAliasRepository implements ChatbotServiceAliasRepositoryInterface
{
    public function __construct(
        private ChatbotServiceAlias $model,
    ) {}

    public function all(): Collection
    {
        return $this->model->orderBy('alias')->get();
    }

    public function find(int $id): ?ChatbotServiceAlias
    {
        return $this->model->find($id);
    }

    public function create(array $data): ChatbotServiceAlias
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatbotServiceAlias
    {
        $alias = $this->model->findOrFail($id);
        $alias->update($data);

        return $alias->fresh();
    }

    public function delete(int $id): bool
    {
        $alias = $this->model->find($id);

        if (! $alias) {
            return false;
        }

        return (bool) $alias->delete();
    }

    public function findByAlias(string $alias): ?ChatbotServiceAlias
    {
        return $this->model
            ->where('alias', $alias)
            ->first();
    }
}
