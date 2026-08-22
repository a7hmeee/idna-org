<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\FacilityDetailsData;

interface FacilityQueryInterface
{
    public function getPublishedFacilities(int $limit = 10): array;

    public function searchPublishedFacilities(string $query, int $limit = 5): array;

    public function getPublishedFacilityById(int $id): ?FacilityDetailsData;
}
