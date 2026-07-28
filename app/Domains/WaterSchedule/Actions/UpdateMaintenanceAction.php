<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\DTOs\WaterMaintenanceData;
use App\Domains\WaterSchedule\Models\WaterMaintenance;

final readonly class UpdateMaintenanceAction
{
    public function __construct(
        private WaterMaintenanceRepositoryInterface $repository,
    ) {}

    public function execute(int $id, WaterMaintenanceData $dto): WaterMaintenance
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
