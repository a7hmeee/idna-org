<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class NewsDetailsData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $slug = null,
        public ?string $category = null,
        public ?string $summary = null,
        public ?string $content = null,
        public ?string $publishAt = null,
        public ?string $author = null,
        public ?string $coverImageUrl = null,
    ) {}
}
