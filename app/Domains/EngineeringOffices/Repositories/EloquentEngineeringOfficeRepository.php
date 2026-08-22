<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Repositories;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentEngineeringOfficeRepository implements EngineeringOfficeRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $approvalStatus = null, ?string $status = null): LengthAwarePaginator
    {
        $query = EngineeringOffice::query()->with(['creator:id,name', 'updater:id,name']);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('office_name', 'like', "%{$search}%")
                    ->orWhere('engineer_name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($approvalStatus) {
            $query->where('approval_status', $approvalStatus);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('sort_order')->orderBy('office_name')->paginate(15);
    }

    public function find(int $id): ?EngineeringOffice
    {
        return EngineeringOffice::with(['creator:id,name', 'updater:id,name'])->find($id);
    }

    public function findBySlug(string $slug): ?EngineeringOffice
    {
        return EngineeringOffice::with(['creator:id,name', 'updater:id,name'])->where('slug', $slug)->first();
    }

    public function create(array $data): EngineeringOffice
    {
        return DB::transaction(fn () => EngineeringOffice::create($data));
    }

    public function update(int $id, array $data): EngineeringOffice
    {
        return DB::transaction(function () use ($id, $data): EngineeringOffice {
            $office = EngineeringOffice::findOrFail($id);
            $office->update($data);

            return $office->fresh()->load(['creator:id,name', 'updater:id,name']);
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(fn () => EngineeringOffice::findOrFail($id)->delete());
    }

    public function approve(int $id): EngineeringOffice
    {
        return DB::transaction(function () use ($id): EngineeringOffice {
            $office = EngineeringOffice::findOrFail($id);
            $office->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'suspended_at' => null,
            ]);

            return $office->fresh();
        });
    }

    public function suspend(int $id): EngineeringOffice
    {
        return DB::transaction(function () use ($id): EngineeringOffice {
            $office = EngineeringOffice::findOrFail($id);
            $office->update([
                'approval_status' => 'suspended',
                'suspended_at' => now(),
            ]);

            return $office->fresh();
        });
    }

    public function markExpired(int $id): EngineeringOffice
    {
        return DB::transaction(function () use ($id): EngineeringOffice {
            $office = EngineeringOffice::findOrFail($id);
            $office->update([
                'approval_status' => 'expired',
            ]);

            return $office->fresh();
        });
    }

    public function togglePublic(int $id): EngineeringOffice
    {
        return DB::transaction(function () use ($id): EngineeringOffice {
            $office = EngineeringOffice::findOrFail($id);
            $office->update(['is_public' => ! $office->is_public]);

            return $office->fresh();
        });
    }

    public function reorder(array $orders): void
    {
        DB::transaction(function () use ($orders): void {
            foreach ($orders as $id => $sortOrder) {
                EngineeringOffice::where('id', $id)->update(['sort_order' => $sortOrder]);
            }
        });
    }

    public function getApprovedOffices(): iterable
    {
        return EngineeringOffice::where('approval_status', 'approved')
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('office_name')
            ->get();
    }

    public function getExpiredOffices(): iterable
    {
        return EngineeringOffice::where('approval_status', 'expired')
            ->orderBy('sort_order')
            ->orderBy('office_name')
            ->get();
    }

    public function getPublicOffices(?string $search = null, ?string $filter = null): LengthAwarePaginator
    {
        $query = EngineeringOffice::where('is_public', true);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('office_name', 'like', "%{$search}%")
                    ->orWhere('engineer_name', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($filter === 'featured') {
            $query->where('is_featured', true);
        }

        return $query->orderBy('sort_order')->orderBy('office_name')->paginate(12);
    }

    public function incrementViews(int $id): void
    {
        EngineeringOffice::where('id', $id)->increment('views');
    }

    public function getFeaturedPublicOffices(): iterable
    {
        return EngineeringOffice::where('is_public', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderBy('office_name')
            ->get();
    }
}
