<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\WaterAreaData;
use App\Domains\Chatbot\DTOs\WaterScheduleData;

interface WaterScheduleQueryInterface
{
    public function getPublishedAreas(): array;

    public function searchAreas(string $query, int $limit = 5): array;

    public function getCurrentScheduleForArea(int $areaId): ?WaterScheduleData;

    public function getLatestScheduleForArea(int $areaId): ?WaterScheduleData;

    public function getNextScheduleForArea(int $areaId): ?WaterScheduleData;

    public function getTodaySchedules(): array;

    public function findAreaByName(string $name): ?WaterAreaData;
}
