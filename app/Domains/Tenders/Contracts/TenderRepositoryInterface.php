<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Contracts;

use App\Domains\Tenders\Models\Tender;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TenderRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?Tender;

    public function findBySlug(string $slug): ?Tender;

    public function create(array $data): Tender;

    public function update(int $id, array $data): Tender;

    public function delete(int $id): bool;

    public function publish(int $id): Tender;

    public function award(int $id): Tender;

    public function cancel(int $id): Tender;

    public function archive(int $id): Tender;

    public function toggleFeatured(int $id): Tender;

    public function incrementViews(int $id): void;

    public function getPublished(?string $search = null, ?string $filter = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;
}
