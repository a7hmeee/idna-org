<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\DTOs;

final readonly class BusinessHourDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $hourableId = null,
        public ?string $hourableType = null,
        public string $day = 'saturday',
        public ?string $openingTime = null,
        public ?string $closingTime = null,
        public bool $isClosed = false,
        public int $displayOrder = 0,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            hourableId: isset($validated['hourable_id']) ? (int) $validated['hourable_id'] : null,
            hourableType: $validated['hourable_type'] ?? null,
            day: $validated['day'],
            openingTime: $validated['opening_time'] ?? null,
            closingTime: $validated['closing_time'] ?? null,
            isClosed: (bool) ($validated['is_closed'] ?? false),
            displayOrder: (int) ($validated['display_order'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'hourable_id' => $this->hourableId,
            'hourable_type' => $this->hourableType,
            'day' => $this->day,
            'opening_time' => $this->openingTime,
            'closing_time' => $this->closingTime,
            'is_closed' => $this->isClosed,
            'display_order' => $this->displayOrder,
        ];
    }
}
