<?php

declare(strict_types=1);

namespace App\Domains\Department\Actions;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\Models\Department;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class ToggleDepartmentPublicAction
{
    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function execute(int $id): Department
    {
        $department = $this->repository->togglePublic($id);

        MunicipalityUpdated::dispatch('departments');

        return $department;
    }
}
