<?php

declare(strict_types=1);

namespace App\Domains\News\Repositories;

use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Enums\NewsStatus;
use App\Domains\News\Models\NewsItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentNewsRepository implements NewsRepositoryInterface
{
    public function __construct(
        private NewsItem $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderBy('publish_at', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?NewsItem
    {
        return $this->model->with(['creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?NewsItem
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): NewsItem
    {
        $news = $this->model->create($data);

        $this->forgetCache();

        return $news->load(['creator', 'updater']);
    }

    public function update(int $id, array $data): NewsItem
    {
        $news = $this->findOrFail($id);
        $news->update($data);

        $this->forgetCache();

        return $news->fresh()->load(['creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        $news = $this->findOrFail($id);

        return (bool) $news->delete();
    }

    public function publish(int $id): NewsItem
    {
        $news = $this->findOrFail($id);
        $news->update([
            'status' => NewsStatus::Published,
            'is_public' => true,
            'publish_at' => $news->publish_at ?? now(),
        ]);

        $this->forgetCache();

        return $news->fresh();
    }

    public function unpublish(int $id): NewsItem
    {
        $news = $this->findOrFail($id);
        $news->update(['status' => NewsStatus::Draft]);

        $this->forgetCache();

        return $news->fresh();
    }

    public function toggleFeatured(int $id): NewsItem
    {
        $news = $this->findOrFail($id);
        $news->update(['is_featured' => ! $news->is_featured]);

        $this->forgetCache();

        return $news->fresh();
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views_count');
    }

    public function getPublished(?string $search = null, ?string $category = null, ?string $filter = null): LengthAwarePaginator
    {
        $query = $this->model->published();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($filter === 'featured') {
            $query->featured();
        }

        return $query->orderBy('is_featured', 'desc')
            ->orderBy('publish_at', 'desc')
            ->paginate(12);
    }

    public function getFeatured(): Collection
    {
        $cached = Cache::remember('news_featured_v2', 3600, function (): array {
            return $this->model->published()
                ->featured()
                ->orderBy('publish_at', 'desc')
                ->take(5)
                ->get()
                ->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    public function getLatest(int $limit = 5): Collection
    {
        $cached = Cache::remember('news_latest_v2', 3600, function () use ($limit): array {
            return $this->model->published()
                ->orderBy('publish_at', 'desc')
                ->take($limit)
                ->get()
                ->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    /**
     * Reconstruct Eloquent models from cached arrays.
     */
    private function hydrateCollection(array $rows): Collection
    {
        $models = new \Illuminate\Database\Eloquent\Collection;

        foreach ($rows as $row) {
            $model = new NewsItem;
            $model->forceFill($row);
            $model->exists = true;
            $models->push($model);
        }

        return $models;
    }

    private function findOrFail(int $id): NewsItem
    {
        $news = $this->model->find($id);

        if (! $news) {
            throw new ModelNotFoundException("News item with ID {$id} not found.");
        }

        return $news;
    }

    private function forgetCache(): void
    {
        Cache::forget('news_featured_v2');
        Cache::forget('news_latest_v2');
        Cache::forget('news_featured_v1');
        Cache::forget('news_latest_v1');
        Cache::forget('homepage.public.data');
    }
}
