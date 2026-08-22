<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\UnknownQuestionRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotUnknownQuestion;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class EloquentUnknownQuestionRepository implements UnknownQuestionRepositoryInterface
{
    public function __construct(
        private ChatbotUnknownQuestion $model,
    ) {}

    public function createOrIncrement(string $question, string $normalizedQuestion, array $extra = []): ChatbotUnknownQuestion
    {
        $existing = $this->model->where('normalized_question', $normalizedQuestion)->first();

        if ($existing !== null) {
            $existing->increment('occurrence_count');
            $existing->update(['last_seen_at' => Carbon::now()]);

            return $existing->fresh();
        }

        return $this->model->create(array_merge([
            'question' => $question,
            'normalized_question' => $normalizedQuestion,
            'occurrence_count' => 1,
            'last_seen_at' => Carbon::now(),
            'admin_status' => 'new',
        ], $extra));
    }

    public function getAll(string $status = 'all', int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->orderByDesc('occurrence_count');

        if ($status !== 'all') {
            $query->where('admin_status', $status);
        }

        return $query->paginate($perPage);
    }

    public function updateStatus(int $id, string $status, ?string $notes = null): void
    {
        $this->model->where('id', $id)->update(array_filter([
            'admin_status' => $status,
            'admin_notes' => $notes,
        ], fn ($v) => $v !== null));
    }

    public function getTopUnknown(int $limit, Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('last_seen_at', [$from, $to])
            ->where('admin_status', 'new')
            ->orderByDesc('occurrence_count')
            ->limit($limit)
            ->get(['id', 'question', 'normalized_question', 'occurrence_count', 'last_seen_at'])
            ->toArray();
    }

    public function getTotalCount(string $status = 'all'): int
    {
        $query = $this->model->newQuery();

        if ($status !== 'all') {
            $query->where('admin_status', $status);
        }

        return $query->count();
    }

    public function findByNormalized(string $normalizedQuestion): ?ChatbotUnknownQuestion
    {
        return $this->model->where('normalized_question', $normalizedQuestion)->first();
    }
}
