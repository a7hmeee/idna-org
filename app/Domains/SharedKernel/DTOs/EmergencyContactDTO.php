<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\DTOs;

final readonly class EmergencyContactDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $contactableId = null,
        public ?string $contactableType = null,
        public string $name = '',
        public ?string $department = null,
        public string $phone = '',
        public ?string $icon = null,
        public int $displayOrder = 0,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            contactableId: isset($validated['contactable_id']) ? (int) $validated['contactable_id'] : null,
            contactableType: $validated['contactable_type'] ?? null,
            name: $validated['name'],
            department: $validated['department'] ?? null,
            phone: $validated['phone'],
            icon: $validated['icon'] ?? null,
            displayOrder: (int) ($validated['display_order'] ?? 0),
            isActive: (bool) ($validated['is_active'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'contactable_id' => $this->contactableId,
            'contactable_type' => $this->contactableType,
            'name' => $this->name,
            'department' => $this->department,
            'phone' => $this->phone,
            'icon' => $this->icon,
            'display_order' => $this->displayOrder,
            'is_active' => $this->isActive,
        ];
    }
}
