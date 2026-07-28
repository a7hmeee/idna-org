<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Repositories;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentServiceCategoryRepository implements ServiceCategoryRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = ServiceCategory::withCount('services')->orderBy($sortField, $sortDirection);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?ServiceCategory
    {
        return ServiceCategory::withCount('services')->find($id);
    }

    public function findBySlug(string $slug): ?ServiceCategory
    {
        return ServiceCategory::where('slug', $slug)
            ->withCount('services')
            ->first();
    }

    public function create(array $data): ServiceCategory
    {
        return DB::transaction(function () use ($data): ServiceCategory {
            return ServiceCategory::create($data);
        });
    }

    public function update(int $id, array $data): ServiceCategory
    {
        return DB::transaction(function () use ($id, $data): ServiceCategory {
            $category = ServiceCategory::findOrFail($id);
            $category->update($data);

            return $category->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return ServiceCategory::findOrFail($id)->delete();
        });
    }

    public function togglePublic(int $id): ServiceCategory
    {
        return DB::transaction(function () use ($id): ServiceCategory {
            $category = ServiceCategory::findOrFail($id);
            $category->update(['is_public' => !$category->is_public]);

            return $category->fresh();
        });
    }

    public function reorder(array $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            foreach ($ids as $index => $id) {
                ServiceCategory::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return true;
        });
    }

    public function getPublicCategories(): Collection
    {
        return ServiceCategory::where('is_public', true)
            ->where('status', 'active')
            ->withCount('services')
            ->orderBy('sort_order')
            ->get();
    }

    public function getRootPublicCategories(): Collection
    {
        return ServiceCategory::whereNull('parent_id')
            ->where('is_public', true)
            ->where('status', 'active')
            ->withCount('services')
            ->orderBy('sort_order')
            ->get();
    }

    public function getChildren(int $parentId): Collection
    {
        return ServiceCategory::where('parent_id', $parentId)
            ->where('is_public', true)
            ->where('status', 'active')
            ->withCount('services')
            ->orderBy('sort_order')
            ->get();
    }
}
