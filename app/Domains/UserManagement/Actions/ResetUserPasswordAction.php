<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Actions;

use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;

final readonly class ResetUserPasswordAction
{
    public function __construct(
        private UserManagementRepositoryInterface $repository,
    ) {}

    public function execute(int $userId, string $password): void
    {
        $this->repository->resetPassword($userId, $password);
    }
}
