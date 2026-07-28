<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\DTOs;

final readonly class FacilityCategoryData
{
    public function __construct(
        public string $name,
        public ?string $slug = null,
        public ?string $icon = null,
        public ?string $description = null,
        public ?int $displayOrder = null,
        public ?bool $isActive = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            slug: $validated['slug'] ?? null,
            icon: $validated['icon'] ?? null,
            description: $validated['description'] ?? null,
            displayOrder: isset($validated['displayOrder']) ? (int) $validated['displayOrder'] : null,
            isActive: isset($validated['isActive']) ? (bool) $validated['isActive'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'description' => $this->description,
            'display_order' => $this->displayOrder ?? 0,
            'is_active' => $this->isActive ?? true,
        ];
    }
}
