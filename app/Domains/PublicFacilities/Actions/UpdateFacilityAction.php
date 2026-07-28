<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\DTOs\FacilityData;
use App\Domains\PublicFacilities\Models\Facility;

final readonly class UpdateFacilityAction
{
    public function __construct(
        private FacilityRepositoryInterface $repository,
    ) {}

    public function execute(int $id, FacilityData $dto): Facility
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
