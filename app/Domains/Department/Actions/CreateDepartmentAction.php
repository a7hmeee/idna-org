<?php

declare(strict_types=1);

namespace App\Domains\Department\Actions;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\DTOs\DepartmentDTO;
use App\Domains\Department\Models\Department;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class CreateDepartmentAction
{
    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function execute(DepartmentDTO $dto): Department
    {
        $department = $this->repository->create($dto->toArray());

        MunicipalityUpdated::dispatch('departments');

        return $department;
    }
}
