<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;

final readonly class DeleteMaintenanceAction
{
    public function __construct(
        private WaterMaintenanceRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
