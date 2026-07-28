<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class ContactDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $municipalityId = null,
        public string $type = 'phone',
        public string $label = '',
        public ?string $value = null,
        public ?string $icon = null,
        public ?string $url = null,
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            municipalityId: isset($validated['municipality_id']) ? (int) $validated['municipality_id'] : null,
            type: $validated['type'],
            label: $validated['label'],
            value: $validated['value'] ?? null,
            icon: $validated['icon'] ?? null,
            url: $validated['url'] ?? null,
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'label' => $this->label,
            'value' => $this->value,
            'icon' => $this->icon,
            'url' => $this->url,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
