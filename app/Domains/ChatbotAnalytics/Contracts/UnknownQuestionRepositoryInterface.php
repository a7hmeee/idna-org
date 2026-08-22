<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotUnknownQuestion;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

interface UnknownQuestionRepositoryInterface
{
    public function createOrIncrement(string $question, string $normalizedQuestion, array $extra = []): ChatbotUnknownQuestion;

    public function getAll(string $status = 'all', int $perPage = 20): LengthAwarePaginator;

    public function updateStatus(int $id, string $status, ?string $notes = null): void;

    public function getTopUnknown(int $limit, Carbon $from, Carbon $to): array;

    public function getTotalCount(string $status = 'all'): int;

    public function findByNormalized(string $normalizedQuestion): ?ChatbotUnknownQuestion;
}
