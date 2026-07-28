<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Support;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

final class PermissionSynchronizer
{
    /**
     * Sync the permission registry to the database.
     * Idempotent: existing permissions are not duplicated, new ones are inserted.
     */
    public function sync(array $registry): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $registeredNames = [];

        DB::transaction(function () use ($registry, &$registeredNames): void {
            foreach ($registry as $module) {
                $group = $module['module'];
                $displayName = $module['display_name'];

                foreach ($module['permissions'] as $perm) {
                    $registeredNames[] = $perm['name'];

                    Permission::updateOrCreate(
                        ['name' => $perm['name'], 'guard_name' => 'web'],
                        [
                            'group' => $group,
                            'display_name' => $perm['display_name'],
                        ],
                    );
                }
            }
        });

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Get all registered permission names from the registry.
     */
    public function getRegisteredNames(array $registry): array
    {
        $names = [];
        foreach ($registry as $module) {
            foreach ($module['permissions'] as $perm) {
                $names[] = $perm['name'];
            }
        }
        return $names;
    }
}
