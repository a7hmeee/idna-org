<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;

final readonly class RecordFacilityViewAction
{
    public function __construct(
        private FacilityRepositoryInterface $repository,
    ) {}

    public function execute(int $id): void
    {
        $this->repository->incrementViews($id);
    }
}
