<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class SocialPlatformDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $municipalityId = null,
        public string $name = '',
        public string $slug = '',
        public string $icon = '',
        public string $url = '',
        public ?string $color = null,
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            municipalityId: isset($validated['municipality_id']) ? (int) $validated['municipality_id'] : null,
            name: $validated['name'],
            slug: $validated['slug'],
            icon: $validated['icon'],
            url: $validated['url'],
            color: $validated['color'] ?? null,
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'icon' => $this->icon,
            'url' => $this->url,
            'color' => $this->color,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
