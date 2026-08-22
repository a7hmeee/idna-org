<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\DepartmentDetailsData;

interface DepartmentQueryInterface
{
    public function getPublishedDepartments(int $limit = 10): array;

    public function searchPublishedDepartments(string $query, int $limit = 5): array;

    public function getPublishedDepartmentById(int $id): ?DepartmentDetailsData;

    public function getPublishedDepartmentByName(string $name): ?DepartmentDetailsData;
}
