<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Repositories;

use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\DTOs\MediaDTO;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

final class EloquentMediaRepository implements MediaRepositoryInterface
{
    public function getForModel(Model $model, ?string $collection = null): Collection
    {
        $query = Media::where('mediable_type', $model->getMorphClass())
            ->where('mediable_id', $model->getKey())
            ->orderBy('display_order');

        if ($collection) {
            $query->where('collection', $collection);
        }

        return $query->get();
    }

    public function paginateForModel(Model $model, ?string $collection = null, int $perPage = 15): LengthAwarePaginator
    {
        $query = Media::where('mediable_type', $model->getMorphClass())
            ->where('mediable_id', $model->getKey())
            ->orderBy('display_order');

        if ($collection) {
            $query->where('collection', $collection);
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?Media
    {
        return Media::find($id);
    }

    public function save(MediaDTO $dto, ?int $id = null): Media
    {
        return DB::transaction(function () use ($dto, $id): Media {
            if ($id) {
                $media = Media::findOrFail($id);
                $media->update($dto->toArray());

                return $media;
            }

            return Media::create($dto->toArray());
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return Media::findOrFail($id)->delete();
        });
    }

    public function search(Model $model, array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Media::where('mediable_type', $model->getMorphClass())
            ->where('mediable_id', $model->getKey());

        // Search filter
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('path', 'like', "%{$search}%")
                    ->orWhere('alt', 'like', "%{$search}%")
                    ->orWhere('collection', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%");
            });
        }

        // Collection filter
        if (! empty($filters['collection'])) {
            $query->where('collection', $filters['collection']);
        }

        // Type filter (image, document, etc.)
        if (! empty($filters['type'])) {
            match ($filters['type']) {
                'image' => $query->where('mime_type', 'like', 'image/%'),
                'video' => $query->where('mime_type', 'like', 'video/%'),
                'document' => $query->where(function ($q) {
                    $q->where('mime_type', 'like', 'application/pdf')
                        ->orWhere('mime_type', 'like', 'application/msword')
                        ->orWhere('mime_type', 'like', 'application/vnd.openxmlformats-officedocument%');
                }),
                'other' => $query->where(function ($q) {
                    $q->whereNull('mime_type')
                        ->orWhere('mime_type', 'not like', 'image/%')
                        ->orWhere('mime_type', 'not like', 'video/%')
                        ->orWhere('mime_type', 'not like', 'application/pdf')
                        ->orWhere('mime_type', 'not like', 'application/msword')
                        ->orWhere('mime_type', 'not like', 'application/vnd.openxmlformats-officedocument%');
                }),
                default => null,
            };
        }

        // Status filter
        if (! empty($filters['status'])) {
            match ($filters['status']) {
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                default => null,
            };
        }

        // Sort
        $sortField = match ($filters['sort'] ?? null) {
            'name' => 'title',
            'size' => 'size',
            'created' => 'created_at',
            'collection' => 'collection',
            default => 'display_order',
        };
        $sortDirection = ($filters['sort'] ?? null) === 'created' ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDirection);

        // Usage filter (used / unused) — path-based scan computed after the other filters.
        if (! empty($filters['usage']) && in_array($filters['usage'], ['used', 'unused'], true)) {
            $all = $query->get();
            $wanted = $all->filter(
                fn (Media $m) => $filters['usage'] === 'used' ? $m->isUsed() : ! $m->isUsed()
            );

            $currentPage = Paginator::resolveCurrentPage();

            return new LengthAwarePaginator(
                $wanted->forPage($currentPage, $perPage)->values(),
                $wanted->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath()]
            );
        }

        return $query->paginate($perPage);
    }
}
