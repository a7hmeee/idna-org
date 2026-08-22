<?php

declare(strict_types=1);

namespace App\Domains\Municipality\Repositories;

use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Models\CouncilMember;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentCouncilMemberRepository implements CouncilMemberRepositoryInterface
{
    public function paginateDashboard(?string $search = null, ?string $status = null, ?string $position = null, ?bool $isFeatured = null, ?bool $isPublic = null, string $sortField = 'display_order', string $sortDirection = 'asc', int $perPage = 15): LengthAwarePaginator
    {
        $query = CouncilMember::orderBy($sortField, $sortDirection);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('committee', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($position) {
            $query->where('position', $position);
        }

        if ($isFeatured !== null) {
            $query->where('is_featured', $isFeatured);
        }

        if ($isPublic !== null) {
            $query->where('is_public', $isPublic);
        }

        return $query->paginate($perPage);
    }

    public function find(int $id): ?CouncilMember
    {
        return CouncilMember::find($id);
    }

    public function findBySlug(string $slug): ?CouncilMember
    {
        return CouncilMember::where('slug', $slug)->first();
    }

    public function create(array $data): CouncilMember
    {
        return DB::transaction(function () use ($data): CouncilMember {
            return CouncilMember::create($data);
        });
    }

    public function update(int $id, array $data): CouncilMember
    {
        return DB::transaction(function () use ($id, $data): CouncilMember {
            $member = CouncilMember::findOrFail($id);
            $member->update($data);

            return $member->fresh();
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return CouncilMember::findOrFail($id)->delete();
        });
    }

    public function togglePublic(int $id): CouncilMember
    {
        return DB::transaction(function () use ($id): CouncilMember {
            $member = CouncilMember::findOrFail($id);
            $member->update(['is_public' => ! $member->is_public]);

            return $member->fresh();
        });
    }

    public function toggleFeatured(int $id): CouncilMember
    {
        return DB::transaction(function () use ($id): CouncilMember {
            $member = CouncilMember::findOrFail($id);
            $member->update(['is_featured' => ! $member->is_featured]);

            return $member->fresh();
        });
    }

    public function reorder(array $ids): bool
    {
        return DB::transaction(function () use ($ids): bool {
            foreach ($ids as $index => $id) {
                CouncilMember::where('id', $id)->update(['display_order' => $index + 1]);
            }

            return true;
        });
    }

    public function getPublicMembers(): Collection
    {
        return CouncilMember::where('is_public', true)
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();
    }

    public function getFeaturedMembers(): Collection
    {
        return CouncilMember::where('is_featured', true)
            ->where('status', 'active')
            ->orderBy('display_order')
            ->get();
    }

    public function getMayor(): ?CouncilMember
    {
        return CouncilMember::where('position', 'mayor')
            ->where('status', 'active')
            ->first();
    }

    public function getCouncilBoard(): Collection
    {
        return CouncilMember::whereIn('status', ['active', 'inactive'])
            ->where('is_public', true)
            ->orderBy('display_order')
            ->get();
    }

    public function paginatePublicMembers(?string $search = null, ?string $position = null, int $perPage = 12): LengthAwarePaginator
    {
        $query = CouncilMember::where('is_public', true)
            ->where('status', 'active')
            ->orderBy('display_order');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('committee', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%")
                    ->orWhere('bio', 'like', "%{$search}%")
                    ->orWhere('qualification', 'like', "%{$search}%");
            });
        }

        if ($position) {
            $query->where('position', $position);
        }

        return $query->paginate($perPage);
    }
}
