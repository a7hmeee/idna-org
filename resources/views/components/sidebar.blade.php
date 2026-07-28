@php
    $user = auth()->user();
    $currentRoute = request()->route()?->getName();
    $userPermissions = $user ? $user->getAllPermissions()->pluck('name')->flip() : collect();

    $can = fn(string $permission): bool => $userPermissions->has($permission);

    $navGroups = [];

    // الرئيسية
    $navGroups['الرئيسية'] = [
        ['icon' => 'layout-dashboard', 'label' => 'لوحة التحكم', 'route' => 'dashboard', 'permission' => null, 'active' => $currentRoute === 'dashboard'],
    ];

    // إدارة الصفحة الرئيسية
    if ($can('homepage.view') || $can('homepage.update') || $can('homepage.slides.view') || $can('homepage.sections.update') || $can('homepage.quick_links.view') || $can('homepage.statistics.view')) {
        $navGroups['إدارة الصفحة الرئيسية'] = [];
    }

    if ($can('homepage.view')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'layout-dashboard', 'label' => 'لوحة الصفحة الرئيسية', 'route' => 'dashboard.homepage', 'permission' => 'homepage.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.homepage') && $currentRoute === 'dashboard.homepage',
        ];
    }

    if ($can('homepage.update')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'settings', 'label' => 'إعدادات الصفحة', 'route' => 'dashboard.homepage.settings', 'permission' => 'homepage.update', 'active' => $currentRoute === 'dashboard.homepage.settings',
        ];
    }

    if ($can('homepage.slides.view')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'images', 'label' => 'شرائح البنر', 'route' => 'dashboard.homepage.slides', 'permission' => 'homepage.slides.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.homepage.slides'),
        ];
    }

    if ($can('homepage.sections.update')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'layers', 'label' => 'أقسام الصفحة', 'route' => 'dashboard.homepage.sections', 'permission' => 'homepage.sections.update', 'active' => $currentRoute === 'dashboard.homepage.sections',
        ];
    }

    if ($can('homepage.quick_links.view')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'link', 'label' => 'الروابط السريعة', 'route' => 'dashboard.homepage.quick-links', 'permission' => 'homepage.quick_links.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.homepage.quick-links'),
        ];
    }

    if ($can('homepage.statistics.view')) {
        $navGroups['إدارة الصفحة الرئيسية'][] = [
            'icon' => 'bar-chart-3', 'label' => 'الإحصائيات', 'route' => 'dashboard.homepage.statistics', 'permission' => 'homepage.statistics.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.homepage.statistics'),
        ];
    }

    // إدارة المستخدمين (only if user can view users)
    if ($can('view users')) {
        $navGroups['إدارة المستخدمين'][] = [
            'icon' => 'users', 'label' => 'المستخدمين', 'route' => 'users.index', 'permission' => 'view users', 'active' => $currentRoute === 'users.index',
        ];
    }

    if ($can('view roles')) {
        $navGroups['إدارة المستخدمين'][] = [
            'icon' => 'shield-check', 'label' => 'الأدوار والصلاحيات', 'route' => 'roles.index', 'permission' => 'view roles', 'active' => $currentRoute === 'roles.index',
        ];
    }

    if ($can('departments.view')) {
        $navGroups['إدارة المستخدمين'][] = [
            'icon' => 'building-2', 'label' => 'الأقسام', 'route' => 'dashboard.departments', 'permission' => 'departments.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.departments'),
        ];
    }

    // بوابة الخدمات الإلكترونية
    if ($can('service_categories.view') || $can('electronic_services.view') || $can('electronic_services.analytics') || $can('engineering_offices.view')) {
        $navGroups['بوابة الخدمات الإلكترونية'] = [];
    }

    if ($can('engineering_offices.view')) {
        $navGroups['بوابة الخدمات الإلكترونية'][] = [
            'icon' => 'hard-hat', 'label' => 'المكاتب الهندسية', 'route' => 'dashboard.engineering-offices', 'permission' => 'engineering_offices.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.engineering-offices'),
        ];
    }

    if ($can('service_categories.view')) {
        $navGroups['بوابة الخدمات الإلكترونية'][] = [
            'icon' => 'folder-tree', 'label' => 'تصنيفات الخدمات', 'route' => 'dashboard.electronic-services.categories', 'permission' => 'service_categories.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.electronic-services.categories'),
        ];
    }

    if ($can('electronic_services.view')) {
        $navGroups['بوابة الخدمات الإلكترونية'][] = [
            'icon' => 'laptop', 'label' => 'الخدمات الإلكترونية', 'route' => 'dashboard.electronic-services.services', 'permission' => 'electronic_services.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.electronic-services.services'),
        ];
    }

    if ($can('electronic_services.analytics')) {
        $navGroups['بوابة الخدمات الإلكترونية'][] = [
            'icon' => 'bar-chart-3', 'label' => 'إحصائيات الخدمات', 'route' => 'dashboard.electronic-services.analytics', 'permission' => 'electronic_services.analytics', 'active' => $currentRoute === 'dashboard.electronic-services.analytics',
        ];
    }

    // المرافق العامة
    if ($can('facilities.view') || $can('facility_categories.view')) {
        $navGroups['المرافق العامة'] = [];
    }

    if ($can('facilities.view')) {
        $navGroups['المرافق العامة'][] = [
            'icon' => 'building-2',
            'label' => 'المرافق',
            'route' => 'dashboard.facilities',
            'permission' => 'facilities.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.facilities') && !str_contains($currentRoute ?? '', '.categories'),
        ];
    }

    if ($can('facility_categories.view')) {
        $navGroups['المرافق العامة'][] = [
            'icon' => 'folder-tree',
            'label' => 'تصنيفات المرافق',
            'route' => 'dashboard.facilities.categories',
            'permission' => 'facility_categories.view',
            'active' => str_contains($currentRoute ?? '', 'dashboard.facilities.categories'),
        ];
    }

    if ($can('news.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'newspaper',
            'label' => 'الأخبار',
            'route' => 'dashboard.news',
            'permission' => 'news.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.news'),
        ];
    }

    if ($can('projects.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'folder-kanban',
            'label' => 'المشاريع',
            'route' => 'dashboard.projects',
            'permission' => 'projects.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.projects'),
        ];
    }

    if ($can('jobs.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'briefcase',
            'label' => 'الوظائف',
            'route' => 'dashboard.jobs',
            'permission' => 'jobs.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.jobs'),
        ];
    }

    if ($can('tenders.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'scroll-text',
            'label' => 'المناقصات',
            'route' => 'dashboard.tenders',
            'permission' => 'tenders.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.tenders'),
        ];
    }

    if ($can('water.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'droplets',
            'label' => 'جدول توزيع المياه',
            'route' => 'dashboard.water-schedule',
            'permission' => 'water.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.water-schedule'),
        ];
    }

    if ($can('open_data.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'file-text',
            'label' => 'البيانات المفتوحة',
            'route' => 'dashboard.open-data',
            'permission' => 'open_data.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.open-data'),
        ];
    }

    if ($can('complaints.view')) {
        $navGroups['الخدمات'][] = [
            'icon' => 'message-square',
            'label' => 'الشكاوى',
            'route' => 'dashboard.complaints',
            'permission' => 'complaints.view',
            'active' => str_starts_with($currentRoute ?? '', 'dashboard.complaints'),
        ];
    }

    // البلدية
    if ($can('municipality.view')) {
        $navGroups['البلدية'][] = [
            'icon' => 'building-2', 'label' => 'لوحة البلدية', 'route' => 'dashboard.municipality.index', 'permission' => 'municipality.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.municipality'),
        ];
    }

    if ($can('municipality.update')) {
        $navGroups['البلدية'][] = [
            'icon' => 'info', 'label' => 'المعلومات العامة', 'route' => 'dashboard.municipality.general-info', 'permission' => 'municipality.update', 'active' => $currentRoute === 'dashboard.municipality.general-info',
        ];
    }

    if ($can('municipality.contacts.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'phone', 'label' => 'جهات الاتصال', 'route' => 'dashboard.municipality.contacts', 'permission' => 'municipality.contacts.manage', 'active' => $currentRoute === 'dashboard.municipality.contacts',
        ];
    }

    if ($can('municipality.social.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'share-2', 'label' => 'وسائل التواصل', 'route' => 'dashboard.municipality.social', 'permission' => 'municipality.social.manage', 'active' => $currentRoute === 'dashboard.municipality.social',
        ];
    }

    if ($can('municipality.platforms.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'globe', 'label' => 'المنصات الخارجية', 'route' => 'dashboard.municipality.platforms', 'permission' => 'municipality.platforms.manage', 'active' => $currentRoute === 'dashboard.municipality.platforms',
        ];
    }

    if ($can('municipality.custom-fields.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'list-checks', 'label' => 'الحقول المخصصة', 'route' => 'dashboard.municipality.custom-fields', 'permission' => 'municipality.custom-fields.manage', 'active' => $currentRoute === 'dashboard.municipality.custom-fields',
        ];
    }

    if ($can('municipality.media.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'image', 'label' => 'الوسائط', 'route' => 'dashboard.municipality.media', 'permission' => 'municipality.media.manage', 'active' => $currentRoute === 'dashboard.municipality.media',
        ];
    }

    if ($can('municipality.business-hours.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'clock', 'label' => 'ساعات الدوام', 'route' => 'dashboard.municipality.business-hours', 'permission' => 'municipality.business-hours.manage', 'active' => $currentRoute === 'dashboard.municipality.business-hours',
        ];
    }

    if ($can('municipality.emergency-contacts.manage')) {
        $navGroups['البلدية'][] = [
            'icon' => 'alert-triangle', 'label' => 'جهات الطوارئ', 'route' => 'dashboard.municipality.emergency-contacts', 'permission' => 'municipality.emergency-contacts.manage', 'active' => $currentRoute === 'dashboard.municipality.emergency-contacts',
        ];
    }

    if ($can('council_decisions.view')) {
        $navGroups['البلدية'][] = [
            'icon' => 'file-text', 'label' => 'قرارات المجلس البلدي', 'route' => 'dashboard.municipality.council-decisions', 'permission' => 'council_decisions.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.municipality.council-decisions'),
        ];
    }

    if ($can('council_members.view')) {
        $navGroups['البلدية'][] = [
            'icon' => 'users', 'label' => 'أعضاء المجلس البلدي', 'route' => 'dashboard.municipality.council-members', 'permission' => 'council_members.view', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.municipality.council-members'),
        ];
    }

    // Filter out empty groups
    $navGroups = array_filter($navGroups, fn ($items) => !empty($items));

    $bottomItems = [];

    $isActive = fn($item) => $item['active'] || ($item['route'] && $currentRoute === $item['route']);
