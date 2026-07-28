<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class CustomFieldDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $municipalityId = null,
        public string $key = '',
        public string $value = '',
        public string $type = 'text',
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            municipalityId: isset($validated['municipality_id']) ? (int) $validated['municipality_id'] : null,
            key: $validated['key'],
            value: $validated['value'],
            type: $validated['type'],
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
            'type' => $this->type,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
