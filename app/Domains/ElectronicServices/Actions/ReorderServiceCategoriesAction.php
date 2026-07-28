<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;

final readonly class ReorderServiceCategoriesAction
{
    public function __construct(
        private ServiceCategoryRepositoryInterface $repository,
    ) {}

    public function execute(array $ids): bool
    {
        return $this->repository->reorder($ids);
    }
}
