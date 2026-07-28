<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Actions;

use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;

final readonly class DeleteRoleAction
{
    public function __construct(
        private RoleRepositoryInterface $repository,
    ) {}

    public function execute(int $roleId): bool
    {
        return $this->repository->delete($roleId);
    }
}
