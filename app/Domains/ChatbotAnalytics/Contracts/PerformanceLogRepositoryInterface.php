<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Contracts;

use App\Domains\ChatbotAnalytics\Models\ChatbotPerformanceLog;
use Carbon\Carbon;

interface PerformanceLogRepositoryInterface
{
    public function create(array $data): ChatbotPerformanceLog;

    public function getAverageDuration(string $context, Carbon $from, Carbon $to): float;

    public function getSlowHandlers(int $thresholdMs, Carbon $from, Carbon $to): array;

    public function getStats(Carbon $from, Carbon $to): array;
}
