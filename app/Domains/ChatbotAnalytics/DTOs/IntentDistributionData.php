<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\DTOs;

final readonly class IntentDistributionData
{
    public function __construct(
        public array $distribution,
        public array $topIntents,
        public float $avgConfidence,
        public int $unknownCount,
        public float $unknownRate,
        public array $sourceDistribution,
        public string $periodFrom,
        public string $periodTo,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
