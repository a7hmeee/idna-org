<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\RoleManagement\Support\PermissionSynchronizer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $registry = config('permissions');

        if (empty($registry)) {
            throw new \RuntimeException('Permission registry is empty. Check config/permissions.php');
        }

        $synchronizer = app(PermissionSynchronizer::class);
        $synchronizer->sync($registry);

        $allPermissionNames = $synchronizer->getRegisteredNames($registry);

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->syncPermissions($allPermissionNames);

        $admin = Role::findOrCreate('Admin');
        $admin->syncPermissions([
            'access panel', 'view users',
            'view roles', 'departments.view',
            'departments.create', 'departments.update',
            'news.view', 'news.create', 'news.update', 'news.publish', 'news.feature',
            'view services', 'create services', 'edit services',
            'complaints.view', 'complaints.create', 'complaints.update', 'complaints.assign', 'complaints.change_status', 'complaints.respond',
            'projects.view', 'projects.create', 'projects.update', 'projects.publish', 'projects.feature',
            'tenders.view', 'tenders.create', 'tenders.update', 'tenders.publish', 'tenders.archive',
            'jobs.view', 'view activity logs', 'view settings',
            'service_categories.view', 'service_categories.create', 'service_categories.update', 'service_categories.delete',
            'electronic_services.view', 'electronic_services.create', 'electronic_services.update', 'electronic_services.delete', 'electronic_services.publish',
            'engineering_offices.view', 'engineering_offices.create', 'engineering_offices.update', 'engineering_offices.delete', 'engineering_offices.approve', 'engineering_offices.suspend', 'engineering_offices.publish',
            'announcements.view', 'announcements.create', 'announcements.update', 'announcements.delete', 'announcements.publish',
            'open_data.view', 'open_data.create', 'open_data.update', 'open_data.delete', 'open_data.publish',
        ]);

        $departmentManager = Role::findOrCreate('Department Manager');
        $departmentManager->syncPermissions([
            'access panel', 'departments.view',
            'news.view', 'news.create',
            'view services', 'complaints.view', 'complaints.create', 'complaints.respond',
            'projects.view', 'jobs.view', 'announcements.view', 'tenders.view',
        ]);

        $employee = Role::findOrCreate('Employee');
        $employee->syncPermissions([
            'access panel', 'view services',
            'complaints.create', 'complaints.view',
            'news.view', 'announcements.view', 'projects.view', 'tenders.view',
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
