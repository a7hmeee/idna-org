<?php

declare(strict_types=1);

namespace App\Domains\Projects\DTOs;

use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;

final readonly class ProjectData
{
    public function __construct(
        public string $nameAr,
        public ?string $nameEn,
        public ProjectCategory $category,
        public ProjectStatus $projectStatus,
        public ProjectStatus $status,
        public ?string $slug = null,
        public ?string $summary = null,
        public ?string $description = null,
        public ?string $startDate = null,
        public ?string $expectedCompletionDate = null,
        public ?string $actualCompletionDate = null,
        public ?string $location = null,
        public ?float $budget = null,
        public ?string $budgetCurrency = 'ILS',
        public ?int $implementationPercentage = 0,
        public ?string $contractor = null,
        public ?string $fundingEntity = null,
        public ?string $coverImagePath = null,
        public ?array $gallery = null,
        public ?array $documents = null,
        public bool $isFeatured = false,
        public bool $isPublic = false,
        public int $displayOrder = 0,
        public int $viewsCount = 0,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            nameAr: $data['nameAr'] ?? $data['name_ar'] ?? $data['name'],
            nameEn: $data['nameEn'] ?? $data['name_en'] ?? null,
            category: $data['category'] instanceof ProjectCategory ? $data['category'] : ProjectCategory::from($data['category']),
            projectStatus: $data['projectStatus'] instanceof ProjectStatus ? $data['projectStatus'] : ProjectStatus::from($data['projectStatus'] ?? 'planned'),
            status: $data['status'] instanceof ProjectStatus ? $data['status'] : ProjectStatus::from($data['status'] ?? 'planned'),
            slug: $data['slug'] ?? null,
            summary: $data['summary'] ?? null,
            description: $data['description'] ?? null,
            startDate: $data['startDate'] ?? $data['start_date'] ?? null,
            expectedCompletionDate: $data['expectedCompletionDate'] ?? $data['expected_completion_date'] ?? null,
            actualCompletionDate: $data['actualCompletionDate'] ?? $data['actual_completion_date'] ?? null,
            location: $data['location'] ?? null,
            budget: isset($data['budget']) ? (float) $data['budget'] : null,
            budgetCurrency: $data['budgetCurrency'] ?? $data['budget_currency'] ?? 'ILS',
            implementationPercentage: isset($data['implementationPercentage']) ? (int) $data['implementationPercentage'] : ($data['implementation_percentage'] ?? 0),
            contractor: $data['contractor'] ?? null,
            fundingEntity: $data['fundingEntity'] ?? $data['funding_entity'] ?? null,
            coverImagePath: $data['coverImagePath'] ?? $data['cover_image_path'] ?? null,
            gallery: $data['gallery'] ?? null,
            documents: $data['documents'] ?? null,
            isFeatured: $data['isFeatured'] ?? $data['is_featured'] ?? false,
            isPublic: $data['isPublic'] ?? $data['is_public'] ?? false,
            displayOrder: $data['displayOrder'] ?? $data['display_order'] ?? 0,
            viewsCount: $data['viewsCount'] ?? $data['views_count'] ?? 0,
            createdBy: $data['createdBy'] ?? $data['created_by'] ?? null,
            updatedBy: $data['updatedBy'] ?? $data['updated_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name_ar' => $this->nameAr,
            'name_en' => $this->nameEn,
            'slug' => $this->slug,
            'category' => $this->category->value,
            'project_status' => $this->projectStatus->value,
            'status' => $this->status->value,
            'summary' => $this->summary,
            'description' => $this->description,
            'start_date' => $this->startDate,
            'expected_completion_date' => $this->expectedCompletionDate,
            'actual_completion_date' => $this->actualCompletionDate,
            'location' => $this->location,
            'budget' => $this->budget,
            'budget_currency' => $this->budgetCurrency,
            'implementation_percentage' => $this->implementationPercentage ?? 0,
            'contractor' => $this->contractor,
            'funding_entity' => $this->fundingEntity,
            'cover_image_path' => $this->coverImagePath,
            'gallery' => $this->gallery,
            'documents' => $this->documents,
            'is_featured' => $this->isFeatured,
            'is_public' => $this->isPublic,
            'display_order' => $this->displayOrder,
            'views_count' => $this->viewsCount,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
