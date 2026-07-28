<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\DTOs\WaterAreaData;
use App\Domains\WaterSchedule\Models\WaterArea;

final readonly class CreateWaterAreaAction
{
    public function __construct(
        private WaterAreaRepositoryInterface $repository,
    ) {}

    public function execute(WaterAreaData $dto): WaterArea
    {
        return $this->repository->create($dto->toArray());
    }
}
