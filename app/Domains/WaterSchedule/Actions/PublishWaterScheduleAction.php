<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;

final readonly class PublishWaterScheduleAction
{
    public function __construct(
        private WaterScheduleRepositoryInterface $repository,
    ) {}

    public function execute(string $date, ?int $userId = null): void
    {
        $this->repository->publishToday($date, $userId);
    }
}
