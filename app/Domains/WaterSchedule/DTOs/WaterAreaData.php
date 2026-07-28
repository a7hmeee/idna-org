<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\DTOs;

final readonly class WaterAreaData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public ?int $displayOrder = null,
        public ?bool $isActive = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            description: $validated['description'] ?? null,
            displayOrder: isset($validated['displayOrder']) ? (int) $validated['displayOrder'] : null,
            isActive: isset($validated['isActive']) ? (bool) $validated['isActive'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
