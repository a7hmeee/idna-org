<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Models\ServiceCategory;

final readonly class ToggleServiceCategoryPublicAction
{
    public function __construct(
        private ServiceCategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ServiceCategory
    {
        return $this->repository->togglePublic($id);
    }
}
