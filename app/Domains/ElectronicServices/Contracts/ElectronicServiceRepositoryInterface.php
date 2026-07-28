<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Contracts;

use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ElectronicServiceRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?int $categoryId = null, ?int $departmentId = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?ElectronicService;

    public function findBySlug(string $slug): ?ElectronicService;

    public function create(array $data): ElectronicService;

    public function update(int $id, array $data): ElectronicService;

    public function delete(int $id): bool;

    public function publish(int $id): ElectronicService;

    public function archive(int $id): ElectronicService;

    public function togglePublic(int $id): ElectronicService;

    public function toggleFeatured(int $id): ElectronicService;

    public function incrementViews(int $id): void;

    public function incrementPortalClicks(int $id): void;

    public function getPublicServices(): Collection;

    public function getFeaturedServices(): Collection;

    public function getByCategory(int $categoryId): Collection;

    public function getByCategoryPaginated(int $categoryId, ?string $search = null, ?string $departmentId = null, ?bool $requiresLogin = null, ?bool $isFeatured = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 12): LengthAwarePaginator;

    public function getRelatedServices(int $categoryId, int $excludeServiceId, int $limit = 3): Collection;

    public function searchPublicServices(string $search, ?int $categoryId = null, int $perPage = 12, ?string $departmentSlug = null): LengthAwarePaginator;

    public function getMostViewed(int $limit = 10): Collection;

    public function getMostClicked(int $limit = 10): Collection;
}