@endphp

<aside
    :class="sidebarOpen ? 'w-[260px]' : 'w-[76px]'"
    class="fixed inset-y-4 right-4 z-40 sidebar-glass rounded-3xl flex flex-col transition-all duration-300 hidden lg:flex overflow-hidden"
>
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 h-[64px] shrink-0 border-b border-border/50 mx-3">
        <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center shrink-0 shadow-sm">
            <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-7 h-7 object-contain">
        </a>
        <div x-show="sidebarOpen" x-transition class="overflow-hidden whitespace-nowrap">
            <p class="text-primary font-bold text-sm leading-tight">بلدية إذنا</p>
            <p class="text-[9px] text-text-tertiary font-medium tracking-wide">Municipality Management System</p>
        </div>
    </div>

    {{-- Dashboard Button --}}
    <div class="px-3 pt-4 pb-1">
        <a
            href="{{ route('dashboard') }}"
            @class([
                'flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-sm transition-all',
                'bg-primary text-white shadow-sm' => $currentRoute === 'dashboard',
                'bg-primary/10 text-primary hover:bg-primary hover:text-white' => $currentRoute !== 'dashboard',
            ])
        >
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span x-show="sidebarOpen" x-transition>لوحة التحكم</span>
        </a>
    </div>

    {{-- Search --}}
    <div x-show="sidebarOpen" class="px-3 pt-3 pb-1">
        <div class="flex items-center gap-2 bg-municipal-50 rounded-xl px-3 py-2 border border-transparent focus-within:border-accent focus-within:bg-surface transition-all">
            <i data-lucide="search" class="w-4 h-4 text-text-tertiary shrink-0"></i>
            <input type="text" placeholder="بحث سريع..." class="bg-transparent border-none outline-none text-xs text-text placeholder-text-tertiary/60 w-full font-medium" style="outline:none;box-shadow:none">
            <kbd class="text-[9px] font-bold text-text-tertiary bg-surface px-1.5 py-0.5 rounded border border-border leading-none shrink-0">⌘K</kbd>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-2 px-3 space-y-0.5">
        @foreach ($navGroups as $groupTitle => $items)
            <div x-show="sidebarOpen" class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-text-tertiary uppercase tracking-[0.08em]">{{ $groupTitle }}</p>
            </div>
            @foreach ($items as $item)
                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    @class([
                        'sidebar-item',
                        'sidebar-item-active' => $isActive($item),
                        'hover:sidebar-item-hover' => !$isActive($item),
                    ])
                >
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                    <span x-show="sidebarOpen" x-transition>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>

    {{-- Bottom Items --}}
    <div class="border-t border-border/50 mx-3 py-2 space-y-0.5">
        @foreach ($bottomItems as $item)
            <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="sidebar-item hover:sidebar-item-hover">
                <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                <span x-show="sidebarOpen" x-transition>{{ $item['label'] }}</span>
            </a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-full" style="color: var(--color-danger)">
                <i data-lucide="log-out" class="w-[18px] h-[18px] shrink-0 rotate-180"></i>
                <span x-show="sidebarOpen" x-transition>تسجيل الخروج</span>
            </button>
        </form>
    </div>

    {{-- Toggle --}}
    <div class="border-t border-border/50 mx-3 py-2">
        <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-xs font-semibold text-text-tertiary hover:bg-municipal-50 hover:text-primary transition-all">
            <i data-lucide="panel-right-close" x-show="sidebarOpen" class="w-[18px] h-[18px] shrink-0"></i>
            <i data-lucide="panel-right-open" x-show="!sidebarOpen" class="w-[18px] h-[18px] shrink-0"></i>
            <span x-show="sidebarOpen" x-transition>طي القائمة</span>
        </button>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div
    x-show="mobileSidebar"
    x-cloak
    @click="mobileSidebar = false"
    class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm lg:hidden"
    x-transition.opacity
