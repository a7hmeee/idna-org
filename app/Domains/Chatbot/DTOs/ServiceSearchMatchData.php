<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ServiceSearchMatchData
{
    public function __construct(
        public int $serviceId,
        public string $serviceName,
        public float $score,
        public string $matchedBy,
        public array $matchedTerms = [],
        public string $explanation = '',
        public int $priority = 0,
        public ?string $applicationUrl = null,
    ) {}

    public function toArray(): array
    {
        return [
            'service_id' => $this->serviceId,
            'service_name' => $this->serviceName,
            'score' => $this->score,
            'matched_by' => $this->matchedBy,
            'matched_terms' => $this->matchedTerms,
            'explanation' => $this->explanation,
            'priority' => $this->priority,
        ];
    }
}
