<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Actions;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;

final readonly class ReorderEngineeringOfficesAction
{
    public function __construct(
        private EngineeringOfficeRepositoryInterface $repository,
    ) {}

    public function execute(array $orders): void
    {
        $this->repository->reorder($orders);
    }
}
