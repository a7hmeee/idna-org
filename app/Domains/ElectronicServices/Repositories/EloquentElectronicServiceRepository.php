<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Repositories;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentElectronicServiceRepository implements ElectronicServiceRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?int $categoryId = null, ?int $departmentId = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = ElectronicService::with(['category:id,name,slug', 'department:id,name'])
            ->orderBy($sortField, $sortDirection);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('service_category_id', $categoryId);
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?ElectronicService
    {
        return ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])->find($id);
    }

    public function findBySlug(string $slug): ?ElectronicService
    {
        return ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): ElectronicService
    {
        return DB::transaction(function () use ($data): ElectronicService {
            return ElectronicService::create($data);
        });
    }

    public function update(int $id, array $data): ElectronicService
    {
        return DB::transaction(function () use ($id, $data): ElectronicService {
            $service = ElectronicService::findOrFail($id);
            $service->update($data);

            return $service->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return ElectronicService::findOrFail($id)->delete();
        });
    }

    public function publish(int $id): ElectronicService
    {
        return DB::transaction(function () use ($id): ElectronicService {
            $service = ElectronicService::findOrFail($id);
            $service->update([
                'status' => 'active',
                'published_at' => now(),
            ]);

            return $service->fresh();
        });
    }

    public function archive(int $id): ElectronicService
    {
        return DB::transaction(function () use ($id): ElectronicService {
            $service = ElectronicService::findOrFail($id);
            $service->update(['status' => 'archived']);

            return $service->fresh();
        });
    }

    public function togglePublic(int $id): ElectronicService
    {
        return DB::transaction(function () use ($id): ElectronicService {
            $service = ElectronicService::findOrFail($id);
            $service->update(['is_public' => ! $service->is_public]);

            return $service->fresh();
        });
    }

    public function toggleFeatured(int $id): ElectronicService
    {
        return DB::transaction(function () use ($id): ElectronicService {
            $service = ElectronicService::findOrFail($id);
            $service->update(['is_featured' => ! $service->is_featured]);

            return $service->fresh();
        });
    }

    public function incrementViews(int $id): void
    {
        ElectronicService::where('id', $id)->increment('views_count');
    }

    public function incrementPortalClicks(int $id): void
    {
        ElectronicService::where('id', $id)->increment('portal_clicks_count');
    }

    public function getPublicServices(): Collection
    {
        return ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }

    public function getFeaturedServices(): Collection
    {
        return ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('is_featured', true)
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }

    public function getByCategory(int $categoryId): Collection
    {
        return ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('service_category_id', $categoryId)
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();
    }

    public function getByCategoryPaginated(int $categoryId, ?string $search = null, ?string $departmentId = null, ?bool $requiresLogin = null, ?bool $isFeatured = null, string $sortField = 'sort_order', string $sortDirection = 'asc', int $perPage = 12): LengthAwarePaginator
    {
        $query = ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('service_category_id', $categoryId)
            ->where('is_public', true)
            ->where('status', 'active');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($requiresLogin !== null) {
            $query->where('requires_login', $requiresLogin);
        }

        if ($isFeatured !== null) {
            $query->where('is_featured', $isFeatured);
        }

        return $query->orderBy($sortField, $sortDirection)->paginate($perPage);
    }

    public function getRelatedServices(int $categoryId, int $excludeServiceId, int $limit = 3): Collection
    {
        return ElectronicService::with(['category:id,name,slug'])
            ->where('service_category_id', $categoryId)
            ->where('id', '!=', $excludeServiceId)
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }

    public function searchPublicServices(string $search, ?int $categoryId = null, int $perPage = 12, ?string $departmentSlug = null): LengthAwarePaginator
    {
        $query = ElectronicService::with(['category:id,name,slug', 'department:id,name,slug'])
            ->where('is_public', true)
            ->where('status', 'active');

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('service_category_id', $categoryId);
        }

        if ($departmentSlug) {
            $query->whereHas('department', function ($q) use ($departmentSlug): void {
                $q->where('slug', $departmentSlug);
            });
        }

        return $query->orderBy('sort_order')->paginate($perPage);
    }

    public function getMostViewed(int $limit = 10): Collection
    {
        return ElectronicService::with(['category:id,name', 'department:id,name'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get();
    }

    public function getMostClicked(int $limit = 10): Collection
    {
        return ElectronicService::with(['category:id,name', 'department:id,name'])
            ->where('is_public', true)
            ->where('status', 'active')
            ->orderByDesc('portal_clicks_count')
            ->limit($limit)
            ->get();
    }
}
