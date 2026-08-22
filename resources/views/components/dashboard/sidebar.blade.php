@php
    $user = auth()->user();
    $currentRoute = request()->route()?->getName();
    $navGroups = [];

    $navGroups['الرئيسية'] = [
        ['icon' => 'layout-dashboard', 'label' => 'لوحة التحكم', 'route' => 'dashboard', 'permission' => null, 'active' => $currentRoute === 'dashboard'],
    ];

    if ($user->can('view users')) {
        $navGroups['إدارة المستخدمين'][] = ['icon' => 'users', 'label' => 'المستخدمين', 'route' => 'users.index', 'active' => $currentRoute === 'users.index'];
    }
    if ($user->can('view roles')) {
        $navGroups['إدارة المستخدمين'][] = ['icon' => 'shield-check', 'label' => 'الأدوار والصلاحيات', 'route' => 'roles.index', 'active' => $currentRoute === 'roles.index'];
    }
            if ($user->can('departments.view')) {
                $navGroups['إدارة المستخدمين'][] = ['icon' => 'building-2', 'label' => 'الأقسام', 'route' => 'dashboard.departments', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.departments')];
            }
            if ($user->can('viewSlides', \App\Domains\Homepage\Models\HomepageSetting::class)) {
                $navGroups['المحتوى والخدمات'][] = ['icon' => 'images', 'label' => 'كاروسيل الصفحات', 'route' => 'dashboard.page-carousels', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.page-carousels')];
            }
            if ($user->can('announcements.view')) {
                $navGroups['المحتوى والخدمات'][] = ['icon' => 'megaphone', 'label' => 'الإعلانات', 'route' => 'dashboard.announcements', 'active' => $currentRoute === 'dashboard.announcements' || str_starts_with($currentRoute ?? '', 'dashboard.announcements.')];
            }
            if ($user->can('chatbot.view')) {
                $navGroups['المحتوى والخدمات'][] = ['icon' => 'bot-message', 'label' => 'المساعد الذكي', 'route' => 'dashboard.chatbot', 'active' => str_starts_with($currentRoute ?? '', 'dashboard.chatbot') || str_starts_with($currentRoute ?? '', 'admin.chatbot.')];
            }
    $navGroups = array_filter($navGroups, fn ($items) => !empty($items));
@endphp

{{-- Desktop Sidebar --}}
<aside :class="sidebarOpen ? 'w-[260px]' : 'w-[76px]'" class="fixed inset-y-4 right-4 z-50 sidebar-glass rounded-3xl flex flex-col transition-all duration-[300ms] ease-[cubic-bezier(0.4,0,0.2,1)] hidden lg:flex overflow-hidden">
    {{-- Logo --}}
    <div class="flex items-center gap-3 px-4 h-[64px] shrink-0 border-b border-[#E6EEE5]/50 mx-3">
        <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-[#2E6F1F] flex items-center justify-center shrink-0 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
            <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-7 h-7 object-contain">
        </a>
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="overflow-hidden whitespace-nowrap">
            <p class="text-[#2E6F1F] font-bold text-sm leading-tight">بلدية إذنا</p>
            <p class="text-[9px] text-[#7A9A6E] font-medium tracking-wide">Municipality Management System</p>
        </div>
    </div>

    {{-- Search in sidebar --}}
    <div x-show="sidebarOpen" class="px-3 pt-3 pb-1">
        <div class="flex items-center gap-2 bg-[#F0F6EF] rounded-xl px-3 py-2 border border-transparent focus-within:border-[#A9D19D] focus-within:bg-white transition-all">
            <i data-lucide="search" class="w-4 h-4 text-[#7A9A6E] shrink-0"></i>
            <input type="text" placeholder="بحث سريع..." class="bg-transparent border-none outline-none text-xs text-[#1A2E15] placeholder-[#A8C09E] w-full font-medium" style="outline:none;box-shadow:none">
            <kbd class="text-[9px] font-bold text-[#A8C09E] bg-white px-1.5 py-0.5 rounded border border-[#E6EEE5] leading-none">⌘K</kbd>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-2 px-3 sidebar-nav">
        @foreach ($navGroups as $groupTitle => $items)
            @if (!$loop->first)
                <div class="sidebar-separator my-1"></div>
            @endif
            <div x-show="sidebarOpen" class="px-4 pt-4 pb-1">
                <p class="text-[10px] font-bold text-[#A8C09E] uppercase tracking-[0.08em]">{{ $groupTitle }}</p>
            </div>
            @foreach ($items as $item)
                <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="sidebar-item {{ ($item['active'] || ($item['route'] && $currentRoute === $item['route'])) ? 'active' : '' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                    <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="text-[13px]">{{ $item['label'] }}</span>
                </a>
            @endforeach
        @endforeach
    </nav>

    {{-- Sidebar Footer --}}
    <div class="border-t border-[#E6EEE5]/50 mx-3 py-2 space-y-0.5">
        <div class="sidebar-separator mb-2"></div>
        <a href="{{ route('settings.index') }}" class="sidebar-item">
            <i data-lucide="settings" class="w-[18px] h-[18px] shrink-0"></i>
            <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">الإعدادات</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-full text-[#DC2626] hover:bg-[#FEE2E2]">
                <i data-lucide="log-out" class="w-[18px] h-[18px] shrink-0 rotate-180"></i>
                <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">تسجيل الخروج</span>
            </button>
        </form>
    </div>

    {{-- Toggle --}}
    <div class="border-t border-[#E6EEE5]/50 mx-3 py-2">
        <button @click="sidebarOpen = !sidebarOpen" class="w-full flex items-center gap-3 px-4 py-2 rounded-xl text-xs font-semibold text-[#7A9A6E] hover:bg-[#EDF5EB] transition-all duration-200">
            <i data-lucide="panel-right-close" x-show="sidebarOpen" class="w-[18px] h-[18px] shrink-0 transition-transform duration-200"></i>
            <i data-lucide="panel-right-open" x-show="!sidebarOpen" class="w-[18px] h-[18px] shrink-0 transition-transform duration-200"></i>
            <span x-show="sidebarOpen" x-transition:enter="transition-opacity duration-200 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">طي القائمة</span>
        </button>
    </div>
</aside>

{{-- Mobile Sidebar Overlay --}}
<div x-show="mobileSidebar" x-cloak @click="mobileSidebar = false" class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm lg:hidden" x-transition.opacity></div>
<aside x-show="mobileSidebar" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="fixed inset-y-4 left-4 z-50 w-[280px] bg-white rounded-3xl shadow-dropdown border border-[#E6EEE5] flex flex-col lg:hidden overflow-hidden">
    <div class="flex items-center justify-between px-4 h-[64px] border-b border-[#E6EEE5] shrink-0">
        <div class="flex items-center gap-3">
        <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-[#2E6F1F] flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
            <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-7 h-7 object-contain">
        </a>
        <div>
            <p class="text-[#2E6F1F] font-bold text-sm">بلدية إذنا</p>
            <p class="text-[9px] text-[#7A9A6E] font-medium tracking-wide">Municipality Management System</p>
        </div>
    </div>
        <button @click="mobileSidebar = false" class="p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200"><i data-lucide="x" class="w-5 h-5 text-[#4A6B3F]"></i></button>
    </div>
    <nav class="flex-1 overflow-y-auto py-3 px-3 space-y-1 sidebar-nav">
        @foreach ($navGroups as $groupTitle => $items)
            <div class="px-2 pt-2 pb-1"><p class="text-[10px] font-bold text-[#A8C09E] uppercase tracking-[0.08em]">{{ $groupTitle }}</p></div>
            @foreach ($items as $item)
                <a href="{{ $item['route'] ? route($item['route']) : '#' }}" class="sidebar-item {{ ($item['active'] || ($item['route'] && $currentRoute === $item['route'])) ? 'active' : '' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px]"></i>{{ $item['label'] }}
                </a>
            @endforeach
            @if (!$loop->last)
                <div class="sidebar-separator my-2"></div>
            @endif
        @endforeach

        <div class="sidebar-separator my-2"></div>
        <a href="#" class="sidebar-item"><i data-lucide="settings" class="w-[18px] h-[18px]"></i>الإعدادات</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-item w-full text-[#DC2626] hover:bg-[#FEE2E2]"><i data-lucide="log-out" class="w-[18px] h-[18px] rotate-180"></i>تسجيل الخروج</button>
        </form>
    </nav>
</aside>
