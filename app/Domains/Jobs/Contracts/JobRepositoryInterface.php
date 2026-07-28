<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Contracts;

use App\Domains\Jobs\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface JobRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?Job;

    public function findBySlug(string $slug): ?Job;

    public function create(array $data): Job;

    public function update(int $id, array $data): Job;

    public function delete(int $id): bool;

    public function publish(int $id): Job;

    public function archive(int $id): Job;

    public function close(int $id): Job;

    public function toggleFeatured(int $id): Job;

    public function incrementViews(int $id): void;

    public function getPublished(?string $search = null, ?string $filter = null, ?int $departmentId = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;
}
