<?php

namespace App\Domains\WaterSchedule\Contracts;

use App\Domains\WaterSchedule\Models\WaterSchedule;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WaterScheduleRepositoryInterface
{
    public function getCurrentSchedule(int $areaId): ?WaterSchedule;

    public function getAreas(): Collection;

    public function findArea(int $id): ?WaterArea;

    public function getCurrentMaintenance(int $areaId): ?WaterMaintenance;

    public function getScheduleHistory(int $areaId): Collection;
}