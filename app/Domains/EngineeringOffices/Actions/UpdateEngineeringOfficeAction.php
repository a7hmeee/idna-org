<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Actions;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\DTOs\EngineeringOfficeData;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;

final readonly class UpdateEngineeringOfficeAction
{
    public function __construct(
        private EngineeringOfficeRepositoryInterface $repository,
    ) {}

    public function execute(int $id, EngineeringOfficeData $data): EngineeringOffice
    {
        return $this->repository->update($id, $data->toArray());
    }
}
