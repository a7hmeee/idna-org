<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\DTOs;

final readonly class ElectronicServiceData
{
    public function __construct(
        public ?int $id = null,
        public int $serviceCategoryId = 0,
        public ?int $departmentId = null,
        public string $name = '',
        public ?string $slug = null,
        public ?string $summary = null,
        public ?string $description = null,
        public ?string $eligibility = null,
        public ?array $requirements = null,
        public ?array $documents = null,
        public ?array $steps = null,
        public ?array $fees = null,
        public ?string $processingTime = null,
        public ?string $portalUrl = null,
        public bool $requiresLogin = true,
        public string $status = 'draft',
        public bool $isPublic = true,
        public bool $isFeatured = false,
        public int $sortOrder = 0,
        public int $viewsCount = 0,
        public int $portalClicksCount = 0,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
        public ?string $publishedAt = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            serviceCategoryId: (int) ($validated['service_category_id'] ?? 0),
            departmentId: isset($validated['department_id']) ? (int) $validated['department_id'] : null,
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            summary: $validated['summary'] ?? null,
            description: $validated['description'] ?? null,
            eligibility: $validated['eligibility'] ?? null,
            requirements: $validated['requirements'] ?? null,
            documents: $validated['documents'] ?? null,
            steps: $validated['steps'] ?? null,
            fees: $validated['fees'] ?? null,
            processingTime: $validated['processing_time'] ?? null,
            portalUrl: $validated['portal_url'] ?? null,
            requiresLogin: (bool) ($validated['requires_login'] ?? true),
            status: $validated['status'] ?? 'draft',
            isPublic: (bool) ($validated['is_public'] ?? true),
            isFeatured: (bool) ($validated['is_featured'] ?? false),
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            viewsCount: (int) ($validated['views_count'] ?? 0),
            portalClicksCount: (int) ($validated['portal_clicks_count'] ?? 0),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
            publishedAt: $validated['published_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'service_category_id' => $this->serviceCategoryId,
            'department_id' => $this->departmentId,
            'name' => $this->name,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'eligibility' => $this->eligibility,
            'requirements' => $this->requirements,
            'documents' => $this->documents,
            'steps' => $this->steps,
            'fees' => $this->fees,
            'processing_time' => $this->processingTime,
            'portal_url' => $this->portalUrl,
            'requires_login' => $this->requiresLogin,
            'status' => $this->status,
            'is_public' => $this->isPublic,
            'is_featured' => $this->isFeatured,
            'sort_order' => $this->sortOrder,
            'views_count' => $this->viewsCount,
            'portal_clicks_count' => $this->portalClicksCount,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'published_at' => $this->publishedAt,
        ];
    }
}
