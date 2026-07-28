<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\DTOs;

final readonly class FacilityData
{
    public function __construct(
        public string $name,
        public string $summary,
        public string $description,
        public string $address,
        public ?int $facilityCategoryId = null,
        public ?string $slug = null,
        public ?string $coverImagePath = null,
        public ?array $gallery = null,
        public ?string $phone = null,
        public ?string $email = null,
        public ?string $workingHours = null,
        public ?array $services = null,
        public ?array $features = null,
        public ?array $rules = null,
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
            name: $validated['name'],
            summary: $validated['summary'],
            description: $validated['description'],
            address: $validated['address'],
            facilityCategoryId: isset($validated['facilityCategoryId']) ? (int) $validated['facilityCategoryId'] : null,
            slug: $validated['slug'] ?? null,
            coverImagePath: $validated['coverImagePath'] ?? null,
            gallery: $validated['gallery'] ?? null,
            phone: $validated['phone'] ?? null,
            email: $validated['email'] ?? null,
            workingHours: $validated['workingHours'] ?? null,
            services: $validated['services'] ?? null,
            features: $validated['features'] ?? null,
            rules: $validated['rules'] ?? null,
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
            'facility_category_id' => $this->facilityCategoryId,
            'name' => $this->name,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'description' => $this->description,
            'cover_image_path' => $this->coverImagePath,
            'gallery' => $this->gallery,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'working_hours' => $this->workingHours,
            'services' => $this->services,
            'features' => $this->features,
            'rules' => $this->rules,
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
