<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class CouncilDecisionSummaryData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $decisionNumber = null,
        public ?string $decisionDate = null,
        public ?string $type = null,
        public ?string $summary = null,
        public string $status = 'published',
    ) {}
}
