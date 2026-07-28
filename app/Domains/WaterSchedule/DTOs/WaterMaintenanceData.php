<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\DTOs;

final readonly class WaterMaintenanceData
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?string $startsAt = null,
        public ?string $endsAt = null,
        public ?string $status = null,
        public ?array $affectedAreas = null,
        public ?bool $isPublic = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            title: $validated['title'],
            description: $validated['description'] ?? null,
            startsAt: $validated['startsAt'] ?? null,
            endsAt: $validated['endsAt'] ?? null,
            status: $validated['status'] ?? null,
            affectedAreas: $validated['affectedAreas'] ?? null,
            isPublic: isset($validated['isPublic']) ? (bool) $validated['isPublic'] : null,
            createdBy: isset($validated['createdBy']) ? (int) $validated['createdBy'] : null,
            updatedBy: isset($validated['updatedBy']) ? (int) $validated['updatedBy'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'status' => $this->status,
            'affected_areas' => $this->affectedAreas,
            'is_public' => $this->isPublic,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
