<?php

declare(strict_types=1);

namespace App\Domains\Projects\Repositories;

use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentProjectRepository implements ProjectRepositoryInterface
{
    public function __construct(
        private Project $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderBy('display_order')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function find(int $id): ?Project
    {
        return $this->model->with(['creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?Project
    {
        return $this->model->with(['creator', 'updater'])->where('slug', $slug)->first();
    }

    public function create(array $data): Project
    {
        $project = $this->model->create($data);

        $this->forgetCache();

        return $project->load(['creator', 'updater']);
    }

    public function update(int $id, array $data): Project
    {
        $project = $this->findOrFail($id);
        $project->update($data);

        $this->forgetCache();

        return $project->fresh()->load(['creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        $project = $this->findOrFail($id);
        $deleted = (bool) $project->delete();

        $this->forgetCache();

        return $deleted;
    }

    public function publish(int $id): Project
    {
        $project = $this->findOrFail($id);
        $project->update([
            'status' => 'completed',
            'is_public' => true,
        ]);

        $this->forgetCache();

        return $project->fresh()->load(['creator', 'updater']);
    }

    public function unpublish(int $id): Project
    {
        $project = $this->findOrFail($id);
        $project->update(['status' => 'planned']);

        $this->forgetCache();

        return $project->fresh()->load(['creator', 'updater']);
    }

    public function toggleFeatured(int $id): Project
    {
        $project = $this->findOrFail($id);
        $project->update(['is_featured' => ! $project->is_featured]);

        $this->forgetCache();

        return $project->fresh()->load(['creator', 'updater']);
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views_count');
    }

    public function getPublished(?string $search = null, ?string $category = null, ?string $projectStatus = null): LengthAwarePaginator
    {
        $query = $this->model
            ->published()
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($projectStatus) {
            $query->where('project_status', $projectStatus);
        }

        return $query->paginate(12);
    }

    public function getFeatured(): Collection
    {
        $cached = Cache::remember('projects_featured_v2', 3600, function (): array {
            return $this->model
                ->published()
                ->featured()
                ->orderBy('display_order')
                ->take(5)
                ->get()
                ->toArray();
        });

        return $this->hydrateCollection($cached);
    }

    public function getLatest(int $limit = 5): Collection
    {
        $cached = Cache::remember('projects_latest_v2', 3600, function () use ($limit): array {
            return $this->model
                ->published()
                ->orderByDesc('created_at')
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
            $model = new Project;
            $model->forceFill($row);
            $model->exists = true;
            $models->push($model);
        }

        return $models;
    }

    public function getByCategory(Project $category): Collection
    {
        return $this->model
            ->published()
            ->where('category', $category)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getByProjectStatus(string $status): Collection
    {
        return $this->model
            ->published()
            ->where('project_status', $status)
            ->orderByDesc('created_at')
            ->get();
    }

    private function findOrFail(int $id): Project
    {
        $project = $this->model->find($id);

        if (! $project) {
            throw new ModelNotFoundException("Project with ID {$id} not found.");
        }

        return $project;
    }

    private function forgetCache(): void
    {
        Cache::forget('projects_featured_v2');
        Cache::forget('projects_latest_v2');
        Cache::forget('projects_featured_v1');
        Cache::forget('projects_latest_v1');
        Cache::forget('homepage.public.data');
    }
}
