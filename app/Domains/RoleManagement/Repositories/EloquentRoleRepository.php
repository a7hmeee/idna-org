<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Repositories;

use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class EloquentRoleRepository implements RoleRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null): LengthAwarePaginator
    {
        $query = Role::withCount('users')->with('permissions');

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return Role::withCount('users')->get();
    }

    public function findById(int $id): ?Role
    {
        return Role::with(['permissions', 'users'])->find($id);
    }

    public function findByName(string $name): ?Role
    {
        return Role::where('name', $name)->first();
    }

    public function create(array $data): Role
    {
        return DB::transaction(function () use ($data): Role {
            $role = Role::create(['name' => $data['name']]);

            if (!empty($data['permissions'])) {
                $role->givePermissionTo($data['permissions']);
            }

            return $role;
        });
    }

    public function update(int $id, array $data): Role
    {
        return DB::transaction(function () use ($id, $data): Role {
            $role = Role::findOrFail($id);
            $role->update(['name' => $data['name']]);

            if (array_key_exists('permissions', $data)) {
                $role->syncPermissions($data['permissions'] ?? []);
            }

            return $role;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            $role = Role::findOrFail($id);
            $role->permissions()->detach();
            $role->users()->detach();

            return $role->delete();
        });
    }

    public function syncPermissions(int $roleId, array $permissions): void
    {
        $role = Role::findOrFail($roleId);
        $role->syncPermissions($permissions);
    }

    public function countUsers(int $roleId): int
    {
        return DB::table('model_has_roles')
            ->where('role_id', $roleId)
            ->count();
    }

    public function getPermissionsGrouped(): array
    {
        $permissions = Permission::orderBy('name')->get();
        $grouped = [];

        foreach ($permissions as $permission) {
            $group = $permission->group ?? explode(' ', $permission->name, 2)[1] ?? 'other';
            $grouped[$group][] = $permission;
        }

        uksort($grouped, function ($a, $b) {
            $order = ['users', 'roles', 'departments', 'news', 'services', 'complaints', 'projects', 'tenders', 'jobs', 'settings', 'system'];
            $posA = array_search($a, $order);
            $posB = array_search($b, $order);
            if ($posA === false && $posB === false) return strcmp($a, $b);
            if ($posA === false) return 1;
            if ($posB === false) return -1;
            return $posA <=> $posB;
        });

        return $grouped;
    }
}
