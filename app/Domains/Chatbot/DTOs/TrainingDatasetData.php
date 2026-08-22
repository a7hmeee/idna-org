<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class TrainingDatasetData
{
    public function __construct(
        public array $examples = [],
        public array $labels = [],
        public array $intentCounts = [],
        public int $totalCount = 0,
        public int $intentCount = 0,
        public ?string $fingerprint = null,
    ) {}

    public function toArray(): array
    {
        return [
            'examples' => count($this->examples),
            'labels' => $this->labels,
            'intent_counts' => $this->intentCounts,
            'total_count' => $this->totalCount,
            'intent_count' => $this->intentCount,
            'fingerprint' => $this->fingerprint,
        ];
    }
}
