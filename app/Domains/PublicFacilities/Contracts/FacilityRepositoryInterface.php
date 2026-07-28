<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Contracts;

use App\Domains\PublicFacilities\Models\Facility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FacilityRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?Facility;

    public function findBySlug(string $slug): ?Facility;

    public function create(array $data): Facility;

    public function update(int $id, array $data): Facility;

    public function delete(int $id): bool;

    public function publish(int $id): Facility;

    public function archive(int $id): Facility;

    public function toggleFeatured(int $id): Facility;

    public function incrementViews(int $id): void;

    public function getPublished(?string $search = null, ?string $filter = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;
}
