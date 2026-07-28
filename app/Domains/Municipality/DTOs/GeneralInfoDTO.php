<?php

declare(strict_types=1);

namespace App\Domains\Municipality\DTOs;

final readonly class GeneralInfoDTO
{
    public function __construct(
        public string $nameAr,
        public string $nameEn,
        public ?string $shortDescription = null,
        public ?string $fullDescription = null,
        public ?string $vision = null,
        public ?string $mission = null,
        public ?array $objectives = null,
        public ?string $foundationDate = null,
        public ?int $population = null,
        public ?float $area = null,
        public ?string $municipalityCode = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            nameAr: $validated['nameAr'],
            nameEn: $validated['nameEn'],
            shortDescription: $validated['shortDescription'] ?? null,
            fullDescription: $validated['fullDescription'] ?? null,
            vision: $validated['vision'] ?? null,
            mission: $validated['mission'] ?? null,
            objectives: $validated['objectives'] ?? null,
            foundationDate: $validated['foundationDate'] ?? null,
            population: isset($validated['population']) ? (int) $validated['population'] : null,
            area: isset($validated['area']) ? (float) $validated['area'] : null,
            municipalityCode: $validated['municipalityCode'] ?? null,
            latitude: isset($validated['latitude']) ? (float) $validated['latitude'] : null,
            longitude: isset($validated['longitude']) ? (float) $validated['longitude'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'name_ar' => $this->nameAr,
            'name_en' => $this->nameEn,
            'short_description' => $this->shortDescription,
            'full_description' => $this->fullDescription,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'objectives' => $this->objectives,
            'foundation_date' => $this->foundationDate,
            'population' => $this->population,
            'area' => $this->area,
            'municipality_code' => $this->municipalityCode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
