<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;
use App\Domains\PublicFacilities\DTOs\FacilityCategoryData;
use App\Domains\PublicFacilities\Models\FacilityCategory;

final readonly class UpdateFacilityCategoryAction
{
    public function __construct(
        private FacilityCategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id, FacilityCategoryData $dto): FacilityCategory
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
