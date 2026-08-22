<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class CouncilDecisionDetailsData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $decisionNumber = null,
        public ?string $type = null,
        public ?string $summary = null,
        public ?string $content = null,
        public ?string $decisionDate = null,
        public ?string $sessionNumber = null,
        public ?string $publishedAt = null,
        public ?string $attachmentPath = null,
        public string $status = 'published',
    ) {}
}
