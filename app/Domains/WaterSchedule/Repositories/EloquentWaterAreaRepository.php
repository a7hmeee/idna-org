<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Repositories;

use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterArea;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class EloquentWaterAreaRepository implements WaterAreaRepositoryInterface
{
    public function __construct(
        private WaterArea $model,
    ) {}

    public function paginateDashboard(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15);
    }

    public function find(int $id): ?WaterArea
    {
        return $this->model->find($id);
    }

    public function create(array $data): WaterArea
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): WaterArea
    {
        $area = $this->model->findOrFail($id);
        $area->update($data);

        return $area->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getActiveAreas(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();
    }

    public function toggleActive(int $id): WaterArea
    {
        $area = $this->model->findOrFail($id);
        $area->update(['is_active' => ! $area->is_active]);

        return $area->fresh();
    }

    public function reorder(array $orders): void
    {
        foreach ($orders as $id => $order) {
            $this->model->where('id', $id)->update(['display_order' => $order]);
        }
    }
}
