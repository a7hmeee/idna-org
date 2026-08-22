<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Repositories;

use App\Domains\Chatbot\Contracts\ChatTrainingExampleRepositoryInterface;
use App\Domains\Chatbot\Models\ChatTrainingExample;
use Illuminate\Database\Eloquent\Collection;

final readonly class EloquentChatTrainingExampleRepository implements ChatTrainingExampleRepositoryInterface
{
    public function __construct(
        private ChatTrainingExample $model,
    ) {}

    public function getVerifiedActiveExamples(): Collection
    {
        return $this->model
            ->usable()
            ->with('intent')
            ->get();
    }

    public function getVerifiedActiveExamplesByIntent(int $intentId): Collection
    {
        return $this->model
            ->usable()
            ->where('chat_intent_id', $intentId)
            ->get();
    }

    public function create(array $data): ChatTrainingExample
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ChatTrainingExample
    {
        $example = $this->model->findOrFail($id);
        $example->update($data);

        return $example->fresh();
    }

    public function deactivate(int $id): bool
    {
        $example = $this->model->find($id);
        if (! $example) {
            return false;
        }

        return (bool) $example->update(['is_active' => false]);
    }

    public function countByIntent(): array
    {
        return $this->model
            ->usable()
            ->selectRaw('chat_intent_id, COUNT(*) as count')
            ->groupBy('chat_intent_id')
            ->pluck('count', 'chat_intent_id')
            ->toArray();
    }

    public function datasetFingerprint(): string
    {
        $examples = $this->model
            ->usable()
            ->orderBy('chat_intent_id')
            ->orderBy('normalized_text')
            ->pluck('normalized_text')
            ->implode('|');

        return md5($examples);
    }
}
