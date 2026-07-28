<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\DTOs;

final readonly class ServiceCategoryData
{
    public function __construct(
        public ?int $id = null,
        public ?int $parentId = null,
        public string $name = '',
        public ?string $slug = null,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $imagePath = null,
        public string $status = 'active',
        public bool $isPublic = true,
        public int $sortOrder = 0,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            parentId: isset($validated['parent_id']) ? (int) $validated['parent_id'] : null,
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            description: $validated['description'] ?? null,
            icon: $validated['icon'] ?? null,
            imagePath: $validated['image_path'] ?? null,
            status: $validated['status'] ?? 'active',
            isPublic: (bool) ($validated['is_public'] ?? true),
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'icon' => $this->icon,
            'image_path' => $this->imagePath,
            'status' => $this->status,
            'is_public' => $this->isPublic,
            'sort_order' => $this->sortOrder,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
