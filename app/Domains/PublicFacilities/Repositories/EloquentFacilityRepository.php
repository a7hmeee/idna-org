<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Repositories;

use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\Enums\FacilityStatus;
use App\Domains\PublicFacilities\Models\Facility;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentFacilityRepository implements FacilityRepositoryInterface
{
    public function __construct(
        private Facility $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['category', 'creator', 'updater'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?Facility
    {
        return $this->model->with(['category', 'creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?Facility
    {
        return $this->model
            ->with(['category'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Facility
    {
        $facility = $this->model->create($data);

        $this->forgetCache();

        return $facility;
    }

    public function update(int $id, array $data): Facility
    {
        $facility = $this->model->findOrFail($id);
        $facility->update($data);

        $this->forgetCache();

        return $facility->fresh()->load(['category', 'creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function publish(int $id): Facility
    {
        $facility = $this->model->findOrFail($id);
        $facility->update([
            'status' => FacilityStatus::Published,
            'is_public' => true,
        ]);

        $this->forgetCache();

        return $facility->fresh();
    }

    public function archive(int $id): Facility
    {
        $facility = $this->model->findOrFail($id);
        $facility->update(['status' => FacilityStatus::Archived]);

        $this->forgetCache();

        return $facility->fresh();
    }

    public function toggleFeatured(int $id): Facility
    {
        $facility = $this->model->findOrFail($id);
        $facility->update(['is_featured' => !$facility->is_featured]);

        $this->forgetCache();

        return $facility->fresh();
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views_count');
    }

    public function getPublished(?string $search = null, ?string $filter = null): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['category'])
            ->published();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        return $query
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(12);
    }

    public function getFeatured(): Collection
    {
        return $this->model
            ->with(['category'])
            ->published()
            ->where('is_featured', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->take(5)
            ->get();
    }

    public function getLatest(int $limit = 5): Collection
    {
        return $this->model
            ->with(['category'])
            ->published()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    private function forgetCache(): void
    {
        Cache::forget('public_facilities_featured_v3');
        Cache::forget('public_facilities_latest_v3');
        Cache::forget('homepage.public.data');
    }
}
