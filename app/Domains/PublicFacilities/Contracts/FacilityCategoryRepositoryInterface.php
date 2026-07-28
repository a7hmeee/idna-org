<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Contracts;

use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FacilityCategoryRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?FacilityCategory;

    public function findBySlug(string $slug): ?FacilityCategory;

    public function create(array $data): FacilityCategory;

    public function update(int $id, array $data): FacilityCategory;

    public function delete(int $id): bool;

    public function getActive(): Collection;

    public function getAll(): Collection;
}
