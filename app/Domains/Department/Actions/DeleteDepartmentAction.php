<?php

declare(strict_types=1);

namespace App\Domains\Department\Actions;

use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Municipality\Events\MunicipalityUpdated;

final readonly class DeleteDepartmentAction
{
    public function __construct(
        private DepartmentRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $result = $this->repository->delete($id);

        MunicipalityUpdated::dispatch('departments');

        return $result;
    }
}
