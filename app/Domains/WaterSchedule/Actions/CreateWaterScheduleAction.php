<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\DTOs\WaterScheduleData;
use App\Domains\WaterSchedule\Models\WaterSchedule;

final readonly class CreateWaterScheduleAction
{
    public function __construct(
        private WaterScheduleRepositoryInterface $repository,
    ) {}

    public function execute(WaterScheduleData $dto): WaterSchedule
    {
        return $this->repository->upsert($dto->toArray());
    }
}
