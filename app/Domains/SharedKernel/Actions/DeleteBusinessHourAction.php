<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\BusinessHourRepositoryInterface;

final readonly class DeleteBusinessHourAction
{
    public function __construct(
        private BusinessHourRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->delete($id);

        MunicipalityUpdated::dispatch('business_hours');

        return $result;
    }
}
