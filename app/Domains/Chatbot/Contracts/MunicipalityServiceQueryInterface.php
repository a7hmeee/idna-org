<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ResolvedServiceData;

interface MunicipalityServiceQueryInterface
{
    public function getPublishedServiceCategories(): array;

    public function getPublishedServicesByCategory(int $categoryId): array;

    public function getPublishedElectronicServices(): array;

    public function getCategoryById(int $categoryId): ?array;

    public function findPublishedByExactName(string $normalizedName): ?ResolvedServiceData;

    public function findPublishedByText(string $normalizedText): ?ResolvedServiceData;

    public function searchPublished(string $query, int $limit = 5): array;

    public function getPublishedOverview(int $serviceId): ?ResolvedServiceData;

    public function getPublishedApplicationGuide(int $serviceId): ?ResolvedServiceData;

    public function getPublishedRequirements(int $serviceId): ?ResolvedServiceData;

    public function getPublishedFees(int $serviceId): ?ResolvedServiceData;

    public function getPublishedDuration(int $serviceId): ?ResolvedServiceData;

    public function getPublishedLocation(int $serviceId): ?ResolvedServiceData;

    public function getPublishedOnlineLink(int $serviceId): ?ResolvedServiceData;

    public function getSearchDocuments(): array;
}
