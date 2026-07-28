<?php

declare(strict_types=1);

namespace App\Domains\Department\Actions;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\DTOs\DepartmentDTO;
use App\Domains\Department\Models\Department;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class UpdateDepartmentAction
{
    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function execute(int $id, DepartmentDTO $dto): Department
    {
        $department = $this->repository->update($id, $dto->toArray());

        MunicipalityUpdated::dispatch('departments');

        return $department;
    }
}
