<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Central Permission Registry
    |--------------------------------------------------------------------------
    |
    | This is the single source of truth for all module permissions in the system.
    | Every module registers its permissions here. The seeder reads this config
    | and synchronizes to the database idempotently.
    |
    | To add a new module: add a new entry to this array.
    | Permissions will appear automatically in Roles, Users, and Permission Matrix.
    |
    | Structure:
    |   'module'        => Module identifier (used as permission group key in DB)
    |   'display_name'  => Human-readable module name shown in UI
    |   'permissions'   => Array of [name => permission_name, display_name => ...]
    |   'navigation'    => Optional: sidebar config (icon, route, order)
    |
    */

    [
        'module' => 'users',
        'display_name' => 'Users',
        'permissions' => [
            ['name' => 'view users', 'display_name' => 'View'],
            ['name' => 'create users', 'display_name' => 'Create'],
            ['name' => 'edit users', 'display_name' => 'Edit'],
            ['name' => 'delete users', 'display_name' => 'Delete'],
            ['name' => 'restore users', 'display_name' => 'Restore'],
            ['name' => 'export users', 'display_name' => 'Export'],
            ['name' => 'assign-role users', 'display_name' => 'Assign Role'],
            ['name' => 'assign-permission users', 'display_name' => 'Assign Permission'],
        ],
        'navigation' => [
            'icon' => 'users',
            'route' => 'dashboard.users',
            'order' => 1,
        ],
    ],

    [
        'module' => 'roles',
        'display_name' => 'Roles & Permissions',
        'permissions' => [
            ['name' => 'view roles', 'display_name' => 'View'],
            ['name' => 'create roles', 'display_name' => 'Create'],
            ['name' => 'edit roles', 'display_name' => 'Edit'],
            ['name' => 'delete roles', 'display_name' => 'Delete'],
            ['name' => 'assign roles', 'display_name' => 'Assign Roles'],
        ],
        'navigation' => [
            'icon' => 'shield',
            'route' => 'dashboard.roles',
            'order' => 2,
        ],
    ],

    [
        'module' => 'departments',
        'display_name' => 'Departments',
        'permissions' => [
            ['name' => 'departments.view', 'display_name' => 'View'],
            ['name' => 'departments.create', 'display_name' => 'Create'],
            ['name' => 'departments.update', 'display_name' => 'Update'],
            ['name' => 'departments.delete', 'display_name' => 'Delete'],
            ['name' => 'departments.publish', 'display_name' => 'Publish'],
            ['name' => 'departments.feature', 'display_name' => 'Feature'],
            ['name' => 'departments.reorder', 'display_name' => 'Reorder'],
        ],
    ],

    [
        'module' => 'news',
        'display_name' => 'News',
        'permissions' => [
            ['name' => 'news.view', 'display_name' => 'View'],
            ['name' => 'news.create', 'display_name' => 'Create'],
            ['name' => 'news.update', 'display_name' => 'Update'],
            ['name' => 'news.delete', 'display_name' => 'Delete'],
            ['name' => 'news.publish', 'display_name' => 'Publish'],
            ['name' => 'news.feature', 'display_name' => 'Feature'],
            // Legacy aliases for backward compatibility
            ['name' => 'view news', 'display_name' => 'View (Legacy)'],
            ['name' => 'create news', 'display_name' => 'Create (Legacy)'],
            ['name' => 'edit news', 'display_name' => 'Edit (Legacy)'],
            ['name' => 'delete news', 'display_name' => 'Delete (Legacy)'],
            ['name' => 'publish news', 'display_name' => 'Publish (Legacy)'],
        ],
        'navigation' => [
            'icon' => 'newspaper',
            'route' => 'dashboard.news',
            'order' => 4,
        ],
    ],

    [
        'module' => 'services',
        'display_name' => 'Services',
        'permissions' => [
            ['name' => 'view services', 'display_name' => 'View'],
            ['name' => 'create services', 'display_name' => 'Create'],
            ['name' => 'edit services', 'display_name' => 'Edit'],
            ['name' => 'delete services', 'display_name' => 'Delete'],
        ],
        'navigation' => [
            'icon' => 'briefcase',
            'route' => 'dashboard.services',
            'order' => 5,
        ],
    ],

    [
        'module' => 'complaints',
        'display_name' => 'Complaints',
        'permissions' => [
            ['name' => 'complaints.view', 'display_name' => 'View'],
            ['name' => 'complaints.create', 'display_name' => 'Create'],
            ['name' => 'complaints.update', 'display_name' => 'Update'],
            ['name' => 'complaints.delete', 'display_name' => 'Delete'],
            ['name' => 'complaints.assign', 'display_name' => 'Assign'],
            ['name' => 'complaints.change_status', 'display_name' => 'Change Status'],
            ['name' => 'complaints.respond', 'display_name' => 'Respond'],
            ['name' => 'complaints.export', 'display_name' => 'Export'],
            // Legacy aliases for backward compatibility
            ['name' => 'view complaints', 'display_name' => 'View (Legacy)'],
            ['name' => 'create complaints', 'display_name' => 'Create (Legacy)'],
            ['name' => 'edit complaints', 'display_name' => 'Edit (Legacy)'],
            ['name' => 'delete complaints', 'display_name' => 'Delete (Legacy)'],
            ['name' => 'reply complaints', 'display_name' => 'Reply (Legacy)'],
            ['name' => 'close complaints', 'display_name' => 'Close (Legacy)'],
        ],
        'navigation' => [
            'icon' => 'message-square',
            'route' => 'dashboard.complaints',
            'order' => 6,
        ],
    ],

    [
        'module' => 'projects',
        'display_name' => 'Projects',
        'permissions' => [
            ['name' => 'projects.view', 'display_name' => 'View'],
            ['name' => 'projects.create', 'display_name' => 'Create'],
            ['name' => 'projects.update', 'display_name' => 'Update'],
            ['name' => 'projects.delete', 'display_name' => 'Delete'],
            ['name' => 'projects.publish', 'display_name' => 'Publish'],
            ['name' => 'projects.feature', 'display_name' => 'Feature'],
            // Legacy aliases for backward compatibility
            ['name' => 'view projects', 'display_name' => 'View (Legacy)'],
            ['name' => 'create projects', 'display_name' => 'Create (Legacy)'],
            ['name' => 'edit projects', 'display_name' => 'Edit (Legacy)'],
            ['name' => 'delete projects', 'display_name' => 'Delete (Legacy)'],
            ['name' => 'approve projects', 'display_name' => 'Approve (Legacy)'],
        ],
        'navigation' => [
            'icon' => 'folder-kanban',
            'route' => 'dashboard.projects',
            'order' => 7,
        ],
    ],

    [
        'module' => 'tenders',
        'display_name' => 'Tenders',
        'permissions' => [
            ['name' => 'tenders.view', 'display_name' => 'View'],
            ['name' => 'tenders.create', 'display_name' => 'Create'],
            ['name' => 'tenders.update', 'display_name' => 'Update'],
            ['name' => 'tenders.delete', 'display_name' => 'Delete'],
            ['name' => 'tenders.publish', 'display_name' => 'Publish'],
            ['name' => 'tenders.archive', 'display_name' => 'Archive'],
            // Legacy aliases for backward compatibility
            ['name' => 'view tenders', 'display_name' => 'View (Legacy)'],
            ['name' => 'create tenders', 'display_name' => 'Create (Legacy)'],
            ['name' => 'edit tenders', 'display_name' => 'Edit (Legacy)'],
            ['name' => 'delete tenders', 'display_name' => 'Delete (Legacy)'],
            ['name' => 'award tenders', 'display_name' => 'Award (Legacy)'],
        ],
        'navigation' => [
            'icon' => 'scroll-text',
            'route' => 'dashboard.tenders',
            'order' => 8,
        ],
    ],

    [
        'module' => 'settings',
        'display_name' => 'Settings',
        'permissions' => [
            ['name' => 'view settings', 'display_name' => 'View'],
            ['name' => 'edit settings', 'display_name' => 'Edit'],
        ],
        'navigation' => [
            'icon' => 'settings',
            'route' => 'dashboard.settings',
            'order' => 10,
        ],
    ],

    [
        'module' => 'system',
        'display_name' => 'System',
        'permissions' => [
            ['name' => 'access panel', 'display_name' => 'Access Panel'],
            ['name' => 'view activity logs', 'display_name' => 'View Activity Logs'],
            ['name' => 'manage sessions', 'display_name' => 'Manage Sessions'],
        ],
        'navigation' => [
            'icon' => 'terminal',
            'route' => 'dashboard.system',
            'order' => 11,
        ],
    ],

    [
        'module' => 'service_categories',
        'display_name' => 'Service Categories',
        'permissions' => [
            ['name' => 'service_categories.view', 'display_name' => 'View'],
            ['name' => 'service_categories.create', 'display_name' => 'Create'],
            ['name' => 'service_categories.update', 'display_name' => 'Update'],
            ['name' => 'service_categories.delete', 'display_name' => 'Delete'],
            ['name' => 'service_categories.publish', 'display_name' => 'Publish'],
            ['name' => 'service_categories.reorder', 'display_name' => 'Reorder'],
        ],
    ],

    [
        'module' => 'engineering_offices',
        'display_name' => 'Engineering Offices',
        'permissions' => [
            ['name' => 'engineering_offices.view', 'display_name' => 'View'],
            ['name' => 'engineering_offices.create', 'display_name' => 'Create'],
            ['name' => 'engineering_offices.update', 'display_name' => 'Update'],
            ['name' => 'engineering_offices.delete', 'display_name' => 'Delete'],
            ['name' => 'engineering_offices.approve', 'display_name' => 'Approve'],
            ['name' => 'engineering_offices.suspend', 'display_name' => 'Suspend'],
            ['name' => 'engineering_offices.publish', 'display_name' => 'Publish'],
            ['name' => 'engineering_offices.reorder', 'display_name' => 'Reorder'],
        ],
    ],

    [
        'module' => 'electronic_services',
        'display_name' => 'Electronic Services',
        'permissions' => [
            ['name' => 'electronic_services.view', 'display_name' => 'View'],
            ['name' => 'electronic_services.create', 'display_name' => 'Create'],
            ['name' => 'electronic_services.update', 'display_name' => 'Update'],
            ['name' => 'electronic_services.delete', 'display_name' => 'Delete'],
            ['name' => 'electronic_services.publish', 'display_name' => 'Publish'],
            ['name' => 'electronic_services.feature', 'display_name' => 'Feature'],
            ['name' => 'electronic_services.analytics', 'display_name' => 'Analytics'],
        ],
    ],

    [
        'module' => 'homepage',
        'display_name' => 'Homepage',
        'permissions' => [
            ['name' => 'homepage.view', 'display_name' => 'View Homepage'],
            ['name' => 'homepage.update', 'display_name' => 'Update Settings'],

            ['name' => 'homepage.slides.view', 'display_name' => 'View Slides'],
            ['name' => 'homepage.slides.create', 'display_name' => 'Create Slide'],
            ['name' => 'homepage.slides.update', 'display_name' => 'Update Slide'],
            ['name' => 'homepage.slides.delete', 'display_name' => 'Delete Slide'],
            ['name' => 'homepage.slides.reorder', 'display_name' => 'Reorder Slides'],

            ['name' => 'homepage.sections.update', 'display_name' => 'Update Sections'],

            ['name' => 'homepage.quick_links.view', 'display_name' => 'View Quick Links'],
            ['name' => 'homepage.quick_links.create', 'display_name' => 'Create Quick Link'],
            ['name' => 'homepage.quick_links.update', 'display_name' => 'Update Quick Link'],
            ['name' => 'homepage.quick_links.delete', 'display_name' => 'Delete Quick Link'],
            ['name' => 'homepage.quick_links.reorder', 'display_name' => 'Reorder Quick Links'],

            ['name' => 'homepage.statistics.view', 'display_name' => 'View Statistics'],
            ['name' => 'homepage.statistics.create', 'display_name' => 'Create Statistic'],
            ['name' => 'homepage.statistics.update', 'display_name' => 'Update Statistic'],
            ['name' => 'homepage.statistics.delete', 'display_name' => 'Delete Statistic'],
            ['name' => 'homepage.statistics.reorder', 'display_name' => 'Reorder Statistics'],
        ],
    ],

    [
        'module' => 'page_carousels',
        'display_name' => 'Page Carousels',
        'permissions' => [
            ['name' => 'page-carousels.view', 'display_name' => 'View'],
            ['name' => 'page-carousels.create', 'display_name' => 'Create'],
            ['name' => 'page-carousels.update', 'display_name' => 'Update'],
            ['name' => 'page-carousels.delete', 'display_name' => 'Delete'],
            ['name' => 'page-carousels.reorder', 'display_name' => 'Reorder'],
            ['name' => 'page-carousels.publish', 'display_name' => 'Publish'],
        ],
    ],

    [
        'module' => 'water_schedule',
        'display_name' => 'Water Schedule',
        'permissions' => [
            ['name' => 'water.view', 'display_name' => 'View'],
            ['name' => 'water.create', 'display_name' => 'Create'],
            ['name' => 'water.update', 'display_name' => 'Update'],
            ['name' => 'water.delete', 'display_name' => 'Delete'],
            ['name' => 'water.publish', 'display_name' => 'Publish'],
        ],
    ],

    [
        'module' => 'jobs',
        'display_name' => 'Jobs',
        'permissions' => [
            ['name' => 'jobs.view', 'display_name' => 'View'],
            ['name' => 'jobs.create', 'display_name' => 'Create'],
            ['name' => 'jobs.update', 'display_name' => 'Update'],
            ['name' => 'jobs.delete', 'display_name' => 'Delete'],
            ['name' => 'jobs.publish', 'display_name' => 'Publish'],
            ['name' => 'jobs.archive', 'display_name' => 'Archive'],
        ],
    ],

    [
        'module' => 'announcements',
        'display_name' => 'Announcements',
        'permissions' => [
            ['name' => 'announcements.view', 'display_name' => 'View'],
            ['name' => 'announcements.create', 'display_name' => 'Create'],
            ['name' => 'announcements.update', 'display_name' => 'Update'],
            ['name' => 'announcements.delete', 'display_name' => 'Delete'],
            ['name' => 'announcements.publish', 'display_name' => 'Publish'],
            ['name' => 'announcements.reorder', 'display_name' => 'Reorder'],
        ],
    ],

    [
        'module' => 'public_facilities',
        'display_name' => 'Public Facilities',
        'permissions' => [
            ['name' => 'facility_categories.view', 'display_name' => 'View Categories'],
            ['name' => 'facility_categories.create', 'display_name' => 'Create Categories'],
            ['name' => 'facility_categories.update', 'display_name' => 'Update Categories'],
            ['name' => 'facility_categories.delete', 'display_name' => 'Delete Categories'],
            ['name' => 'facilities.view', 'display_name' => 'View Facilities'],
            ['name' => 'facilities.create', 'display_name' => 'Create Facilities'],
            ['name' => 'facilities.update', 'display_name' => 'Update Facilities'],
            ['name' => 'facilities.delete', 'display_name' => 'Delete Facilities'],
            ['name' => 'facilities.publish', 'display_name' => 'Publish Facilities'],
        ],
        'navigation' => [
            'icon' => 'building-2',
            'route' => 'dashboard.facilities',
            'order' => 12,
        ],
    ],

    [
        'module' => 'open_data',
        'display_name' => 'Open Data',
        'permissions' => [
            ['name' => 'open_data.view', 'display_name' => 'View'],
            ['name' => 'open_data.create', 'display_name' => 'Create'],
            ['name' => 'open_data.update', 'display_name' => 'Update'],
            ['name' => 'open_data.delete', 'display_name' => 'Delete'],
            ['name' => 'open_data.publish', 'display_name' => 'Publish'],
        ],
        'navigation' => [
            'icon' => 'file-text',
            'route' => 'dashboard.open-data',
            'order' => 14,
        ],
    ],

    [
        'module' => 'municipality',
        'display_name' => 'Municipality',
        'permissions' => [
            ['name' => 'municipality.view', 'display_name' => 'View'],
            ['name' => 'municipality.update', 'display_name' => 'Update'],

            ['name' => 'municipality.contacts.manage', 'display_name' => 'Manage Contacts'],
            ['name' => 'municipality.contacts.create', 'display_name' => 'Create Contact'],
            ['name' => 'municipality.contacts.update', 'display_name' => 'Update Contact'],
            ['name' => 'municipality.contacts.delete', 'display_name' => 'Delete Contact'],

            ['name' => 'municipality.social.manage', 'display_name' => 'Manage Social Platforms'],
            ['name' => 'municipality.social.create', 'display_name' => 'Create Social Platform'],
            ['name' => 'municipality.social.update', 'display_name' => 'Update Social Platform'],
            ['name' => 'municipality.social.delete', 'display_name' => 'Delete Social Platform'],

            ['name' => 'municipality.platforms.manage', 'display_name' => 'Manage External Platforms'],
            ['name' => 'municipality.platforms.create', 'display_name' => 'Create External Platform'],
            ['name' => 'municipality.platforms.update', 'display_name' => 'Update External Platform'],
            ['name' => 'municipality.platforms.delete', 'display_name' => 'Delete External Platform'],

            ['name' => 'municipality.custom-fields.manage', 'display_name' => 'Manage Custom Fields'],
            ['name' => 'municipality.custom-fields.create', 'display_name' => 'Create Custom Field'],
            ['name' => 'municipality.custom-fields.update', 'display_name' => 'Update Custom Field'],
            ['name' => 'municipality.custom-fields.delete', 'display_name' => 'Delete Custom Field'],

            ['name' => 'municipality.media.manage', 'display_name' => 'Manage Media'],
            ['name' => 'municipality.media.create', 'display_name' => 'Create Media'],
            ['name' => 'municipality.media.update', 'display_name' => 'Update Media'],
            ['name' => 'municipality.media.delete', 'display_name' => 'Delete Media'],

            ['name' => 'municipality.business-hours.manage', 'display_name' => 'Manage Business Hours'],
            ['name' => 'municipality.business-hours.update', 'display_name' => 'Update Business Hours'],

            ['name' => 'municipality.emergency-contacts.manage', 'display_name' => 'Manage Emergency Contacts'],
            ['name' => 'municipality.emergency-contacts.create', 'display_name' => 'Create Emergency Contact'],
            ['name' => 'municipality.emergency-contacts.update', 'display_name' => 'Update Emergency Contact'],
            ['name' => 'municipality.emergency-contacts.delete', 'display_name' => 'Delete Emergency Contact'],

            ['name' => 'council_decisions.view', 'display_name' => 'View Council Decisions'],
            ['name' => 'council_decisions.create', 'display_name' => 'Create Council Decision'],
            ['name' => 'council_decisions.update', 'display_name' => 'Update Council Decision'],
            ['name' => 'council_decisions.delete', 'display_name' => 'Delete Council Decision'],
            ['name' => 'council_decisions.publish', 'display_name' => 'Publish Council Decision'],
            ['name' => 'council_decisions.archive', 'display_name' => 'Archive Council Decision'],
            ['name' => 'council_decisions.cancel', 'display_name' => 'Cancel Council Decision'],

            ['name' => 'council_members.view', 'display_name' => 'View Council Members'],
            ['name' => 'council_members.create', 'display_name' => 'Create Council Member'],
            ['name' => 'council_members.update', 'display_name' => 'Update Council Member'],
            ['name' => 'council_members.delete', 'display_name' => 'Delete Council Member'],
            ['name' => 'council_members.toggle-public', 'display_name' => 'Toggle Public Visibility'],
            ['name' => 'council_members.toggle-featured', 'display_name' => 'Toggle Featured Status'],
            ['name' => 'council_members.reorder', 'display_name' => 'Reorder Council Members'],
        ],
    ],

    [
        'module' => 'chatbot',
        'display_name' => 'Chatbot',
        'permissions' => [
            ['name' => 'chatbot.view', 'display_name' => 'View Dashboard'],
            ['name' => 'chatbot.analytics', 'display_name' => 'Analytics'],
            ['name' => 'chatbot.search-terms', 'display_name' => 'Search Terms'],
            ['name' => 'chatbot.unknown-questions', 'display_name' => 'Unknown Questions'],
            ['name' => 'chatbot.performance', 'display_name' => 'Performance'],
        ],
        'navigation' => [
            'icon' => 'bot-message',
            'route' => 'dashboard.chatbot',
            'order' => 20,
        ],
    ],
];
