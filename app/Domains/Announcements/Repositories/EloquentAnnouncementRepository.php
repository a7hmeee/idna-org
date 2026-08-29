<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Repositories;

use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Models\Announcement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentAnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function __construct(
        private Announcement $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderByDesc('published_at')
            ->paginate(15);
    }

    public function find(int $id): ?Announcement
    {
        return $this->model->with(['creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?Announcement
    {
        return $this->model->with(['creator', 'updater'])->where('slug', $slug)->first();
    }

    public function create(array $data): Announcement
    {
        $announcement = $this->model->create($data);

        $this->forgetCache();

        return $announcement->load(['creator', 'updater']);
    }

    public function update(int $id, array $data): Announcement
    {
        $announcement = $this->findOrFail($id);
        $announcement->update($data);

        $this->forgetCache();

        return $announcement->fresh()->load(['creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        $announcement = $this->findOrFail($id);
        $deleted = (bool) $announcement->delete();

        $this->forgetCache();

        return $deleted;
    }

    public function publish(int $id): Announcement
    {
        $announcement = $this->findOrFail($id);
        $announcement->update([
            'status' => 'published',
            'published_at' => $announcement->published_at ?? now(),
        ]);

        $this->forgetCache();

        return $announcement->fresh()->load(['creator', 'updater']);
    }

    public function unpublish(int $id): Announcement
    {
        $announcement = $this->findOrFail($id);
        $announcement->update(['status' => 'draft']);

        $this->forgetCache();

        return $announcement->fresh()->load(['creator', 'updater']);
    }

    public function toggleFeatured(int $id): Announcement
    {
        $announcement = $this->findOrFail($id);
        $announcement->update(['is_featured' => ! $announcement->is_featured]);

        $this->forgetCache();

        return $announcement->fresh()->load(['creator', 'updater']);
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views');
    }

    public function reorder(array $items): void
    {
        foreach ($items as $item) {
            $this->model->where('id', $item['id'])->update(['display_order' => $item['order']]);
        }

        $this->forgetCache();
    }

    public function getPublished(?string $search = null, ?string $type = null, ?string $priority = null): LengthAwarePaginator
    {
        $query = $this->model->published()->orderByDesc('is_featured')->orderByDesc('published_at');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($priority) {
            $query->where('priority', $priority);
        }

        return $query->paginate(12);
    }

    public function getFeatured(): Collection
    {
        $cached = Cache::remember('announcements_featured_v6', 3600, function (): array {
            return $this->model->published()->featured()->orderByDesc('published_at')->take(5)->get()->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    public function getLatest(int $limit = 5): Collection
    {
        $cached = Cache::remember('announcements_latest_v6', 3600, function () use ($limit): array {
            return $this->model->published()->orderByDesc('published_at')->take($limit)->get()->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    public function getUrgent(): Collection
    {
        $cached = Cache::remember('announcements_urgent_v6', 3600, function (): array {
            return $this->model->published()->urgent()->orderByDesc('published_at')->take(5)->get()->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    /**
     * Reconstruct Eloquent models from cached arrays.
     *
     * This avoids __PHP_Incomplete_Class by storing only primitive data
     * in cache and hydrating fresh model instances on read.
     */
    private function hydrateCollection(array $rows): Collection
    {
        $models = new Collection;

        foreach ($rows as $row) {
            $model = new Announcement;
            $model->forceFill($row);
            $model->exists = true;
            $models->push($model);
        }

        return $models;
    }

    private function findOrFail(int $id): Announcement
    {
        $announcement = $this->model->find($id);

        if (! $announcement) {
            throw new ModelNotFoundException("Announcement with ID {$id} not found.");
        }

        return $announcement;
    }

    private function forgetCache(): void
    {
        Cache::forget('announcements_featured_v6');
        Cache::forget('announcements_latest_v6');
        Cache::forget('announcements_urgent_v6');
        Cache::forget('announcements_featured_v5');
        Cache::forget('announcements_latest_v5');
        Cache::forget('announcements_urgent_v5');
        Cache::forget('announcements_featured_v4');
        Cache::forget('announcements_latest_v4');
        Cache::forget('announcements_urgent_v4');
        Cache::forget('homepage.public.data');
    }
}
