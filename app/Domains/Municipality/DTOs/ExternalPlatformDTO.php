<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class ExternalPlatformDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $municipalityId = null,
        public string $name = '',
        public ?string $description = null,
        public string $icon = '',
        public string $url = '',
        public ?string $category = null,
        public ?string $color = null,
        public bool $openInNewTab = true,
        public bool $isFeatured = false,
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            municipalityId: isset($validated['municipality_id']) ? (int) $validated['municipality_id'] : null,
            name: $validated['name'],
            description: $validated['description'] ?? null,
            icon: $validated['icon'],
            url: $validated['url'],
            category: $validated['category'] ?? null,
            color: $validated['color'] ?? null,
            openInNewTab: (bool) ($validated['open_in_new_tab'] ?? true),
            isFeatured: (bool) ($validated['is_featured'] ?? false),
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'icon' => $this->icon,
            'url' => $this->url,
            'category' => $this->category,
            'color' => $this->color,
            'open_in_new_tab' => $this->openInNewTab,
            'is_featured' => $this->isFeatured,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
