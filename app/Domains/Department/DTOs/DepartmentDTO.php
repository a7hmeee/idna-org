<?php

declare(strict_types=1);

namespace App\Domains\Department\DTOs;

final readonly class DepartmentDTO
{
    public function __construct(
        public ?int $id = null,
        public string $name = '',
        public ?string $slug = null,
        public ?string $shortDescription = null,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $coverImagePath = null,
        public ?string $managerName = null,
        public ?string $managerPosition = null,
        public ?string $phone = null,
        public ?string $extension = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $officeLocation = null,
        public ?string $workingHours = null,
        public ?string $vision = null,
        public ?string $mission = null,
        public ?string $responsibilities = null,
        public string $status = 'active',
        public int $displayOrder = 0,
        public bool $isPublic = true,
        public bool $isFeatured = false,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            shortDescription: $validated['short_description'] ?? null,
            description: $validated['description'] ?? null,
            icon: $validated['icon'] ?? null,
            coverImagePath: $validated['cover_image_path'] ?? null,
            managerName: $validated['manager_name'] ?? null,
            managerPosition: $validated['manager_position'] ?? null,
            phone: $validated['phone'] ?? null,
            extension: $validated['extension'] ?? null,
            mobile: $validated['mobile'] ?? null,
            email: $validated['email'] ?? null,
            officeLocation: $validated['office_location'] ?? null,
            workingHours: $validated['working_hours'] ?? null,
            vision: $validated['vision'] ?? null,
            mission: $validated['mission'] ?? null,
            responsibilities: $validated['responsibilities'] ?? null,
            status: $validated['status'],
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isPublic: (bool) ($validated['is_public'] ?? true),
            isFeatured: (bool) ($validated['is_featured'] ?? false),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->shortDescription,
            'description' => $this->description,
            'icon' => $this->icon,
            'cover_image_path' => $this->coverImagePath,
            'manager_name' => $this->managerName,
            'manager_position' => $this->managerPosition,
            'phone' => $this->phone,
            'extension' => $this->extension,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'office_location' => $this->officeLocation,
            'working_hours' => $this->workingHours,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'responsibilities' => $this->responsibilities,
            'status' => $this->status,
            'display_order' => $this->displayOrder,
            'is_public' => $this->isPublic,
            'is_featured' => $this->isFeatured,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
