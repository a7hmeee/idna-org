<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Contracts;

use App\Domains\WaterSchedule\Models\WaterArea;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WaterAreaRepositoryInterface
{
    public function paginateDashboard(): LengthAwarePaginator;

    public function find(int $id): ?WaterArea;

    public function create(array $data): WaterArea;

    public function update(int $id, array $data): WaterArea;

    public function delete(int $id): bool;

    public function getActiveAreas(): Collection;

    public function toggleActive(int $id): WaterArea;

    public function reorder(array $orders): void;
}
