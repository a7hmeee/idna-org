<?php

declare(strict_types=1);

namespace App\Domains\Department\Repositories;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\Models\Department;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentDepartmentRepository implements DepartmentRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?string $sortField = 'display_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = Department::orderBy($sortField, $sortDirection);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('manager_name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?Department
    {
        return Department::find($id);
    }

    public function findBySlug(string $slug): ?Department
    {
        return Department::where('slug', $slug)->first();
    }

    public function create(array $data): Department
    {
        return DB::transaction(function () use ($data): Department {
            return Department::create($data);
        });
    }

    public function update(int $id, array $data): Department
    {
        return DB::transaction(function () use ($id, $data): Department {
            $department = Department::findOrFail($id);
            $department->update($data);

            return $department->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return Department::findOrFail($id)->delete();
        });
    }

    public function togglePublic(int $id): Department
    {
        return DB::transaction(function () use ($id): Department {
            $department = Department::findOrFail($id);
            $department->update(['is_public' => ! $department->is_public]);

            return $department->fresh();
        });
    }

    public function toggleFeatured(int $id): Department
    {
        return DB::transaction(function () use ($id): Department {
            $department = Department::findOrFail($id);
            $department->update(['is_featured' => ! $department->is_featured]);

            return $department->fresh();
        });
    }

    public function reorder(array $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            foreach ($ids as $index => $id) {
                Department::where('id', $id)->update(['display_order' => $index + 1]);
            }

            return true;
        });
    }

    public function getPublicDepartments(): Collection
    {
        return Department::where('is_public', true)
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();
    }

    public function getFeaturedDepartments(): Collection
    {
        return Department::where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();
    }

    public function getActiveDepartments(): Collection
    {
        return Department::where('status', 'active')
            ->orderBy('display_order')
            ->get();
    }

    public function paginatePublicDepartments(?string $search = null, ?string $filter = null, int $perPage = 12): LengthAwarePaginator
    {
        $query = Department::where('is_public', true)
            ->where('status', 'active')
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('manager_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        return $query->paginate($perPage);
    }
}
