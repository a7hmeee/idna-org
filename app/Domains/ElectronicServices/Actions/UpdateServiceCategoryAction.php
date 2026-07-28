<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\DTOs\ServiceCategoryData;
use App\Domains\ElectronicServices\Models\ServiceCategory;

final readonly class UpdateServiceCategoryAction
{
    public function __construct(
        private ServiceCategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id, ServiceCategoryData $dto): ServiceCategory
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
