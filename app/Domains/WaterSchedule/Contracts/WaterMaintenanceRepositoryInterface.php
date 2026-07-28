<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Contracts;

use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WaterMaintenanceRepositoryInterface
{
    public function getActiveMaintenance(): ?WaterMaintenance;

    public function getUpcomingMaintenance(): Collection;

    public function paginate(): LengthAwarePaginator;

    public function find(int $id): ?WaterMaintenance;

    public function create(array $data): WaterMaintenance;

    public function update(int $id, array $data): WaterMaintenance;

    public function delete(int $id): bool;
}
