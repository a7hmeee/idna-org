<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class JobSummaryData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $departmentName = null,
        public ?string $employmentType = null,
        public ?string $location = null,
        public ?string $closingAt = null,
        public string $status = 'published',
    ) {}
}
