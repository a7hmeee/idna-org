<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\PerformanceLogRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotPerformanceLog;
use Carbon\Carbon;

final readonly class EloquentPerformanceLogRepository implements PerformanceLogRepositoryInterface
{
    public function __construct(
        private ChatbotPerformanceLog $model,
    ) {}

    public function create(array $data): ChatbotPerformanceLog
    {
        return $this->model->create($data);
    }

    public function getAverageDuration(string $context, Carbon $from, Carbon $to): float
    {
        return (float) ($this->model
            ->where('context', $context)
            ->whereBetween('created_at', [$from, $to])
            ->avg('duration_ms') ?? 0.0);
    }

    public function getSlowHandlers(int $thresholdMs, Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('duration_ms', '>=', $thresholdMs)
            ->groupBy('handler_class')
            ->selectRaw('handler_class, count(*) as count, avg(duration_ms) as avg_ms, max(duration_ms) as max_ms')
            ->orderByDesc('avg_ms')
            ->get()
            ->toArray();
    }

    public function getStats(Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('context')
            ->selectRaw('
                context,
                count(*) as calls,
                avg(duration_ms) as avg_ms,
                max(duration_ms) as max_ms,
                min(duration_ms) as min_ms,
                sum(case when slow_flag = 1 then 1 else 0 end) as slow_count
            ')
            ->orderByDesc('avg_ms')
            ->get()
            ->toArray();
    }
}
