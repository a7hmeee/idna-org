<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;

final readonly class DeleteServiceCategoryAction
{
    public function __construct(
        private ServiceCategoryRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
