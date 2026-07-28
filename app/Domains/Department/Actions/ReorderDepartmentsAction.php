<?php

declare(strict_types=1);

namespace App\Domains\Department\Actions;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class ReorderDepartmentsAction
{
    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function execute(array $ids): bool
    {
        $result = $this->repository->reorder($ids);

        MunicipalityUpdated::dispatch('departments');

        return $result;
    }
}
