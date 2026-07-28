<?php

namespace App\Domains\OpenData\Contracts;

use App\Domains\OpenData\Enums\OpenDataType;
use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OpenDataRepositoryInterface
{
    public function getDatasets(?string $search = null, ?string $category = null, ?OpenDataType $type = null): LengthAwarePaginator;

    public function find(int $id): ?OpenDataset;

    public function findBySlug(string $slug): ?OpenDataset;

    public function getFeaturedDatasets(): Collection;

    public function getLatestDatasets(int $limit = 5): Collection;

    public function getCategories(): Collection;

    public function downloadDataset(int $id): string;
}
