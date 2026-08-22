<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Repositories;

use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\Enums\TenderStatus;
use App\Domains\Tenders\Models\Tender;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentTenderRepository implements TenderRepositoryInterface
{
    public function __construct(
        private Tender $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderBy('publication_date', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?Tender
    {
        return $this->model->with(['creator', 'updater'])->find($id);
    }

    public function findBySlug(string $slug): ?Tender
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Tender
    {
        $tender = $this->model->create($data);

        $this->forgetCache();

        return $tender;
    }

    public function update(int $id, array $data): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update($data);

        $this->forgetCache();

        return $tender->fresh()->load(['creator', 'updater']);
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function publish(int $id): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update([
            'status' => TenderStatus::Open,
            'is_public' => true,
            'publication_date' => $tender->publication_date ?? now()->toDateString(),
        ]);

        $this->forgetCache();

        return $tender->fresh();
    }

    public function award(int $id): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update(['status' => TenderStatus::Awarded]);

        $this->forgetCache();

        return $tender->fresh();
    }

    public function cancel(int $id): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update(['status' => TenderStatus::Cancelled]);

        $this->forgetCache();

        return $tender->fresh();
    }

    public function archive(int $id): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update(['status' => TenderStatus::Archived]);

        $this->forgetCache();

        return $tender->fresh();
    }

    public function toggleFeatured(int $id): Tender
    {
        $tender = $this->model->findOrFail($id);
        $tender->update(['is_featured' => ! $tender->is_featured]);

        $this->forgetCache();

        return $tender->fresh();
    }

    public function incrementViews(int $id): void
    {
        $this->model->where('id', $id)->increment('views_count');
    }

    public function getPublished(?string $search = null, ?string $filter = null): LengthAwarePaginator
    {
        $query = $this->model
            ->with(['creator', 'updater'])
            ->published();

        if ($search) {
            $query->where(function (Builder $q) use ($search): void {
                $q->where('title_ar', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('tender_number', 'like', "%{$search}%");
            });
        }

        if ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        return $query
            ->orderBy('is_featured', 'desc')
            ->orderBy('publication_date', 'desc')
            ->paginate(12);
    }

    public function getFeatured(): Collection
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->published()
            ->where('is_featured', true)
            ->orderBy('publication_date', 'desc')
            ->take(5)
            ->get();
    }

    public function getLatest(int $limit = 5): Collection
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->published()
            ->orderBy('publication_date', 'desc')
            ->take($limit)
            ->get();
    }

    private function forgetCache(): void
    {
        Cache::forget('tenders_featured_v3');
        Cache::forget('tenders_latest_v3');
        Cache::forget('homepage.public.data');
    }
}
