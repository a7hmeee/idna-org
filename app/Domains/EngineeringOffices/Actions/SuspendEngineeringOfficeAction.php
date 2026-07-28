<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Actions;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;

final readonly class SuspendEngineeringOfficeAction
{
    public function __construct(
        private EngineeringOfficeRepositoryInterface $repository,
    ) {}

    public function execute(int $id): EngineeringOffice
    {
        return $this->repository->suspend($id);
    }
}
