<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Repositories;

use App\Domains\Authentication\Models\User;
use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class EloquentUserManagementRepository implements UserManagementRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null, ?string $role = null, ?string $status = null): LengthAwarePaginator
    {
        $query = User::with(['department', 'roles']);

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->role($role);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::with(['department', 'roles', 'permissions'])->find($id);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::create($data);

            if (! empty($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });
    }

    public function update(int $id, array $data): User
    {
        return DB::transaction(function () use ($id, $data): User {
            $user = User::findOrFail($id);

            $user->update($data);

            if (array_key_exists('role', $data)) {
                $user->syncRoles([$data['role']]);
            }

            return $user;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            $user = User::findOrFail($id);
            $user->roles()->detach();
            $user->permissions()->detach();

            return $user->delete();
        });
    }

    public function assignRole(int $userId, string $role): void
    {
        $user = User::findOrFail($userId);
        $user->syncRoles([$role]);
    }

    public function removeRole(int $userId, string $role): void
    {
        $user = User::findOrFail($userId);
        $user->removeRole($role);
    }

    public function syncPermissions(int $userId, array $permissions): void
    {
        $user = User::findOrFail($userId);
        $user->syncPermissions($permissions);
    }

    public function resetPassword(int $userId, string $password): void
    {
        User::where('id', $userId)->update([
            'password' => bcrypt($password),
        ]);
    }

    public function countByRole(string $role): int
    {
        return User::role($role)->count();
    }

    public function countActive(): int
    {
        return User::where('status', 'active')->count();
    }

    public function countInactive(): int
    {
        return User::where('status', 'inactive')->count();
    }
}
