<?php

namespace App\Domains\EngineeringOffices\Contracts;

use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use Illuminate\Pagination\LengthAwarePaginator;

interface EngineeringOfficeRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $approvalStatus = null, ?string $status = null): LengthAwarePaginator;

    public function find(int $id): ?EngineeringOffice;

    public function findBySlug(string $slug): ?EngineeringOffice;

    public function create(array $data): EngineeringOffice;

    public function update(int $id, array $data): EngineeringOffice;

    public function delete(int $id): bool;

    public function approve(int $id): EngineeringOffice;

    public function suspend(int $id): EngineeringOffice;

    public function markExpired(int $id): EngineeringOffice;

    public function togglePublic(int $id): EngineeringOffice;

    public function reorder(array $orders): void;

    public function getApprovedOffices(): iterable;

    public function getExpiredOffices(): iterable;

    public function getPublicOffices(?string $search = null, ?string $filter = null): LengthAwarePaginator;

    public function getFeaturedPublicOffices(): iterable;

    public function incrementViews(int $id): void;
}