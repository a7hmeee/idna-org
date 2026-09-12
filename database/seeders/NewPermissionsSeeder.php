<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\RoleManagement\Support\PermissionSynchronizer;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

final class NewPermissionsSeeder extends Seeder
{
    private const NEW_MODULES = [
        'contact_requests',
        'login_activity',
        'navigation',
        'footer',
        'seo',
        'dashboard_notifications',
        'audit_log',
        'website_settings',
    ];

    public function run(): void
    {
        $registry = config('permissions');

        $newModules = array_filter(
            $registry,
            fn (array $module): bool => in_array($module['module'], self::NEW_MODULES, true)
        );

        $synchronizer = app(PermissionSynchronizer::class);
        $synchronizer->sync($newModules);

        $newPermissionNames = $synchronizer->getRegisteredNames($newModules);

        $chatbotNewPermissions = [
            'chatbot.manage',
            'chatbot.conversations.view',
            'chatbot.feedback.view',
            'chatbot.models.view',
            'chatbot.aliases.view',
        ];

        $allNewPermissions = array_merge($newPermissionNames, $chatbotNewPermissions);

        $superAdmin = Role::findOrCreate('Super Admin');
        $superAdmin->givePermissionTo($allNewPermissions);

        $admin = Role::findOrCreate('Admin');
        $admin->givePermissionTo([
            'contact_requests.view', 'contact_requests.resolve',
            'login_activity.view',
            'navigation.view', 'navigation.create', 'navigation.update', 'navigation.delete', 'navigation.reorder',
            'footer.view', 'footer.create', 'footer.update', 'footer.delete', 'footer.reorder',
            'seo.view', 'seo.update',
            'notifications.view', 'notifications.manage',
            'audit.view',
            'website_settings.view', 'website_settings.update',
            'chatbot.manage', 'chatbot.conversations.view', 'chatbot.feedback.view', 'chatbot.models.view', 'chatbot.aliases.view',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
