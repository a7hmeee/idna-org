<?php

declare(strict_types=1);

namespace App\Domains\Homepage\DTOs;

final readonly class HomepageQuickLinkData
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $icon = null,
        public ?string $url = null,
        public ?string $type = null,
        public ?bool $isExternal = null,
        public ?bool $isActive = null,
        public ?int $sortOrder = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            title: $validated['title'],
            description: $validated['description'] ?? null,
            icon: $validated['icon'] ?? null,
            url: $validated['url'] ?? null,
            type: $validated['type'] ?? null,
            isExternal: isset($validated['isExternal']) ? (bool) $validated['isExternal'] : null,
            isActive: isset($validated['isActive']) ? (bool) $validated['isActive'] : null,
            sortOrder: isset($validated['sortOrder']) ? (int) $validated['sortOrder'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'icon' => $this->icon,
            'url' => $this->url,
            'type' => $this->type,
            'is_external' => $this->isExternal,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
