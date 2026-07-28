<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Actions;

use App\Domains\Municipality\Events\MunicipalityUpdated;
use App\Domains\SharedKernel\Contracts\BusinessHourRepositoryInterface;
use App\Domains\SharedKernel\DTOs\BusinessHourDTO;
use App\Domains\SharedKernel\Models\BusinessHour;

final readonly class SaveBusinessHourAction
{
    public function __construct(
        private BusinessHourRepositoryInterface $repository,
    ) {}

    public function execute(BusinessHourDTO $dto, ?int $id = null): BusinessHour
    {
        $businessHour = $this->repository->save($dto, $id);

        MunicipalityUpdated::dispatch('business_hours');

        return $businessHour;
    }
}
