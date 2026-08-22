<?php

declare(strict_types=1);

namespace App\Domains\ChatbotAnalytics\DTOs;

final readonly class KnowledgeGapReportData
{
    public function __construct(
        public int $totalUnknownQuestions,
        public int $newUnknownQuestions,
        public int $reviewedQuestions,
        public int $resolvedQuestions,
        public array $topUnknownQuestions,
        public float $unknownRate,
        public array $suggestedDomains,
    ) {}

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
