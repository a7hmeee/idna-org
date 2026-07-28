<?php

declare(strict_types=1);

namespace App\Domains\Department\Contracts;

use App\Domains\Department\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface DepartmentRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?string $sortField = 'display_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?Department;

    public function findBySlug(string $slug): ?Department;

    public function create(array $data): Department;

    public function update(int $id, array $data): Department;

    public function delete(int $id): bool;

    public function togglePublic(int $id): Department;

    public function toggleFeatured(int $id): Department;

    public function reorder(array $ids): bool;

    public function getPublicDepartments(): Collection;

    public function getFeaturedDepartments(): Collection;

    public function getActiveDepartments(): Collection;

    public function paginatePublicDepartments(?string $search = null, ?string $filter = null, int $perPage = 12): LengthAwarePaginator;
}
