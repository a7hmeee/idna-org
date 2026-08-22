<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Repositories;

use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final readonly class EloquentWaterScheduleRepository implements WaterScheduleRepositoryInterface
{
    public function __construct(
        private WaterSchedule $model,
        private WaterArea $area,
    ) {}

    public function getToday(): Collection
    {
        $today = now()->toDateString();

        return $this->model
            ->with(['area'])
            ->where('schedule_date', $today)
            ->where('is_public', true)
            ->orderBy('display_order')
            ->get();
    }

    public function getByDate(string $date): Collection
    {
        return $this->model
            ->with(['area', 'creator', 'updater'])
            ->where('schedule_date', $date)
            ->orderBy('display_order')
            ->get();
    }

    public function getLatestPublished(): Collection
    {
        $latestDate = $this->model
            ->where('is_public', true)
            ->where('schedule_date', '<', now()->toDateString())
            ->max('schedule_date');

        if (! $latestDate) {
            return collect();
        }

        return $this->model
            ->with(['area'])
            ->where('schedule_date', $latestDate)
            ->where('is_public', true)
            ->orderBy('display_order')
            ->get();
    }

    public function copyPreviousDay(string $date, ?int $userId = null): int
    {
        $previousDate = now()->parse($date)->subDay()->toDateString();

        $previousSchedules = $this->model
            ->where('schedule_date', $previousDate)
            ->get();

        if ($previousSchedules->isEmpty()) {
            return 0;
        }

        $count = 0;
        foreach ($previousSchedules as $schedule) {
            $this->model->updateOrCreate(
                [
                    'water_area_id' => $schedule->water_area_id,
                    'schedule_date' => $date,
                ],
                [
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'status' => $schedule->status,
                    'notes' => $schedule->notes,
                    'display_order' => $schedule->display_order,
                    'is_public' => true,
                    'created_by' => $userId,
                    'updated_by' => $userId,
                ]
            );
            $count++;
        }

        Cache::forget("water_schedule_today_{$date}");

        return $count;
    }

    public function publishToday(string $date, ?int $userId = null): void
    {
        $this->model
            ->where('schedule_date', $date)
            ->update([
                'is_public' => true,
                'updated_by' => $userId,
            ]);

        Cache::forget("water_schedule_today_{$date}");
    }

    public function upsert(array $data): WaterSchedule
    {
        $schedule = $this->model->updateOrCreate(
            [
                'water_area_id' => $data['water_area_id'],
                'schedule_date' => $data['schedule_date'],
            ],
            $data
        );

        Cache::forget("water_schedule_today_{$data['schedule_date']}");

        return $schedule->fresh()->load('area');
    }

    public function findByAreaAndDate(int $areaId, string $date): ?WaterSchedule
    {
        return $this->model
            ->where('water_area_id', $areaId)
            ->where('schedule_date', $date)
            ->first();
    }

    public function findArea(int $id): ?WaterArea
    {
        return $this->area->find($id);
    }

    public function getCurrentSchedule(int $areaId): ?WaterSchedule
    {
        return $this->model
            ->with(['area'])
            ->where('water_area_id', $areaId)
            ->where('schedule_date', now()->toDateString())
            ->where('is_public', true)
            ->first();
    }

    public function getLatestScheduleForArea(int $areaId): ?WaterSchedule
    {
        return $this->model
            ->with(['area'])
            ->where('water_area_id', $areaId)
            ->where('is_public', true)
            ->where('schedule_date', '<=', now()->toDateString())
            ->orderBy('schedule_date', 'desc')
            ->first();
    }

    public function getAreas(): Collection
    {
        return app(WaterAreaRepositoryInterface::class)->getActiveAreas();
    }

    public function getCurrentMaintenance(int $areaId): ?WaterMaintenance
    {
        return WaterMaintenance::where('status', 'active')
            ->where('is_public', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
    }

    public function getScheduleHistory(int $areaId): Collection
    {
        return $this->model
            ->with(['area'])
            ->where('water_area_id', $areaId)
            ->where('is_public', true)
            ->orderBy('schedule_date', 'desc')
            ->limit(30)
            ->get();
    }
}
