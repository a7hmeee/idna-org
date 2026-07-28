<?php

declare(strict_types=1);

namespace App\Domains\Projects\Contracts;

use App\Domains\Projects\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProjectRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?Project;

    public function findBySlug(string $slug): ?Project;

    public function create(array $data): Project;

    public function update(int $id, array $data): Project;

    public function delete(int $id): bool;

    public function publish(int $id): Project;

    public function unpublish(int $id): Project;

    public function toggleFeatured(int $id): Project;

    public function incrementViews(int $id): void;

    public function getPublished(?string $search = null, ?string $category = null, ?string $projectStatus = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;

    public function getByCategory(Project $category): Collection;

    public function getByProjectStatus(string $status): Collection;
}
