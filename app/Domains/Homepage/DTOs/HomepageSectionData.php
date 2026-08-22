<?php

declare(strict_types=1);

namespace App\Domains\Homepage\DTOs;

final readonly class HomepageSectionData
{
    public function __construct(
        public string $key,
        public ?string $title = null,
        public ?string $subtitle = null,
        public ?bool $isEnabled = null,
        public ?int $sortOrder = null,
        public ?int $itemsLimit = null,
        public ?array $settings = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            key: $validated['key'],
            title: $validated['title'] ?? null,
            subtitle: $validated['subtitle'] ?? null,
            isEnabled: isset($validated['isEnabled']) ? (bool) $validated['isEnabled'] : null,
            sortOrder: isset($validated['sortOrder']) ? (int) $validated['sortOrder'] : null,
            itemsLimit: isset($validated['itemsLimit']) ? (int) $validated['itemsLimit'] : null,
            settings: $validated['settings'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'is_enabled' => $this->isEnabled,
            'sort_order' => $this->sortOrder,
            'items_limit' => $this->itemsLimit,
            'settings' => $this->settings,
        ], fn ($value) => ! is_null($value));
    }
}
