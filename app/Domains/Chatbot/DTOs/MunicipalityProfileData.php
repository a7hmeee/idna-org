<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class MunicipalityProfileData
{
    public function __construct(
        public int $id,
        public string $nameAr,
        public ?string $nameEn = null,
        public ?string $shortDescription = null,
        public ?string $fullDescription = null,
        public ?string $vision = null,
        public ?string $mission = null,
        public ?string $foundationDate = null,
        public ?string $municipalityCode = null,
        public ?string $population = null,
        public ?string $area = null,
        public ?string $latitude = null,
        public ?string $longitude = null,
    ) {}
}
