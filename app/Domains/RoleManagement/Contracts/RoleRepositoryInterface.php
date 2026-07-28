<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

interface RoleRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator;

    public function all(): \Illuminate\Database\Eloquent\Collection;

    public function findById(int $id): ?Role;

    public function findByName(string $name): ?Role;

    public function create(array $data): Role;

    public function update(int $id, array $data): Role;

    public function delete(int $id): bool;

    public function syncPermissions(int $roleId, array $permissions): void;

    public function countUsers(int $roleId): int;

    /**
     * @return array<string, array<int, \Spatie\Permission\Models\Permission>>
     */
    public function getPermissionsGrouped(): array;
}
