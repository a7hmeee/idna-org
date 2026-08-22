<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class DepartmentSummaryData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $slug = null,
        public ?string $shortDescription = null,
        public ?string $phone = null,
        public ?string $email = null,
        public string $status = 'active',
    ) {}
}
