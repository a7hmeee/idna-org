<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\DTOs;

use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;

final readonly class WaterScheduleData
{
    public function __construct(
        public int $waterAreaId,
        public string $scheduleDate,
        public ?string $startTime = null,
        public ?string $endTime = null,
        public ?WaterScheduleStatus $status = null,
        public ?string $notes = null,
        public ?int $displayOrder = null,
        public ?bool $isPublic = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            waterAreaId: (int) $validated['waterAreaId'],
            scheduleDate: $validated['scheduleDate'],
            startTime: $validated['startTime'] ?? null,
            endTime: $validated['endTime'] ?? null,
            status: isset($validated['status']) ? WaterScheduleStatus::from($validated['status']) : null,
            notes: $validated['notes'] ?? null,
            displayOrder: isset($validated['displayOrder']) ? (int) $validated['displayOrder'] : null,
            isPublic: isset($validated['isPublic']) ? (bool) $validated['isPublic'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'water_area_id' => $this->waterAreaId,
            'schedule_date' => $this->scheduleDate,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'status' => $this->status?->value,
            'notes' => $this->notes,
            'display_order' => $this->displayOrder,
            'is_public' => $this->isPublic,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
