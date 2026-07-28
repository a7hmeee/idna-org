<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Actions;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;

final readonly class DeleteEngineeringOfficeAction
{
    public function __construct(
        private EngineeringOfficeRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
