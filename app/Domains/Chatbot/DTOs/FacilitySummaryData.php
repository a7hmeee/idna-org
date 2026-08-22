<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class FacilitySummaryData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $slug = null,
        public ?string $categoryName = null,
        public ?string $summary = null,
        public ?string $phone = null,
        public ?string $address = null,
        public string $status = 'published',
    ) {}
}
