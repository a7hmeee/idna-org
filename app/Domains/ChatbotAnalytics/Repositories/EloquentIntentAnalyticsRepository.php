<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Repositories;

use App\Domains\ChatbotAnalytics\Contracts\IntentAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\Models\ChatbotIntentAnalytics;
use Carbon\Carbon;

final readonly class EloquentIntentAnalyticsRepository implements IntentAnalyticsRepositoryInterface
{
    public function __construct(
        private ChatbotIntentAnalytics $model,
    ) {}

    public function create(array $data): ChatbotIntentAnalytics
    {
        return $this->model->create($data);
    }

    public function getDistribution(Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('final_intent')
            ->selectRaw('final_intent, count(*) as count')
            ->pluck('count', 'final_intent')
            ->toArray();
    }

    public function getUnknownCount(Carbon $from, Carbon $to): int
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->where('is_unknown', true)
            ->count();
    }

    public function getAverageConfidence(Carbon $from, Carbon $to): float
    {
        return (float) ($this->model
            ->whereBetween('created_at', [$from, $to])
            ->avg('confidence') ?? 0.0);
    }

    public function getTopIntents(int $limit, Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('final_intent')
            ->selectRaw('final_intent as intent, count(*) as count')
            ->orderByDesc('count')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function getSourceDistribution(Carbon $from, Carbon $to): array
    {
        return $this->model
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('prediction_source')
            ->selectRaw('prediction_source, count(*) as count')
            ->pluck('count', 'prediction_source')
            ->toArray();
    }
}
