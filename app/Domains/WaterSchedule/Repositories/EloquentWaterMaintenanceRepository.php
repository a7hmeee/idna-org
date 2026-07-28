<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Repositories;

use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final readonly class EloquentWaterMaintenanceRepository implements WaterMaintenanceRepositoryInterface
{
    public function __construct(
        private WaterMaintenance $model,
    ) {}

    public function getActiveMaintenance(): ?WaterMaintenance
    {
        return $this->model
            ->where('status', 'active')
            ->where('is_public', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
    }

    public function getUpcomingMaintenance(): Collection
    {
        return $this->model
            ->where('status', 'active')
            ->where('is_public', true)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at')
            ->get();
    }

    public function paginate(): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'updater'])
            ->orderBy('starts_at', 'desc')
            ->paginate(15);
    }

    public function find(int $id): ?WaterMaintenance
    {
        return $this->model->find($id);
    }

    public function create(array $data): WaterMaintenance
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): WaterMaintenance
    {
        $maintenance = $this->model->findOrFail($id);
        $maintenance->update($data);

        return $maintenance->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
