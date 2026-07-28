<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Contracts;

use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ServiceCategoryRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?ServiceCategory;

    public function findBySlug(string $slug): ?ServiceCategory;

    public function create(array $data): ServiceCategory;

    public function update(int $id, array $data): ServiceCategory;

    public function delete(int $id): bool;

    public function togglePublic(int $id): ServiceCategory;

    public function reorder(array $ids): bool;

    public function getPublicCategories(): Collection;

    public function getRootPublicCategories(): Collection;

    public function getChildren(int $parentId): Collection;
}
