<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ServiceSearchResultCollection
{
    /** @param ServiceSearchMatchData[] $matches */
    public function __construct(
        public string $originalMessage,
        public string $normalizedMessage,
        public array $matches = [],
        public ?ServiceSearchMatchData $bestMatch = null,
        public bool $isConfident = false,
        public bool $requiresClarification = false,
        public bool $noMatch = true,
        public float $scoreGap = 0.0,
    ) {}

    public function toArray(): array
    {
        return [
            'original_message' => $this->originalMessage,
            'normalized_message' => $this->normalizedMessage,
            'matches' => array_map(fn (ServiceSearchMatchData $m) => $m->toArray(), $this->matches),
            'best_match' => $this->bestMatch?->toArray(),
            'is_confident' => $this->isConfident,
            'requires_clarification' => $this->requiresClarification,
            'no_match' => $this->noMatch,
            'score_gap' => $this->scoreGap,
        ];
    }
}
