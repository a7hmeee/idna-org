<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بلدية إذنا - لوحة التحكم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.0/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Cairo', 'sans-serif'] },
                    colors: {
                        primary: { DEFAULT: '#2E6F1F', 50: '#EDF5EB', 100: '#D4E8CE', 200: '#A9D19D', 300: '#7EBA6C', 400: '#6BAA3B', 500: '#4F8F2F', 600: '#2E6F1F', 700: '#235818', 800: '#1A4112', 900: '#0F2A0B' },
                        surface: { DEFAULT: '#FFFFFF', hover: '#F5F9F4', active: '#EDF5EB' },
                        border: { DEFAULT: '#E6EEE5', light: '#F0F6EF' },
                        text: { DEFAULT: '#1A2E15', secondary: '#4A6B3F', tertiary: '#7A9A6E', muted: '#A8C09E' },
                        bg: { DEFAULT: '#F7FAF7', dark: '#F0F6EF' },
                    },
                    boxShadow: {
                        'glass': '0 8px 32px rgba(0,0,0,0.08)',
                        'card': '0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02)',
                        'card-hover': '0 8px 24px rgba(46,111,31,0.10), 0 2px 4px rgba(0,0,0,0.04)',
                        'elevated': '0 4px 16px rgba(0,0,0,0.06)',
                        'dropdown': '0 12px 48px rgba(0,0,0,0.10)',
                    },
                    borderRadius: { '2xl': '16px', '3xl': '20px' },
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', sans-serif; background: #F7FAF7; color: #1A2E15; -webkit-font-smoothing: antialiased; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #C8DCC1; border-radius: 9999px; }
        ::-webkit-scrollbar-track { background: transparent; }

        .sidebar-glass {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.02);
        }
        .sidebar-nav { scroll-behavior: smooth; -webkit-overflow-scrolling: touch; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: #D4E8CE; border-radius: 9999px; }
        .sidebar-nav::-webkit-scrollbar-thumb:hover { background: #A9D19D; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 12px;
            font-size: 13px; font-weight: 600; color: #4A6B3F; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer; text-decoration: none; position: relative; overflow: hidden;
        }
        .sidebar-item:hover { background: #EDF5EB; color: #2E6F1F; transform: translateX(-2px); }
        .sidebar-item.active {
            background: linear-gradient(135deg, #2E6F1F 0%, #235818 100%);
            color: white; box-shadow: 0 2px 8px rgba(46,111,31,0.25), inset 0 1px 0 rgba(255,255,255,0.1);
        }
        .sidebar-item.active::before {
            content: ''; position: absolute; right: -14px; top: 50%; transform: translateY(-50%);
            width: 4px; height: 24px; background: #2E6F1F; border-radius: 0 4px 4px 0;
        }
        .sidebar-item.active i { filter: drop-shadow(0 1px 2px rgba(0,0,0,0.2)); }
        .sidebar-item .badge-count {
            font-size: 10px; padding: 1px 8px; border-radius: 9999px; font-weight: 700; line-height: 1.5; margin-right: auto;
        }
        .sidebar-separator {
            height: 1px; background: linear-gradient(90deg, transparent, #E6EEE5, transparent);
            margin: 4px 16px;
        }

        .stat-card {
            background: white; border-radius: 16px; border: 1px solid #E6EEE5; padding: 20px;
            transition: all 0.25s ease; cursor: default;
        }
        .stat-card:hover {
            box-shadow: 0 8px 24px rgba(46,111,31,0.08); border-color: #A9D19D; transform: translateY(-2px);
        }

        .icon-grad-primary { background: linear-gradient(135deg, #EDF5EB, #D4E8CE); }
        .icon-grad-warning { background: linear-gradient(135deg, #FEF3C7, #FDE68A); }
        .icon-grad-danger { background: linear-gradient(135deg, #FEE2E2, #FECACA); }
        .icon-grad-info { background: linear-gradient(135deg, #DBEAFE, #BFDBFE); }
        .icon-grad-success { background: linear-gradient(135deg, #D1FAE5, #A7F3D0); }
        .icon-grad-purple { background: linear-gradient(135deg, #F3E8FF, #E9D5FF); }

        .sparkline-container { direction: ltr; }

        .badge { display: inline-flex; align-items: center; padding: 2px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; line-height: 1.6; }
        .badge-success { background: #D1FAE5; color: #065F46; }
        .badge-warning { background: #FEF3C7; color: #92400E; }
        .badge-danger { background: #FEE2E2; color: #991B1B; }
        .badge-info { background: #DBEAFE; color: #1E40AF; }
        .badge-neutral { background: #F0F6EF; color: #4A6B3F; }

        .priority-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }

        .progress-bar { height: 6px; border-radius: 9999px; background: #F0F6EF; overflow: hidden; }
        .progress-fill { height: 100%; border-radius: 9999px; transition: width 1s ease; }

        .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-size: 13px; font-weight: 600; background: #2E6F1F; color: white; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { background: #235818; box-shadow: 0 4px 12px rgba(46,111,31,0.25); }
        .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 12px; font-size: 13px; font-weight: 600; color: #4A6B3F; border: none; cursor: pointer; transition: all 0.2s; background: transparent; }
        .btn-ghost:hover { background: #EDF5EB; color: #2E6F1F; }

        .timeline-item { position: relative; padding-right: 28px; padding-bottom: 20px; }
        .timeline-item::before { content: ''; position: absolute; right: 8px; top: 8px; bottom: 0; width: 2px; background: #E6EEE5; }
        .timeline-item:last-child::before { display: none; }
        .timeline-dot { position: absolute; right: 0; top: 4px; width: 18px; height: 18px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 0 2px #2E6F1F; background: white; display: flex; align-items: center; justify-content: center; }

        .quick-action { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 12px; padding: 24px; border-radius: 16px; border: 1px solid #E6EEE5; background: white; cursor: pointer; transition: all 0.25s ease; text-decoration: none; color: #1A2E15; }
        .quick-action:hover { box-shadow: 0 8px 24px rgba(46,111,31,0.10); border-color: #A9D19D; transform: translateY(-2px); }
        .quick-action-icon { width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; }

        .cal-day { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 600; color: #4A6B3F; transition: all 0.15s; cursor: pointer; }
        .cal-day:hover { background: #EDF5EB; }
        .cal-day.today { background: #2E6F1F; color: white; box-shadow: 0 2px 8px rgba(46,111,31,0.2); }
        .cal-day.active:not(.today) { background: #D4E8CE; color: #2E6F1F; }

        .table-modern { width: 100%; border-collapse: collapse; }
        .table-modern th { text-align: right; padding: 12px 16px; font-size: 11px; font-weight: 700; color: #7A9A6E; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #E6EEE5; }
        .table-modern td { padding: 12px 16px; font-size: 13px; border-bottom: 1px solid #F0F6EF; }
        .table-modern tr:last-child td { border-bottom: none; }
        .table-modern tr { transition: background 0.15s; }
        .table-modern tr:hover { background: #F5F9F4; }

        .hero-gradient {
            background: linear-gradient(135deg, #1A4112 0%, #2E6F1F 30%, #4F8F2F 60%, #6BAA3B 100%);
        }

        .chart-container { direction: ltr; }

        .text-balance { text-wrap: balance; }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes counterIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeInUp 0.5s ease forwards; opacity: 0; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.15s; }
        .delay-300 { animation-delay: 0.2s; }
        .delay-400 { animation-delay: 0.25s; }
        .delay-500 { animation-delay: 0.3s; }

        .shimmer { background: linear-gradient(90deg, #F0F6EF 25%, #E6EEE5 50%, #F0F6EF 75%); background-size: 200% 100%; animation: shimmer 2s infinite; }
        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        .hover-lift { transition: all 0.25s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.06); }

        [dir="rtl"] .ml-auto { margin-right: auto; margin-left: 0; }
        [dir="rtl"] .mr-auto { margin-left: auto; margin-right: 0; }
        [dir="rtl"] .space-x-reverse > :not([hidden]) ~ :not([hidden]) { --tw-space-x-reverse: 1; }

        .donut-ring { transform: rotate(-90deg); }
        .donut-ring .donut-segment { fill: none; stroke-width: 8; stroke-linecap: round; }

        @media (max-width: 1024px) {
            .sidebar-desktop { display: none; }
        }
    </style>
</head>
<body>

<div class="flex min-h-screen bg-[#F7FAF7]" x-data='{
    sidebarOpen: true,
    mobileSidebar: false,
    counters: { users: 0, departments: 0, services: 0, complaints: 0, projects: 0, revenue: 0, visitors: 0, pending: 0 },
    initCounters() {
        const targets = {!! json_encode([
            "users" => $usersCount ?? 0,
            "departments" => $departmentsCount ?? 0,
            "services" => $servicesCount ?? 0,
            "complaints" => $complaintsCount ?? 0,
            "projects" => $projectsCount ?? 0,
            "revenue" => $revenueCount ?? 0,
            "visitors" => $visitorsCount ?? 0,
            "pending" => $pendingCount ?? 0,
        ]) !!};
        const duration = 1500;
        Object.keys(targets).forEach(key => {
            const start = 0;
            const end = targets[key];
            const startTime = performance.now();
            const animate = (time) => {
                const elapsed = time - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                this.counters[key] = Math.floor(eased * end);
                if (progress < 1) requestAnimationFrame(animate);
            };
            requestAnimationFrame(animate);
        });
    },
    init() { this.initCounters(); }
}'>

    {{-- ===== FLOATING GLASS SIDEBAR ===== --}}
    <aside :class="sidebarOpen ? 'w-[260px]' : 'w-[76px]'" class="fixed inset-y-4 right-4 z-50 sidebar-glass rounded-3xl flex flex-col transition-all duration-[300ms] ease-[cubic-bezier(0.4,0,0.2,1)] hidden lg:flex overflow-hidden">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 h-[64px] shrink-0 border-b border-[#E6EEE5]/50 mx-3">
            <a href="#" class="w-9 h-9 rounded-xl bg-[#2E6F1F] flex items-center justify-center shrink-0 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
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

            $navGroups = array_filter($navGroups, fn ($items) => !empty($items));
        @endphp
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
            <a href="#" class="sidebar-item">
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
                <a href="#" class="w-9 h-9 rounded-xl bg-[#2E6F1F] flex items-center justify-center shrink-0 shadow-sm overflow-hidden">
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

    {{-- ===== MAIN CONTENT ===== --}}
    <div :class="sidebarOpen ? 'lg:mr-[284px]' : 'lg:mr-[100px]'" class="flex-1 flex flex-col min-h-screen transition-all duration-[300ms] ease-[cubic-bezier(0.4,0,0.2,1)] mr-4 lg:mr-[100px]">

        {{-- ═══════ TOP NAV ═══════ --}}
        <header class="sticky top-4 z-40 mx-4 mt-4 bg-white/80 backdrop-blur-xl rounded-2xl border border-[#E6EEE5] shadow-sm">
            <div class="flex items-center justify-between h-14 px-5">
                {{-- Right: Breadcrumb + Mobile Menu --}}
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebar = true" class="lg:hidden p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                        <i data-lucide="menu" class="w-5 h-5 text-[#4A6B3F]"></i>
                    </button>
                    <nav class="hidden sm:flex items-center gap-2 text-xs">
                        <a href="#" class="text-[#7A9A6E] hover:text-[#2E6F1F] transition-colors duration-200 font-semibold">الرئيسية</a>
                        <i data-lucide="chevron-left" class="w-3 h-3 text-[#A8C09E]"></i>
                        <span class="text-[#1A2E15] font-bold">لوحة التحكم</span>
                    </nav>
                </div>

                {{-- Left: Actions --}}
                <div class="flex items-center gap-1.5">
                    <div class="hidden md:flex items-center gap-2 bg-[#F0F6EF] rounded-xl px-3.5 py-2 border border-transparent focus-within:border-[#A9D19D] focus-within:bg-white transition-all duration-200 w-56">
                        <i data-lucide="search" class="w-4 h-4 text-[#7A9A6E] shrink-0"></i>
                        <input type="text" placeholder="ابحث في اللوحة..." class="bg-transparent border-none outline-none text-xs text-[#1A2E15] placeholder-[#A8C09E] w-full font-semibold" style="outline:none;box-shadow:none">
                        <kbd class="hidden lg:inline-flex items-center px-1.5 py-0.5 rounded-md bg-white text-[9px] font-bold text-[#A8C09E] border border-[#E6EEE5] leading-none">⌘K</kbd>
                    </div>

                    {{-- Notifications --}}
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                            <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#EF4444] rounded-full ring-2 ring-white"></div>
                            <i data-lucide="bell" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
                        </button>
                        <div x-show="notifOpen" @click.outside="notifOpen = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-dropdown border border-[#E6EEE5] py-1.5 z-50">
                            <div class="px-4 py-3 border-b border-[#E6EEE5] flex items-center justify-between">
                                <p class="text-xs font-bold text-[#1A2E15]">الإشعارات</p>
                                <span class="text-[10px] font-bold text-[#2E6F1F] cursor-pointer hover:underline">تحديد الكل كمقروء</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto sidebar-nav">
                                <div class="px-4 py-3 hover:bg-[#F5F9F4] transition-colors duration-150 cursor-pointer flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-[#FEE2E2] flex items-center justify-center shrink-0 mt-0.5"><i data-lucide="message-square-warning" class="w-4 h-4 text-[#DC2626]"></i></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-[#1A2E15]">شكوى جديدة مسجلة</p>
                                        <p class="text-[10px] text-[#7A9A6E] font-medium mt-0.5">منذ 5 دقائق</p>
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-[#EF4444] shrink-0 mt-1.5"></div>
                                </div>
                            </div>
                            <div class="px-4 py-2.5 border-t border-[#E6EEE5] text-center">
                                <a href="#" class="text-[11px] font-bold text-[#2E6F1F] hover:underline">عرض كل الإشعارات</a>
                            </div>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <button class="relative p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                        <div class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#22C55E] rounded-full ring-2 ring-white"></div>
                        <i data-lucide="message-square" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
                    </button>

                    {{-- Dark Mode --}}
                    <button class="p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                        <i data-lucide="moon" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
                    </button>

                    {{-- Language --}}
                    <button class="p-2 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                        <i data-lucide="globe" class="w-[18px] h-[18px] text-[#4A6B3F]"></i>
                    </button>

                    {{-- Separator --}}
                    <div class="w-px h-6 bg-[#E6EEE5] mx-1"></div>

                    {{-- Profile --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-[#EDF5EB] transition-colors duration-200">
                            <div class="w-8 h-8 rounded-full bg-[#2E6F1F] text-white font-bold text-xs flex items-center justify-center shadow-sm">{{ mb_substr(auth()->user()->name, 0, 2, 'UTF-8') }}</div>
                            <div class="hidden sm:block text-right">
                                <p class="text-xs font-bold text-[#1A2E15] leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-[9px] text-[#7A9A6E] leading-tight font-medium">مدير البلدية</p>
                            </div>
                            <svg class="w-[14px] h-[14px] text-[#A8C09E] transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-dropdown border border-[#E6EEE5] py-1.5 z-50">
                            <div class="px-4 py-3 border-b border-[#E6EEE5]">
                                <p class="text-sm font-bold text-[#1A2E15]">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-[#7A9A6E]">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs text-[#4A6B3F] hover:bg-[#EDF5EB] transition-colors duration-150 font-semibold"><i data-lucide="user" class="w-4 h-4"></i>الملف الشخصي</a>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-xs text-[#4A6B3F] hover:bg-[#EDF5EB] transition-colors duration-150 font-semibold"><i data-lucide="key-round" class="w-4 h-4"></i>تغيير كلمة المرور</a>
                            <div class="border-t border-[#E6EEE5] my-1"></div>
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-[#DC2626] hover:bg-[#FEE2E2] transition-colors duration-150 font-semibold"><i data-lucide="log-out" class="w-4 h-4"></i>تسجيل الخروج</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- ═══════ MAIN CONTENT ═══════ --}}
        <main class="flex-1 p-5 lg:p-7 space-y-6">

            {{-- ===== HERO WELCOME SECTION ===== --}}
            <div class="relative overflow-hidden rounded-2xl hero-gradient p-6 sm:p-8 animate-fade-up">
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="bg-white/15 rounded-full px-3 py-1 text-[11px] font-bold text-white/80">
                                    <i data-lucide="calendar" class="w-3 h-3 inline-block ml-1"></i>
                                    <span id="currentDate"></span>
                                </div>
                                <div class="bg-white/15 rounded-full px-3 py-1 text-[11px] font-bold text-white/80 flex items-center gap-1">
                                    <i data-lucide="sun" class="w-3 h-3"></i>
                                    <span>28°C | إذنا</span>
                                </div>
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2 text-balance">
                                مرحبًا بعودتك يا {{ auth()->user()->name }} 👋
                            </h1>
                            <p class="text-[#A9D19D] text-sm sm:text-base font-medium">
                                لديك <span class="font-bold text-white">{{ $pendingServiceRequests ?? 0 }}</span> طلب خدمة مفتوح و<span class="font-bold text-white">{{ $pendingComplaintReviews ?? 0 }}</span> شكوى بانتظار المراجعة اليوم.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <button class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-bold transition-all flex items-center gap-2 border border-white/10">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                خدمة جديدة
                            </button>
                            <button class="px-5 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-bold transition-all flex items-center gap-2 border border-white/10">
                                <i data-lucide="folder-plus" class="w-4 h-4"></i>
                                مشروع جديد
                            </button>
                            <button class="px-5 py-2.5 rounded-xl bg-white text-[#2E6F1F] text-sm font-bold transition-all flex items-center gap-2 shadow-lg hover:shadow-xl">
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                تقرير سريع
                            </button>
                        </div>
                    </div>
                </div>
                <div class="absolute top-0 left-0 w-72 h-72 bg-white/[0.03] rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-56 h-56 bg-white/[0.03] rounded-full translate-x-1/4 translate-y-1/4"></div>
                <div class="absolute top-1/2 left-1/4 w-32 h-32 bg-white/[0.02] rounded-full"></div>
            </div>

            {{-- ===== STATISTICS CARDS ===== --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-8 gap-3">
                {{-- Each stat card rendered from $stats array passed from controller --}}
                @php
                    $statCards = $stats ?? [
                        ['icon' => 'users', 'color' => 'primary', 'key' => 'users', 'label' => 'إجمالي المستخدمين', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 20 L8 16 L16 18 L24 12 L32 14 L40 8 L48 10 L56 6 L64 4', 'sparkColor' => '#22C55E'],
                        ['icon' => 'building-2', 'color' => 'info', 'key' => 'departments', 'label' => 'الأقسام', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 16 L8 14 L16 12 L24 15 L32 10 L40 12 L48 8 L56 10 L64 8', 'sparkColor' => '#2563EB'],
                        ['icon' => 'layers', 'color' => 'primary', 'key' => 'services', 'label' => 'الخدمات الإلكترونية', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 18 L8 14 L16 16 L24 10 L32 8 L40 6 L48 9 L56 4 L64 3', 'sparkColor' => '#22C55E'],
                        ['icon' => 'message-square-warning', 'color' => 'warning', 'key' => 'complaints', 'label' => 'الشكاوى المفتوحة', 'trend' => '0%', 'trendUp' => false, 'sparkline' => 'M0 4 L8 6 L16 4 L24 8 L32 10 L40 14 L48 16 L56 18 L64 20', 'sparkColor' => '#EF4444'],
                        ['icon' => 'folder-kanban', 'color' => 'success', 'key' => 'projects', 'label' => 'المشاريع النشطة', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 20 L8 16 L16 12 L24 8 L32 6 L40 6 L48 4 L56 3 L64 2', 'sparkColor' => '#22C55E'],
                        ['icon' => 'circle-dollar-sign', 'color' => 'purple', 'key' => 'revenue', 'label' => 'الإيرادات الشهرية', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 14 L8 10 L16 12 L24 6 L32 8 L40 4 L48 6 L56 4 L64 3', 'sparkColor' => '#7C3AED'],
                        ['icon' => 'eye', 'color' => 'info', 'key' => 'visitors', 'label' => 'زوار اليوم', 'trend' => '+0%', 'trendUp' => true, 'sparkline' => 'M0 22 L8 18 L16 20 L24 14 L32 10 L40 8 L48 6 L56 4 L64 3', 'sparkColor' => '#2563EB'],
                        ['icon' => 'clock', 'color' => 'danger', 'key' => 'pending', 'label' => 'طلبات معلقة', 'trend' => '0%', 'trendUp' => false, 'sparkline' => 'M0 4 L8 6 L16 8 L24 10 L32 12 L40 14 L48 16 L56 18 L64 20', 'sparkColor' => '#EF4444'],
                    ];
                    $colorIcons = [
                        'primary' => ['bg' => 'icon-grad-primary', 'text' => 'text-[#2E6F1F]'],
                        'info' => ['bg' => 'icon-grad-info', 'text' => 'text-[#2563EB]'],
                        'warning' => ['bg' => 'icon-grad-warning', 'text' => 'text-[#D97706]'],
                        'success' => ['bg' => 'icon-grad-success', 'text' => 'text-[#065F46]'],
                        'danger' => ['bg' => 'icon-grad-danger', 'text' => 'text-[#DC2626]'],
                        'purple' => ['bg' => 'icon-grad-purple', 'text' => 'text-[#7C3AED]'],
                    ];
                @endphp
                @foreach ($statCards as $i => $card)
                <div class="stat-card animate-fade-up delay-{{ min(($i % 4) * 100 + 100, 500) }}">
                    <div class="{{ $colorIcons[$card['color']]['bg'] }} w-10 h-10 rounded-xl flex items-center justify-center mb-3">
                        <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 {{ $colorIcons[$card['color']]['text'] }}"></i>
                    </div>
                    @if ($card['key'] === 'revenue')
                        <p class="text-xl font-bold text-[#1A2E15]"><span x-text="(counters.revenue / 1000).toFixed(0)"></span>k</p>
                    @else
                        <p class="text-xl font-bold text-[#1A2E15]" x-text="counters.{{ $card['key'] }}.toLocaleString('ar-SA')"></p>
                    @endif
                    <p class="text-[11px] text-[#7A9A6E] font-medium mb-2">{{ $card['label'] }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold {{ $card['trendUp'] ? 'text-[#22C55E]' : 'text-[#EF4444]' }} flex items-center gap-1">
                            <i data-lucide="{{ $card['trendUp'] ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>{{ $card['trend'] }}
                        </span>
                        <svg class="w-16 h-6" viewBox="0 0 64 24" fill="none">
                            <path d="{{ $card['sparkline'] }}" stroke="{{ $card['sparkColor'] }}" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.5"/>
                            <path d="{{ $card['sparkline'] }} L64 24 L0 24 Z" fill="url(#sparkline-{{ $card['key'] }})" opacity="0.15"/>
                        </svg>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ===== ANALYTICS CHARTS ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Area Chart (2 cols) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E6EEE5] p-5">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">تحليل الشكاوى الشهرية</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">آخر 12 شهراً</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 rounded-lg bg-[#2E6F1F] text-white text-[10px] font-bold">سنوي</button>
                            <button class="px-3 py-1.5 rounded-lg text-[#7A9A6E] hover:bg-[#F0F6EF] text-[10px] font-bold transition-all">شهري</button>
                            <button class="px-3 py-1.5 rounded-lg text-[#7A9A6E] hover:bg-[#F0F6EF] text-[10px] font-bold transition-all">أسبوعي</button>
                        </div>
                    </div>
                    <div class="h-64 w-full chart-container">
                        <svg class="w-full h-full" viewBox="0 0 500 220" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="areaChartGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#2E6F1F" stop-opacity="0.25"/>
                                    <stop offset="60%" stop-color="#2E6F1F" stop-opacity="0.06"/>
                                    <stop offset="100%" stop-color="#2E6F1F" stop-opacity="0"/>
                                </linearGradient>
                                <linearGradient id="areaChartGrad2" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#6BAA3B" stop-opacity="0.2"/>
                                    <stop offset="100%" stop-color="#6BAA3B" stop-opacity="0"/>
                                </linearGradient>
                            </defs>
                            @php
                                $area1 = $chartData['complaints_received'] ?? 'M25 160 L65 140 L105 150 L145 110 L185 100 L225 90 L265 70 L305 50 L345 40 L385 35 L425 30 L465 25';
                                $area2 = $chartData['complaints_resolved'] ?? 'M25 170 L65 155 L105 160 L145 135 L185 125 L225 115 L265 100 L305 85 L345 75 L385 70 L425 65 L465 60';
                            @endphp
                            <line x1="0" y1="180" x2="500" y2="180" stroke="#E6EEE5" stroke-width="1"/>
                            <line x1="0" y1="135" x2="500" y2="135" stroke="#E6EEE5" stroke-width="1" stroke-dasharray="4"/>
                            <line x1="0" y1="90" x2="500" y2="90" stroke="#E6EEE5" stroke-width="1" stroke-dasharray="4"/>
                            <line x1="0" y1="45" x2="500" y2="45" stroke="#E6EEE5" stroke-width="1" stroke-dasharray="4"/>
                            <path d="{{ $area1 }} L465 180 L25 180 Z" fill="url(#areaChartGrad)"/>
                            <path d="{{ $area2 }} L465 180 L25 180 Z" fill="url(#areaChartGrad2)"/>
                            <path d="{{ $area1 }}" fill="none" stroke="#2E6F1F" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="{{ $area2 }}" fill="none" stroke="#6BAA3B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="265" cy="70" r="3" fill="#2E6F1F" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-4 mt-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#2E6F1F]"></div>
                            <span class="text-[11px] text-[#4A6B3F] font-semibold">الشكاوى الواردة</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-[#6BAA3B]"></div>
                            <span class="text-[11px] text-[#4A6B3F] font-semibold">الشكاوى المعالجة</span>
                        </div>
                    </div>
                </div>

                {{-- Donut Chart (1 col) --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">توزيع الخدمات</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">حسب النوع</p>
                        </div>
                        <i data-lucide="more-horizontal" class="w-5 h-5 text-[#A8C09E] cursor-pointer"></i>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="relative w-44 h-44">
                            <svg class="w-full h-full" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="48" fill="none" stroke="#F0F6EF" stroke-width="8"/>
                                <circle cx="60" cy="60" r="48" fill="none" stroke="#2E6F1F" stroke-width="8" stroke-dasharray="180 120" stroke-dashoffset="0" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                                <circle cx="60" cy="60" r="48" fill="none" stroke="#6BAA3B" stroke-width="8" stroke-dasharray="75 225" stroke-dashoffset="-182" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                                <circle cx="60" cy="60" r="48" fill="none" stroke="#D4E8CE" stroke-width="8" stroke-dasharray="45 255" stroke-dashoffset="-260" stroke-linecap="round" transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-bold text-[#1A2E15]">{{ $servicesTotal ?? 0 }}</span>
                                <span class="text-[10px] text-[#7A9A6E] font-medium">خدمة</span>
                            </div>
                        </div>
                        <div class="w-full space-y-2.5 mt-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#2E6F1F]"></div>
                                    <span class="text-xs text-[#4A6B3F] font-semibold">{{ $serviceCategories[0]['name'] ?? 'رخص البناء' }}</span>
                                </div>
                                <span class="text-xs font-bold text-[#1A2E15]">{{ $serviceCategories[0]['percent'] ?? '0%' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#6BAA3B]"></div>
                                    <span class="text-xs text-[#4A6B3F] font-semibold">{{ $serviceCategories[1]['name'] ?? 'الخدمات الصحية' }}</span>
                                </div>
                                <span class="text-xs font-bold text-[#1A2E15]">{{ $serviceCategories[1]['percent'] ?? '0%' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-2.5 h-2.5 rounded-full bg-[#D4E8CE]"></div>
                                    <span class="text-xs text-[#4A6B3F] font-semibold">{{ $serviceCategories[2]['name'] ?? 'أخرى' }}</span>
                                </div>
                                <span class="text-xs font-bold text-[#1A2E15]">{{ $serviceCategories[2]['percent'] ?? '0%' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== BOTTOM GRID: Complaints + Timeline + Departments ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                {{-- Latest Complaints --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">آخر الشكاوى</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">غير المقروءة</p>
                        </div>
                        <button class="text-xs font-bold text-[#2E6F1F] hover:text-[#235818] transition-colors">عرض الكل</button>
                    </div>
                    <div class="space-y-3">
                        @forelse ($latestComplaints ?? [] as $complaint)
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-[#F5F9F4] transition-colors cursor-pointer">
                            <div class="w-2 h-2 rounded-full {{ $complaint['dotClass'] ?? 'bg-[#EF4444]' }} mt-1.5 shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#1A2E15] truncate">{{ $complaint['title'] }}</p>
                                <p class="text-[10px] text-[#7A9A6E] font-medium">{{ $complaint['time'] }}</p>
                            </div>
                            <span class="badge {{ $complaint['badgeClass'] ?? 'badge-danger' }} shrink-0">{{ $complaint['badge'] }}</span>
                        </div>
                        @empty
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-[#F5F9F4] transition-colors cursor-pointer">
                            <div class="w-2 h-2 rounded-full bg-[#22C55E] mt-1.5 shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-[#1A2E15] truncate">لا توجد شكاوى جديدة</p>
                                <p class="text-[10px] text-[#7A9A6E] font-medium">جميع الشكاوى تمت معالجتها</p>
                            </div>
                            <span class="badge badge-neutral shrink-0">مغلق</span>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Activity Timeline --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">آخر النشاطات</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">اليوم</p>
                        </div>
                        <button class="text-xs font-bold text-[#2E6F1F] hover:text-[#235818] transition-colors">عرض الكل</button>
                    </div>
                    <div class="space-y-0">
                        @forelse ($recentActivities ?? [] as $activity)
                        <div class="timeline-item">
                            <div class="timeline-dot"><div class="w-2 h-2 rounded-full {{ $activity['dotClass'] ?? 'bg-[#2E6F1F]' }}"></div></div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg {{ $activity['iconBgClass'] ?? 'bg-[#EDF5EB]' }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $activity['icon'] ?? 'activity' }}" class="w-4 h-4 {{ $activity['iconColorClass'] ?? 'text-[#2E6F1F]' }}"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[#1A2E15]">{{ $activity['title'] }}</p>
                                    <p class="text-[10px] text-[#7A9A6E] font-medium">{{ $activity['description'] }}</p>
                                    <p class="text-[9px] text-[#A8C09E] font-medium mt-0.5">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="timeline-item">
                            <div class="timeline-dot"><div class="w-2 h-2 rounded-full bg-[#A8C09E]"></div></div>
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-[#F0F6EF] flex items-center justify-center shrink-0">
                                    <i data-lucide="activity" class="w-4 h-4 text-[#A8C09E]"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-[#1A2E15]">لا توجد نشاطات حديثة</p>
                                    <p class="text-[10px] text-[#7A9A6E] font-medium">سيتم عرض النشاطات هنا</p>
                                    <p class="text-[9px] text-[#A8C09E] font-medium mt-0.5">—</p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Departments Overview --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">الأقسام</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">نظرة عامة</p>
                        </div>
                        <button class="text-xs font-bold text-[#2E6F1F] hover:text-[#235818] transition-colors">عرض الكل</button>
                    </div>
                    <div class="space-y-4">
                        @forelse ($departmentStats ?? [] as $dept)
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-[#F5F9F4] transition-colors cursor-pointer">
                            <div class="w-9 h-9 rounded-xl {{ $dept['iconBgClass'] ?? 'bg-[#EDF5EB]' }} flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $dept['icon'] ?? 'building-2' }}" class="w-[18px] h-[18px] {{ $dept['iconColorClass'] ?? 'text-[#2E6F1F]' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-bold text-[#1A2E15]">{{ $dept['name'] }}</p>
                                    <span class="text-[10px] text-[#7A9A6E] font-semibold">{{ $dept['staff'] }}</span>
                                </div>
                                <div class="progress-bar mb-1"><div class="progress-fill" style="width: {{ $dept['progress'] }}%; background: {{ $dept['progressColor'] ?? '#2E6F1F' }}"></div></div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold {{ $dept['progressTextClass'] ?? 'text-[#22C55E]' }}">{{ $dept['progress'] }}% إنجاز</span>
                                    <span class="text-[9px] text-[#A8C09E] font-medium">{{ $dept['status'] }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="flex items-start gap-3 p-3 rounded-xl hover:bg-[#F5F9F4] transition-colors cursor-pointer">
                            <div class="w-9 h-9 rounded-xl bg-[#F0F6EF] flex items-center justify-center shrink-0">
                                <i data-lucide="building-2" class="w-[18px] h-[18px] text-[#A8C09E]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-xs font-bold text-[#1A2E15]">لا توجد أقسام</p>
                                    <span class="text-[10px] text-[#7A9A6E] font-semibold">—</span>
                                </div>
                                <div class="progress-bar mb-1"><div class="progress-fill" style="width: 0%"></div></div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-[#A8C09E]">0% إنجاز</span>
                                    <span class="text-[9px] text-[#A8C09E] font-medium">—</span>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ===== BOTTOM ROW: Calendar + News + Quick Access ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                {{-- Calendar Widget --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-[#1A2E15]" id="calendarMonth">يوليو 2026</h3>
                        <div class="flex items-center gap-1">
                            <button class="p-1.5 rounded-lg hover:bg-[#EDF5EB]"><i data-lucide="chevron-right" class="w-4 h-4 text-[#4A6B3F]"></i></button>
                            <button class="p-1.5 rounded-lg hover:bg-[#EDF5EB]"><i data-lucide="chevron-left" class="w-4 h-4 text-[#4A6B3F]"></i></button>
                        </div>
                    </div>
                    <div class="grid grid-cols-7 gap-0.5 text-center mb-2">
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">س</span>
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">ح</span>
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">ن</span>
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">ث</span>
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">ر</span>
                        <span class="text-[10px] font-bold text-[#A8C09E] py-1">خ</span>
                        <span class="text-[10px] font-bold text-[#2E6F1F] py-1">ج</span>
                    </div>
                    <div class="grid grid-cols-7 gap-0.5 text-center" id="calendarDays">
                        <span class="cal-day text-[#A8C09E]">27</span>
                        <span class="cal-day text-[#A8C09E]">28</span>
                        <span class="cal-day text-[#A8C09E]">29</span>
                        <span class="cal-day text-[#A8C09E]">30</span>
                        <span class="cal-day">1</span>
                        <span class="cal-day">2</span>
                        <span class="cal-day">3</span>
                        <span class="cal-day">4</span>
                        <span class="cal-day">5</span>
                        <span class="cal-day">6</span>
                        <span class="cal-day">7</span>
                        <span class="cal-day">8</span>
                        <span class="cal-day active">9</span>
                        <span class="cal-day today">10</span>
                        <span class="cal-day">11</span>
                        <span class="cal-day">12</span>
                        <span class="cal-day">13</span>
                        <span class="cal-day">14</span>
                        <span class="cal-day">15</span>
                        <span class="cal-day">16</span>
                        <span class="cal-day">17</span>
                        <span class="cal-day">18</span>
                        <span class="cal-day active">19</span>
                        <span class="cal-day">20</span>
                        <span class="cal-day">21</span>
                        <span class="cal-day">22</span>
                        <span class="cal-day">23</span>
                        <span class="cal-day">24</span>
                        <span class="cal-day">25</span>
                        <span class="cal-day">26</span>
                        <span class="cal-day">27</span>
                        <span class="cal-day">28</span>
                        <span class="cal-day">29</span>
                        <span class="cal-day">30</span>
                        <span class="cal-day">31</span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-[#E6EEE5] space-y-2">
                        <div class="flex flex-col items-center text-center py-4">
                            <p class="text-xs text-[#A8C09E] font-medium">لا توجد أحداث مضافـة</p>
                        </div>
                    </div>
                </div>

                {{-- Recent News (2 cols) --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-[#E6EEE5] p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-[#1A2E15]">آخر الأخبار</h3>
                            <p class="text-xs text-[#7A9A6E] font-medium mt-0.5">أخبار وأنشطة البلدية</p>
                        </div>
                        <button class="text-xs font-bold text-[#2E6F1F] hover:text-[#235818] transition-colors">عرض الكل</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse ($recentNews ?? [] as $newsItem)
                        <div class="rounded-xl overflow-hidden border border-[#E6EEE5] hover:shadow-elevated transition-all cursor-pointer group">
                            <div class="h-32 flex items-center justify-center" style="background: {{ $newsItem['gradient'] ?? 'linear-gradient(135deg, rgba(46,111,31,0.1), rgba(107,170,59,0.1))' }}">
                                <i data-lucide="{{ $newsItem['icon'] ?? 'building-2' }}" class="w-10 h-10" style="color: {{ $newsItem['iconColor'] ?? 'rgba(46,111,31,0.3)' }}"></i>
                            </div>
                            <div class="p-3">
                                <span class="badge {{ $newsItem['badgeClass'] ?? 'badge-success' }} text-[9px]">{{ $newsItem['category'] ?? 'أخبار البلدية' }}</span>
                                <p class="text-xs font-bold text-[#1A2E15] mt-1.5 group-hover:text-[#2E6F1F] transition-colors line-clamp-2">{{ $newsItem['title'] ?? '—' }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[9px] text-[#A8C09E] font-medium">{{ $newsItem['date'] ?? '—' }}</span>
                                    <span class="text-[9px] text-[#A8C09E] font-medium">{{ $newsItem['department'] ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="sm:col-span-2 flex flex-col items-center justify-center py-12 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-[#F0F6EF] flex items-center justify-center mb-3">
                                <i data-lucide="newspaper" class="w-7 h-7 text-[#A8C09E]"></i>
                            </div>
                            <p class="text-sm font-bold text-[#1A2E15]">لا توجد أخبار</p>
                            <p class="text-xs text-[#7A9A6E] mt-1">ستظهر أخبار البلدية هنا عند نشرها</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Quick Access --}}
                <div class="bg-white rounded-2xl border border-[#E6EEE5] p-5 lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-[#1A2E15]">وصول سريع</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="#" class="quick-action !p-4">
                            <div class="quick-action-icon bg-[#EDF5EB]">
                                <i data-lucide="file-text" class="w-5 h-5 text-[#2E6F1F]"></i>
                            </div>
                            <span class="text-[11px] font-bold text-center">تقديم<br>معاملة</span>
                        </a>
                        <a href="#" class="quick-action !p-4">
                            <div class="quick-action-icon bg-[#FEF3C7]">
                                <i data-lucide="message-square-warning" class="w-5 h-5 text-[#D97706]"></i>
                            </div>
                            <span class="text-[11px] font-bold text-center">تقديم<br>شكوى</span>
                        </a>
                        <a href="#" class="quick-action !p-4">
                            <div class="quick-action-icon bg-[#D1FAE5]">
                                <i data-lucide="receipt" class="w-5 h-5 text-[#065F46]"></i>
                            </div>
                            <span class="text-[11px] font-bold text-center">الاستعلام عن<br>فاتورة</span>
                        </a>
                        <a href="#" class="quick-action !p-4">
                            <div class="quick-action-icon bg-[#DBEAFE]">
                                <i data-lucide="calendar-check" class="w-5 h-5 text-[#2563EB]"></i>
                            </div>
                            <span class="text-[11px] font-bold text-center">حجز<br>موعد</span>
                        </a>
                    </div>
                </div>
            </div>

        </main>

        {{-- ═══════ FOOTER ═══════ --}}
        <footer class="px-5 lg:px-7 py-4 border-t border-[#E6EEE5] mx-4 mb-4">
            <div class="flex items-center justify-between text-[10px] text-[#A8C09E] font-medium">
                <span>© 2026 بلدية إذنا - جميع الحقوق محفوظة</span>
                <span>الإصدار 2.0 | النظام الرقمي للإدارة البلدية</span>
            </div>
        </footer>
    </div>
</div>

{{-- SVG Defs for Sparklines --}}
<svg style="position:absolute;width:0;height:0">
    <defs>
        <linearGradient id="sparkline-users" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#22C55E" stop-opacity="1"/>
            <stop offset="100%" stop-color="#22C55E" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-departments" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#2563EB" stop-opacity="1"/>
            <stop offset="100%" stop-color="#2563EB" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-services" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#22C55E" stop-opacity="1"/>
            <stop offset="100%" stop-color="#22C55E" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-complaints" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#EF4444" stop-opacity="1"/>
            <stop offset="100%" stop-color="#EF4444" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-projects" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#22C55E" stop-opacity="1"/>
            <stop offset="100%" stop-color="#22C55E" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-revenue" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#7C3AED" stop-opacity="1"/>
            <stop offset="100%" stop-color="#7C3AED" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-visitors" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#2563EB" stop-opacity="1"/>
            <stop offset="100%" stop-color="#2563EB" stop-opacity="0"/>
        </linearGradient>
        <linearGradient id="sparkline-pending" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#EF4444" stop-opacity="1"/>
            <stop offset="100%" stop-color="#EF4444" stop-opacity="0"/>
        </linearGradient>
    </defs>
</svg>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('currentDate').textContent = now.toLocaleDateString('ar-SA', options);

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
</body>
</html>
