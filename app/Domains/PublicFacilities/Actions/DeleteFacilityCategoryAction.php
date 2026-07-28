<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Actions;

use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;

final readonly class DeleteFacilityCategoryAction
{
    public function __construct(
        private FacilityCategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
