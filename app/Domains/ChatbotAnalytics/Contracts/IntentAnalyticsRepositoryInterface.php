<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotIntentAnalytics;
use Carbon\Carbon;

interface IntentAnalyticsRepositoryInterface
{
    public function create(array $data): ChatbotIntentAnalytics;

    public function getDistribution(Carbon $from, Carbon $to): array;

    public function getUnknownCount(Carbon $from, Carbon $to): int;

    public function getAverageConfidence(Carbon $from, Carbon $to): float;

    public function getTopIntents(int $limit, Carbon $from, Carbon $to): array;

    public function getSourceDistribution(Carbon $from, Carbon $to): array;
}
