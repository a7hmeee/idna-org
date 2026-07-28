<?php

declare(strict_types=1);

namespace App\Domains\News\Contracts;

use App\Domains\News\Models\NewsItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface NewsRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?NewsItem;

    public function findBySlug(string $slug): ?NewsItem;

    public function create(array $data): NewsItem;

    public function update(int $id, array $data): NewsItem;

    public function delete(int $id): bool;

    public function publish(int $id): NewsItem;

    public function unpublish(int $id): NewsItem;

    public function toggleFeatured(int $id): NewsItem;

    public function incrementViews(int $id): void;

    public function getPublished(?string $search = null, ?string $category = null, ?string $filter = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;
}
