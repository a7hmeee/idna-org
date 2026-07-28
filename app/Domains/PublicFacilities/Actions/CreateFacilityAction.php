<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\DTOs\FacilityData;
use App\Domains\PublicFacilities\Models\Facility;

final readonly class CreateFacilityAction
{
    public function __construct(
        private FacilityRepositoryInterface $repository,
    ) {}

    public function execute(FacilityData $dto): Facility
    {
        return $this->repository->create($dto->toArray());
    }
}
