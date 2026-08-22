<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\Services;

use App\Domains\ChatbotAnalytics\Contracts\IntentAnalyticsRepositoryInterface;
use App\Domains\ChatbotAnalytics\DTOs\IntentDistributionData;
use Carbon\Carbon;

/**
 * Aggregates intent-level statistics for the analytics dashboard.
 */
final readonly class IntentAnalyticsService
{
    public function __construct(
        private IntentAnalyticsRepositoryInterface $intentRepository,
    ) {}

    public function getDistributionReport(Carbon $from, Carbon $to, int $topLimit = 10): IntentDistributionData
    {
        $distribution = $this->intentRepository->getDistribution($from, $to);
        $unknownCount = $this->intentRepository->getUnknownCount($from, $to);
        $avgConfidence = $this->intentRepository->getAverageConfidence($from, $to);
        $topIntents = $this->intentRepository->getTopIntents($topLimit, $from, $to);
        $sourceBreakdown = $this->intentRepository->getSourceDistribution($from, $to);

        $total = array_sum($distribution);

        return new IntentDistributionData(
            distribution: $distribution,
            topIntents: $topIntents,
            avgConfidence: round($avgConfidence, 4),
            unknownCount: $unknownCount,
            unknownRate: $total > 0 ? round($unknownCount / $total * 100, 2) : 0.0,
            sourceDistribution: $sourceBreakdown,
            periodFrom: $from->toDateTimeString(),
            periodTo: $to->toDateTimeString(),
        );
    }
}
