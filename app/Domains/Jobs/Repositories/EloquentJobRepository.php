<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Repositories;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\Enums\JobStatus;
use App\Domains\Jobs\Models\Job;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentJobRepository implements JobRepositoryInterface
{
    public function __construct(
        private Job $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['department', 'creator', 'updater'])
            ->orderBy('publish_at', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?Job
    {
        return $this->model->with(['department', 'creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?Job
    {
        return $this->model
            ->with(['department'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Job
    {
        $job = $this->model->create($data);

        $this->forgetCache();

        return $job;
    }

    public function update(int $id, array $data): Job
    {
        $job = $this->model->findOrFail($id);
        $job->update($data);

        $this->forgetCache();

        return $job->fresh()->load(['department', 'creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function publish(int $id): Job
    {
        $job = $this->model->findOrFail($id);
        $job->update([
            'status' => JobStatus::Published,
            'is_public' => true,
            'publish_at' => $job->publish_at ?? now()->toDateString(),
        ]);

        $this->forgetCache();

        return $job->fresh();
    }

    public function archive(int $id): Job
    {
        $job = $this->model->findOrFail($id);
        $job->update(['status' => JobStatus::Archived]);

        $this->forgetCache();

        return $job->fresh();
    }

    public function close(int $id): Job
    {
        $job = $this->model->findOrFail($id);
        $job->update(['status' => JobStatus::Closed]);

        $this->forgetCache();

        return $job->fresh();
    }

    public function toggleFeatured(int $id): Job
    {
        $job = $this->model->findOrFail($id);
        $job->update(['is_featured' => !$job->is_featured]);

        $this->forgetCache();

        return $job->fresh();
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views_count');
    }

    public function getPublished(?string $search = null, ?string $filter = null, ?int $departmentId = null): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['department'])
            ->published();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query
            ->orderBy('is_featured', 'desc')
            ->orderBy('publish_at', 'desc')
            ->paginate(12);
    }

    public function getFeatured(): Collection
    {
        return $this->model
            ->with(['department'])
            ->published()
            ->where('is_featured', true)
            ->orderBy('publish_at', 'desc')
            ->take(5)
            ->get();
    }

    public function getLatest(int $limit = 5): Collection
    {
        return $this->model
            ->with(['department'])
            ->published()
            ->orderBy('publish_at', 'desc')
            ->take($limit)
            ->get();
    }

    private function forgetCache(): void
    {
        Cache::forget('job_offers_featured_v3');
        Cache::forget('job_offers_latest_v3');
        Cache::forget('homepage.public.data');
    }
}
