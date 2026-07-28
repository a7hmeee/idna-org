<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Actions;

use App\Domains\Authentication\Models\User;
use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;
use App\Domains\UserManagement\DTOs\CreateUserDTO;

final readonly class CreateUserAction
{
    public function __construct(
        private UserManagementRepositoryInterface $repository,
    ) {}

    public function execute(CreateUserDTO $dto): User
    {
        return $this->repository->create($dto->toArray() + ['role' => $dto->role]);
    }
}
