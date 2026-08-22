<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Services;

use App\Domains\ChatbotAnalytics\Contracts\PerformanceLogRepositoryInterface;
use App\Domains\ChatbotAnalytics\DTOs\PerformanceReportData;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Monitors chatbot performance and generates performance reports.
 */
final readonly class PerformanceMonitorService
{
    /** Requests slower than this are considered "slow" */
    private const SLOW_THRESHOLD_MS = 500;

    public function __construct(
        private PerformanceLogRepositoryInterface $performanceRepository,
    ) {}

    public function generateReport(Carbon $from, Carbon $to): PerformanceReportData
    {
        $stats = $this->performanceRepository->getStats($from, $to);
        $slowHandlers = $this->performanceRepository->getSlowHandlers(self::SLOW_THRESHOLD_MS, $from, $to);

        $totalRequests = (int) DB::table('chatbot_performance_logs')
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $slowRequests = (int) DB::table('chatbot_performance_logs')
            ->whereBetween('created_at', [$from, $to])
            ->where('slow_flag', true)
            ->count();

        $avgMs = (float) (DB::table('chatbot_performance_logs')
            ->whereBetween('created_at', [$from, $to])
            ->avg('duration_ms') ?? 0.0);

        // P95 approximation using 95th percentile
        $p95Ms = $this->calculateP95($from, $to);

        return new PerformanceReportData(
            avgResponseTimeMs: round($avgMs, 2),
            p95ResponseTimeMs: $p95Ms,
            slowRequests: $slowRequests,
            totalRequests: $totalRequests,
            slowRate: $totalRequests > 0 ? round($slowRequests / $totalRequests * 100, 2) : 0.0,
            slowHandlers: $slowHandlers,
            contextBreakdown: $stats,
        );
    }

    public function recordRequest(string $context, int $durationMs, array $extra = []): void
    {
        $this->performanceRepository->create(array_merge([
            'context' => $context,
            'duration_ms' => $durationMs,
            'slow_flag' => $durationMs >= self::SLOW_THRESHOLD_MS,
        ], $extra));
    }

    private function calculateP95(Carbon $from, Carbon $to): float
    {
        $rows = DB::table('chatbot_performance_logs')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('duration_ms')
            ->pluck('duration_ms')
            ->toArray();

        if (empty($rows)) {
            return 0.0;
        }

        $index = (int) ceil(0.95 * count($rows)) - 1;

        return (float) ($rows[$index] ?? end($rows));
    }
}
