<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Contracts;

use App\Domains\Authentication\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserManagementRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $role = null, ?string $status = null): LengthAwarePaginator;

    public function findById(int $id): ?User;

    public function create(array $data): User;

    public function update(int $id, array $data): User;

    public function delete(int $id): bool;

    public function assignRole(int $userId, string $role): void;

    public function removeRole(int $userId, string $role): void;

    public function syncPermissions(int $userId, array $permissions): void;

    public function resetPassword(int $userId, string $password): void;

    public function countByRole(string $role): int;

    public function countActive(): int;

    public function countInactive(): int;
}
