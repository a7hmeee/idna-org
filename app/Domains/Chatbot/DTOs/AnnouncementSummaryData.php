<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class AnnouncementSummaryData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $type = null,
        public ?string $priority = null,
        public ?string $shortDescription = null,
        public ?string $publishedAt = null,
    ) {}
}
