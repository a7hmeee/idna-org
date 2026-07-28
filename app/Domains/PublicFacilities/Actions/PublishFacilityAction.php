<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\Models\Facility;

final readonly class PublishFacilityAction
{
    public function __construct(
        private FacilityRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Facility
    {
        return $this->repository->publish($id);
    }
}
