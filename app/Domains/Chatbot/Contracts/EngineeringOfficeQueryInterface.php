<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\EngineeringOfficeDetailsData;

interface EngineeringOfficeQueryInterface
{
    public function getPublishedEngineeringOffices(int $limit = 10): array;

    public function searchPublishedEngineeringOffices(string $query, int $limit = 5): array;

    public function getPublishedEngineeringOfficeById(int $id): ?EngineeringOfficeDetailsData;
}
