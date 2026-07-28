<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Actions;

use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;

final readonly class DeleteWaterAreaAction
{
    public function __construct(
        private WaterAreaRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
