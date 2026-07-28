<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Repositories;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Homepage\Models\HomepageStatistic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentHomepageRepository implements HomepageRepositoryInterface
{
    public function getSettings(): HomepageSetting
    {
        return HomepageSetting::firstOrCreate([]);
    }

    public function updateSettings(array $data): HomepageSetting
    {
        $settings = $this->getSettings();
        $settings->update($data);

        return $settings->fresh();
    }

    public function getActiveSlides(): Collection
    {
        return HomepageSlide::where('page_key', 'home')
            ->where('is_active', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->orderBy('sort_order')
            ->get();
    }

    public function getPageSlides(string $pageKey): Collection
    {
        return HomepageSlide::forPage($pageKey)
            ->active()
            ->currentlyVisible()
            ->ordered()
            ->get();
    }

    public function paginateSlides(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return HomepageSlide::when($search, fn (Builder $q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status === 'active', fn (Builder $q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn (Builder $q) => $q->where('is_active', false))
            ->orderBy('sort_order')
            ->paginate(10);
    }

    public function paginatePageSlides(string $pageKey, ?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return HomepageSlide::forPage($pageKey)
            ->when($search, fn (Builder $q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status === 'active', fn (Builder $q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn (Builder $q) => $q->where('is_active', false))
            ->ordered()
            ->paginate(10);
    }

    public function findSlide(int $id): ?HomepageSlide
    {
        return HomepageSlide::find($id);
    }

    public function createSlide(array $data): HomepageSlide
    {
        return DB::transaction(function () use ($data): HomepageSlide {
            if (!isset($data['sort_order'])) {
                $data['sort_order'] = HomepageSlide::max('sort_order') + 1;
            }

            return HomepageSlide::create($data);
        });
    }

    public function updateSlide(int $id, array $data): HomepageSlide
    {
        return DB::transaction(function () use ($id, $data): HomepageSlide {
            $slide = HomepageSlide::findOrFail($id);
            $slide->update($data);

            return $slide->fresh();
        });
    }

    public function deleteSlide(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            $slide = HomepageSlide::findOrFail($id);

            if ($slide->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($slide->image_path);
            }

            if ($slide->mobile_image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->mobile_image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($slide->mobile_image_path);
            }

            return (bool) $slide->delete();
        });
    }

    public function toggleSlide(int $id): HomepageSlide
    {
        $slide = HomepageSlide::findOrFail($id);
        $slide->update(['is_active' => !$slide->is_active]);

        return $slide->fresh();
    }

    public function reorderSlides(array $orders): void
    {
        DB::transaction(function () use ($orders): void {
            foreach ($orders as $order) {
                HomepageSlide::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }
        });
    }

    public function getSections(): Collection
    {
        return HomepageSection::orderBy('sort_order')->get();
    }

    public function getEnabledSections(): Collection
    {
        return HomepageSection::where('is_enabled', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function updateSection(string $key, array $data): HomepageSection
    {
        $section = HomepageSection::where('key', $key)->firstOrFail();
        $section->update($data);

        return $section->fresh();
    }

    public function reorderSections(array $orders): void
    {
        DB::transaction(function () use ($orders): void {
            foreach ($orders as $order) {
                HomepageSection::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }
        });
    }

    public function getQuickLinks(): Collection
    {
        return HomepageQuickLink::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function paginateQuickLinks(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return HomepageQuickLink::when($search, fn (Builder $q) => $q->where('title', 'like', "%{$search}%"))
            ->when($status === 'active', fn (Builder $q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn (Builder $q) => $q->where('is_active', false))
            ->orderBy('sort_order')
            ->paginate(10);
    }

    public function findQuickLink(int $id): ?HomepageQuickLink
    {
        return HomepageQuickLink::find($id);
    }

    public function createQuickLink(array $data): HomepageQuickLink
    {
        return DB::transaction(function () use ($data): HomepageQuickLink {
            if (!isset($data['sort_order'])) {
                $data['sort_order'] = HomepageQuickLink::max('sort_order') + 1;
            }

            return HomepageQuickLink::create($data);
        });
    }

    public function updateQuickLink(int $id, array $data): HomepageQuickLink
    {
        return DB::transaction(function () use ($id, $data): HomepageQuickLink {
            $link = HomepageQuickLink::findOrFail($id);
            $link->update($data);

            return $link->fresh();
        });
    }

    public function deleteQuickLink(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return (bool) HomepageQuickLink::findOrFail($id)->delete();
        });
    }

    public function toggleQuickLink(int $id): HomepageQuickLink
    {
        $link = HomepageQuickLink::findOrFail($id);
        $link->update(['is_active' => !$link->is_active]);

        return $link->fresh();
    }

    public function reorderQuickLinks(array $orders): void
    {
        DB::transaction(function () use ($orders): void {
            foreach ($orders as $order) {
                HomepageQuickLink::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }
        });
    }

    public function getStatistics(): Collection
    {
        return HomepageStatistic::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function paginateStatistics(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        return HomepageStatistic::when($search, fn (Builder $q) => $q->where('label', 'like', "%{$search}%"))
            ->when($status === 'active', fn (Builder $q) => $q->where('is_active', true))
            ->when($status === 'inactive', fn (Builder $q) => $q->where('is_active', false))
            ->orderBy('sort_order')
            ->paginate(10);
    }

    public function findStatistic(int $id): ?HomepageStatistic
    {
        return HomepageStatistic::find($id);
    }

    public function createStatistic(array $data): HomepageStatistic
    {
        return DB::transaction(function () use ($data): HomepageStatistic {
            if (!isset($data['sort_order'])) {
                $data['sort_order'] = HomepageStatistic::max('sort_order') + 1;
            }

            return HomepageStatistic::create($data);
        });
    }

    public function updateStatistic(int $id, array $data): HomepageStatistic
    {
        return DB::transaction(function () use ($id, $data): HomepageStatistic {
            $stat = HomepageStatistic::findOrFail($id);
            $stat->update($data);

            return $stat->fresh();
        });
    }

    public function deleteStatistic(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return (bool) HomepageStatistic::findOrFail($id)->delete();
        });
    }

    public function toggleStatistic(int $id): HomepageStatistic
    {
        $stat = HomepageStatistic::findOrFail($id);
        $stat->update(['is_active' => !$stat->is_active]);

        return $stat->fresh();
    }

    public function reorderStatistics(array $orders): void
    {
        DB::transaction(function () use ($orders): void {
            foreach ($orders as $order) {
                HomepageStatistic::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
            }
        });
    }
}
