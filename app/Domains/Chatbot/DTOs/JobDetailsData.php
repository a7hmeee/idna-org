<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class JobDetailsData
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $slug = null,
        public ?string $jobNumber = null,
        public ?string $departmentName = null,
        public ?string $employmentType = null,
        public ?string $location = null,
        public ?string $salary = null,
        public ?int $vacancies = null,
        public ?string $summary = null,
        public ?string $description = null,
        public array $requirements = [],
        public array $responsibilities = [],
        public array $benefits = [],
        public array $requiredDocuments = [],
        public ?string $applicationMethod = null,
        public ?string $applicationUrl = null,
        public ?string $applicationEmail = null,
        public ?string $applicationPhone = null,
        public ?string $publishAt = null,
        public ?string $closingAt = null,
        public string $status = 'published',
    ) {}
}
