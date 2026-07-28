<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Contracts;

use App\Domains\Municipality\Models\CouncilMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CouncilMemberRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?string $position = null, ?bool $isFeatured = null, ?bool $isPublic = null, string $sortField = 'display_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator;

    public function find(int $id): ?CouncilMember;

    public function findBySlug(string $slug): ?CouncilMember;

    public function create(array $data): CouncilMember;

    public function update(int $id, array $data): CouncilMember;

    public function delete(int $id): bool;

    public function togglePublic(int $id): CouncilMember;

    public function toggleFeatured(int $id): CouncilMember;

    public function reorder(array $ids): bool;

    public function getPublicMembers(): Collection;

    public function getFeaturedMembers(): Collection;

    public function getMayor(): ?CouncilMember;

    public function getCouncilBoard(): Collection;

    public function paginatePublicMembers(?string $search = null, ?string $position = null, int $perPage = 12): LengthAwarePaginator;
}
