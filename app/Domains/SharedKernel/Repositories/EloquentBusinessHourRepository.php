<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Repositories;

use App\Domains\SharedKernel\Contracts\BusinessHourRepositoryInterface;
use App\Domains\SharedKernel\DTOs\BusinessHourDTO;
use App\Domains\SharedKernel\Models\BusinessHour;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class EloquentBusinessHourRepository implements BusinessHourRepositoryInterface
{
    public function getForModel(Model $model): Collection
    {
        return BusinessHour::where('hourable_type', $model->getMorphClass())
            ->where('hourable_id', $model->getKey())
            ->orderBy('display_order')
            ->get();
    }

    public function save(BusinessHourDTO $dto, ?int $id = null): BusinessHour
    {
        return DB::transaction(function () use ($dto, $id): BusinessHour {
            if ($id) {
                $businessHour = BusinessHour::findOrFail($id);
                $businessHour->update($dto->toArray());

                return $businessHour;
            }

            return BusinessHour::create($dto->toArray());
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            return BusinessHour::findOrFail($id)->delete();
        });
    }
}
