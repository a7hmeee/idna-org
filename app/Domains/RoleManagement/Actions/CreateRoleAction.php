<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Actions;

use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use App\Domains\RoleManagement\DTOs\CreateRoleDTO;
use Spatie\Permission\Models\Role;

final readonly class CreateRoleAction
{
    public function __construct(
        private RoleRepositoryInterface $repository,
    ) {}

    public function execute(CreateRoleDTO $dto): Role
    {
        return $this->repository->create($dto->toArray());
    }
}