></div>

<aside
    x-show="mobileSidebar"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full"
    class="fixed inset-y-4 left-4 z-50 w-[280px] bg-surface rounded-3xl shadow-dropdown border border-border flex flex-col lg:hidden overflow-hidden"
>
    {{-- Mobile Header --}}
    <div class="flex items-center justify-between px-4 h-[64px] border-b border-border shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center shrink-0">
                <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-7 h-7 object-contain">
            </a>
            <div>
                <p class="text-primary font-bold text-sm">بلدية إذنا</p>
                <p class="text-[9px] text-text-tertiary font-medium">Municipality Management System</p>
            </div>
        </div>
        <button @click="mobileSidebar = false" class="p-2 rounded-xl hover:bg-municipal-50 transition-colors">
            <i data-lucide="x" class="w-5 h-5 text-text-secondary"></i>
        </button>
    </div>

    {{-- Mobile Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-1">
        @foreach ($navGroups as $groupTitle => $items)
            <div class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-text-tertiary uppercase tracking-[0.08em]">{{ $groupTitle }}</p>
            </div>
            @foreach ($items as $item)
                <a
                    href="{{ $item['route'] ? route($item['route']) : '#' }}"
                    @class([
                        'sidebar-item',
                        'sidebar-item-active' => $isActive($item),
                        'hover:sidebar-item-hover' => !$isActive($item),
                    ])
                >
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach

        @if (!empty($bottomItems))
        <div class="pt-4 pb-1 px-4">
            <p class="text-[10px] font-bold text-text-tertiary uppercase tracking-[0.08em]">عام</p>
        </div>
        @foreach ($bottomItems as $item)
            <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="sidebar-item hover:sidebar-item-hover">
                <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-full" style="color: var(--color-danger)">
                <i data-lucide="log-out" class="w-[18px] h-[18px] shrink-0 rotate-180"></i>
                <span>تسجيل الخروج</span>
            </button>
        </form>
    </nav>
</aside>

@once
    <style>
        .sidebar-item .badge-count {
            font-size: 10px;
            padding: 1px 8px;
            border-radius: 9999px;
            font-weight: 700;
            line-height: 1.5;
        }
    </style>
@endonce
