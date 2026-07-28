<?php

declare(strict_types=1);

namespace App\Domains\Jobs\DTOs;

use App\Domains\Jobs\Enums\ApplicationMethod;
use App\Domains\Jobs\Enums\EmploymentType;
use App\Domains\Jobs\Enums\JobStatus;

final readonly class JobData
{
    public function __construct(
        public string $title,
        public string $employmentType,
        public string $location,
        public string $summary,
        public string $description,
        public array $requirements,
        public array $responsibilities,
        public array $requiredDocuments,
        public string $applicationMethod,
        public string $publishAt,
        public string $closingAt,
        public ?int $departmentId = null,
        public ?string $slug = null,
        public ?string $jobNumber = null,
        public ?string $salary = null,
        public ?int $vacancies = null,
        public ?array $benefits = null,
        public ?string $applicationUrl = null,
        public ?string $applicationEmail = null,
        public ?string $applicationPhone = null,
        public ?string $attachmentPath = null,
        public ?string $status = null,
        public ?bool $isPublic = null,
        public ?bool $isFeatured = null,
        public ?int $displayOrder = null,
        public ?int $viewsCount = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            title: $validated['title'],
            employmentType: $validated['employmentType'],
            location: $validated['location'],
            summary: $validated['summary'],
            description: $validated['description'],
            requirements: $validated['requirements'] ?? [],
            responsibilities: $validated['responsibilities'] ?? [],
            requiredDocuments: $validated['requiredDocuments'] ?? [],
            applicationMethod: $validated['applicationMethod'],
            publishAt: $validated['publishAt'],
            closingAt: $validated['closingAt'],
            departmentId: isset($validated['departmentId']) ? (int) $validated['departmentId'] : null,
            slug: $validated['slug'] ?? null,
            jobNumber: $validated['jobNumber'] ?? null,
            salary: $validated['salary'] ?? null,
            vacancies: isset($validated['vacancies']) ? (int) $validated['vacancies'] : null,
            benefits: $validated['benefits'] ?? null,
            applicationUrl: $validated['applicationUrl'] ?? null,
            applicationEmail: $validated['applicationEmail'] ?? null,
            applicationPhone: $validated['applicationPhone'] ?? null,
            attachmentPath: $validated['attachmentPath'] ?? null,
            status: $validated['status'] ?? null,
            isPublic: isset($validated['isPublic']) ? (bool) $validated['isPublic'] : null,
            isFeatured: isset($validated['isFeatured']) ? (bool) $validated['isFeatured'] : null,
            displayOrder: isset($validated['displayOrder']) ? (int) $validated['displayOrder'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'department_id' => $this->departmentId,
            'title' => $this->title,
            'slug' => $this->slug,
            'job_number' => $this->jobNumber,
            'employment_type' => $this->employmentType,
            'location' => $this->location,
            'salary' => $this->salary,
            'vacancies' => $this->vacancies ?? 1,
            'summary' => $this->summary,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'responsibilities' => $this->responsibilities,
            'benefits' => $this->benefits,
            'required_documents' => $this->requiredDocuments,
            'application_method' => $this->applicationMethod,
            'application_url' => $this->applicationUrl,
            'application_email' => $this->applicationEmail,
            'application_phone' => $this->applicationPhone,
            'attachment_path' => $this->attachmentPath,
            'publish_at' => $this->publishAt,
            'closing_at' => $this->closingAt,
            'status' => $this->status,
            'is_public' => $this->isPublic,
            'is_featured' => $this->isFeatured,
            'display_order' => $this->displayOrder ?? 0,
            'views_count' => $this->viewsCount ?? 0,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
