<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Actions;

use App\Domains\Authentication\Models\User;
use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;
use App\Domains\UserManagement\DTOs\UpdateUserDTO;

final readonly class UpdateUserAction
{
    public function __construct(
        private UserManagementRepositoryInterface $repository,
    ) {}

    public function execute(int $userId, UpdateUserDTO $dto): User
    {
        return $this->repository->update($userId, $dto->toArray() + ['role' => $dto->role]);
    }
}
