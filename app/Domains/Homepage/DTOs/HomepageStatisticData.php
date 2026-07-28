<?php

declare(strict_types=1);

namespace App\Domains\Homepage\DTOs;

final readonly class HomepageStatisticData
{
    public function __construct(
        public string $label,
        public string $value,
        public ?string $suffix = null,
        public ?string $icon = null,
        public ?string $description = null,
        public ?bool $isActive = null,
        public ?int $sortOrder = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            label: $validated['label'],
            value: $validated['value'],
            suffix: $validated['suffix'] ?? null,
            icon: $validated['icon'] ?? null,
            description: $validated['description'] ?? null,
            isActive: isset($validated['isActive']) ? (bool) $validated['isActive'] : null,
            sortOrder: isset($validated['sortOrder']) ? (int) $validated['sortOrder'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'value' => $this->value,
            'suffix' => $this->suffix,
            'icon' => $this->icon,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
