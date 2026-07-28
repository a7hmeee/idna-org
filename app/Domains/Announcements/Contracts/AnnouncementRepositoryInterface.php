<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Contracts;

use App\Domains\Announcements\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface AnnouncementRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?Announcement;

    public function findBySlug(string $slug): ?Announcement;

    public function create(array $data): Announcement;

    public function update(int $id, array $data): Announcement;

    public function delete(int $id): bool;

    public function publish(int $id): Announcement;

    public function unpublish(int $id): Announcement;

    public function toggleFeatured(int $id): Announcement;

    public function incrementViews(int $id): void;

    public function reorder(array $items): void;

    public function getPublished(?string $search = null, ?string $type = null, ?string $priority = null): LengthAwarePaginator;

    public function getFeatured(): Collection;

    public function getLatest(int $limit = 5): Collection;

    public function getUrgent(): Collection;
}
