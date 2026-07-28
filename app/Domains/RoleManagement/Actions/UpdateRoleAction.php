<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Actions;

use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use App\Domains\RoleManagement\DTOs\UpdateRoleDTO;
use Spatie\Permission\Models\Role;

final readonly class UpdateRoleAction
{
    public function __construct(
        private RoleRepositoryInterface $repository,
    ) {}

    public function execute(int $roleId, UpdateRoleDTO $dto): Role
    {
        return $this->repository->update($roleId, $dto->toArray());
    }
}
