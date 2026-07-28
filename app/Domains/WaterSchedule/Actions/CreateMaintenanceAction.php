<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\DTOs\WaterMaintenanceData;
use App\Domains\WaterSchedule\Models\WaterMaintenance;

final readonly class CreateMaintenanceAction
{
    public function __construct(
        private WaterMaintenanceRepositoryInterface $repository,
    ) {}

    public function execute(WaterMaintenanceData $dto): WaterMaintenance
    {
        return $this->repository->create($dto->toArray());
    }
}
