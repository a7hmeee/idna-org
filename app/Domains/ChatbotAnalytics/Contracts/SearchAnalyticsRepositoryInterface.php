<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotSearchAnalytics;
use Carbon\Carbon;

interface SearchAnalyticsRepositoryInterface
{
    public function create(array $data): ChatbotSearchAnalytics;

    public function getNoResultRate(Carbon $from, Carbon $to): float;

    public function getTopSearchQueries(int $limit, Carbon $from, Carbon $to): array;

    public function getAverageDuration(Carbon $from, Carbon $to): float;

    public function getClarificationRate(Carbon $from, Carbon $to): float;

    public function getTotalSearches(Carbon $from, Carbon $to): int;
}
