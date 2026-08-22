<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class NewsSummaryData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $slug = null,
        public ?string $category = null,
        public ?string $summary = null,
        public ?string $publishAt = null,
        public ?string $author = null,
    ) {}
}
