<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\DTOs;

final readonly class PerformanceReportData
{
    public function __construct(
        public float $avgResponseTimeMs,
        public float $p95ResponseTimeMs,
        public int $slowRequests,
        public int $totalRequests,
        public float $slowRate,
        public array $slowHandlers,
        public array $contextBreakdown,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
