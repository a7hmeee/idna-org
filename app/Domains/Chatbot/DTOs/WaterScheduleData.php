<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class WaterScheduleData
{
    public function __construct(
        public int $id,
        public int $areaId,
        public string $areaName,
        public string $scheduleDate,
        public ?string $startTime = null,
        public ?string $endTime = null,
        public string $status = 'available',
        public ?string $notes = null,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'areaId' => $this->areaId,
            'areaName' => $this->areaName,
            'scheduleDate' => $this->scheduleDate,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
