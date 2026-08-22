<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Services;

use App\Domains\Chatbot\Contracts\WaterScheduleQueryInterface;
use App\Domains\Chatbot\DTOs\WaterAreaData;
use App\Domains\Chatbot\DTOs\WaterScheduleData;
use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

final readonly class WaterScheduleQueryAdapter implements WaterScheduleQueryInterface
{
    private const AREAS_CACHE_KEY = 'chatbot:water-areas';

    private const SCHEDULE_CACHE_PREFIX = 'chatbot:water-schedule:';

    private const CACHE_TTL = 300;

    public function __construct(
        private WaterAreaRepositoryInterface $areaRepository,
        private WaterScheduleRepositoryInterface $scheduleRepository,
    ) {}

    public function getPublishedAreas(): array
    {
        $cached = Cache::remember(self::AREAS_CACHE_KEY, self::CACHE_TTL, function (): array {
            $areas = $this->areaRepository->getActiveAreas();

            return collect($areas)
                ->map(fn ($area) => [
                    'id' => (int) $area->id,
                    'name' => $area->name,
                    'slug' => $area->slug,
                    'description' => $area->description,
                    'isActive' => (bool) $area->is_active,
                ])
                ->values()
                ->all();
        });

        return array_map(function (array $data): WaterAreaData {
            return new WaterAreaData(
                id: $data['id'],
                name: $data['name'],
                slug: $data['slug'] ?? null,
                description: $data['description'] ?? null,
                isActive: $data['isActive'] ?? true,
            );
        }, $cached);
    }

    public function searchAreas(string $query, int $limit = 5): array
    {
        Cache::forget(self::AREAS_CACHE_KEY);

        $areas = $this->areaRepository->getActiveAreas();

        return collect($areas)
            ->filter(fn ($area) => str_contains(mb_strtolower($area->name), mb_strtolower($query)))
            ->take($limit)
            ->map(fn ($area) => new WaterAreaData(
                id: (int) $area->id,
                name: $area->name,
                slug: $area->slug,
                description: $area->description,
                isActive: true,
            ))
            ->values()
            ->all();
    }

    public function getCurrentScheduleForArea(int $areaId): ?WaterScheduleData
    {
        $today = now()->format('Y-m-d');
        $cacheKey = self::SCHEDULE_CACHE_PREFIX."area:{$areaId}:date:{$today}";

        $cached = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($areaId): ?array {
            $schedule = $this->scheduleRepository->getCurrentSchedule($areaId);

            if ($schedule === null) {
                return null;
            }

            $area = $this->areaRepository->find($areaId);

            return (new WaterScheduleData(
                id: (int) $schedule->id,
                areaId: (int) $schedule->water_area_id,
                areaName: $area?->name ?? 'غير معروفة',
                scheduleDate: $schedule->schedule_date instanceof Carbon
                    ? $schedule->schedule_date->format('Y-m-d')
                    : (string) $schedule->schedule_date,
                startTime: $schedule->start_time,
                endTime: $schedule->end_time,
                status: $schedule->status instanceof WaterScheduleStatus
                    ? $schedule->status->value
                    : (string) ($schedule->status ?? 'available'),
                notes: $schedule->notes,
            ))->toArray();
        });

        if ($cached === null) {
            return null;
        }

        return new WaterScheduleData(...$cached);
    }

    public function getLatestScheduleForArea(int $areaId): ?WaterScheduleData
    {
        $schedule = $this->scheduleRepository->getLatestScheduleForArea($areaId);

        if ($schedule === null) {
            return null;
        }

        $area = $this->areaRepository->find($areaId);

        return new WaterScheduleData(
            id: (int) $schedule->id,
            areaId: (int) $schedule->water_area_id,
            areaName: $area?->name ?? 'غير معروفة',
            scheduleDate: $schedule->schedule_date instanceof Carbon
                ? $schedule->schedule_date->format('Y-m-d')
                : (string) $schedule->schedule_date,
            startTime: $schedule->start_time,
            endTime: $schedule->end_time,
            status: $schedule->status instanceof WaterScheduleStatus
                ? $schedule->status->value
                : (string) ($schedule->status ?? 'available'),
            notes: $schedule->notes,
        );
    }

    public function getNextScheduleForArea(int $areaId): ?WaterScheduleData
    {
        $cacheKey = self::SCHEDULE_CACHE_PREFIX."area:{$areaId}:next";

        $cached = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($areaId): ?array {
            $schedule = $this->scheduleRepository->findByAreaAndDate(
                $areaId,
                now()->addDay()->format('Y-m-d'),
            );

            if ($schedule === null) {
                return null;
            }

            $area = $this->areaRepository->find($areaId);

            return (new WaterScheduleData(
                id: (int) $schedule->id,
                areaId: (int) $schedule->water_area_id,
                areaName: $area?->name ?? 'غير معروفة',
                scheduleDate: $schedule->schedule_date instanceof Carbon
                    ? $schedule->schedule_date->format('Y-m-d')
                    : (string) $schedule->schedule_date,
                startTime: $schedule->start_time,
                endTime: $schedule->end_time,
                status: $schedule->status instanceof WaterScheduleStatus
                    ? $schedule->status->value
                    : (string) ($schedule->status ?? 'available'),
                notes: $schedule->notes,
            ))->toArray();
        });

        if ($cached === null) {
            return null;
        }

        return new WaterScheduleData(...$cached);
    }

    public function getTodaySchedules(): array
    {
        $today = now()->format('Y-m-d');
        $cacheKey = self::SCHEDULE_CACHE_PREFIX."today:{$today}";

        $cached = Cache::remember($cacheKey, self::CACHE_TTL, function (): array {
            $schedules = $this->scheduleRepository->getToday();

            return collect($schedules)
                ->where('is_public', true)
                ->map(fn ($schedule) => (new WaterScheduleData(
                    id: (int) $schedule->id,
                    areaId: (int) $schedule->water_area_id,
                    areaName: $schedule->area?->name ?? 'غير معروفة',
                    scheduleDate: $schedule->schedule_date instanceof Carbon
                        ? $schedule->schedule_date->format('Y-m-d')
                        : (string) $schedule->schedule_date,
                    startTime: $schedule->start_time,
                    endTime: $schedule->end_time,
                    status: $schedule->status instanceof WaterScheduleStatus
                        ? $schedule->status->value
                        : (string) ($schedule->status ?? 'available'),
                    notes: $schedule->notes,
                ))->toArray())
                ->values()
                ->all();
        });

        return array_map(function (array $data): WaterScheduleData {
            return new WaterScheduleData(...$data);
        }, $cached);
    }

    public function findAreaByName(string $name): ?WaterAreaData
    {
        $areas = $this->getPublishedAreas();

        $match = collect($areas)->first(
            fn (WaterAreaData $area) => mb_strtolower($area->name) === mb_strtolower($name)
        );

        if ($match !== null) {
            return $match;
        }

        $partial = collect($areas)->first(
            fn (WaterAreaData $area) => str_contains(mb_strtolower($area->name), mb_strtolower($name))
                || str_contains(mb_strtolower($name), mb_strtolower($area->name))
        );

        return $partial;
    }
}
