<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Repositories;

use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\DTOs\MediaDTO;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
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
}
