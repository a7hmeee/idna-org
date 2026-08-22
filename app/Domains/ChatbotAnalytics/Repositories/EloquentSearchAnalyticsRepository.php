<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\SearchAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotSearchAnalytics;
use Carbon\Carbon;

final readonly class EloquentSearchAnalyticsRepository implements SearchAnalyticsRepositoryInterface
{
    public function __construct(
        private ChatbotSearchAnalytics $model,
    ) {}

    public function create(array $data): ChatbotSearchAnalytics
    {
        return $this->model->create($data);
    }

    public function getNoResultRate(Carbon $from, Carbon $to): float
    {
        $total = $this->getTotalSearches($from, $to);

        if ($total === 0) {
            return 0.0;
        }

        $noResult = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('no_results', true)
            ->count();

        return round($noResult / $total * 100, 2);
    }

    public function getTopSearchQueries(int $limit, Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('query_normalized')
            ->selectRaw('query_normalized as query, count(*) as count')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getAverageDuration(Carbon $from, Carbon $to): float
    {
        return (float) ($this->model
            ->whereBetween('created_at', [$from, $to])
            ->avg('duration_ms') ?? 0.0);
    }

    public function getClarificationRate(Carbon $from, Carbon $to): float
    {
        $total = $this->getTotalSearches($from, $to);

        if ($total === 0) {
            return 0.0;
        }

        $clarification = $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('required_clarification', true)
            ->count();

        return round($clarification / $total * 100, 2);
    }

    public function getTotalSearches(Carbon $from, Carbon $to): int
    {
        return $this->model->whereBetween('created_at', [$from, $to])->count();
    }
}
