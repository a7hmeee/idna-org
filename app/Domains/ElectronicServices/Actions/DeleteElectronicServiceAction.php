<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Actions;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;

final readonly class DeleteElectronicServiceAction
{
    public function __construct(
        private ElectronicServiceRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
