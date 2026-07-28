<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بلدية إذنا - لوحة التحكم</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body>

<div class="flex min-h-screen bg-background" x-data='{
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
    <x-sidebar />

    {{-- ===== MAIN CONTENT ===== --}}
    <div :class="sidebarOpen ? 'lg:mr-[284px]' : 'lg:mr-[100px]'" class="flex-1 flex flex-col min-h-screen transition-all duration-[300ms] ease-[cubic-bezier(0.4,0,0.2,1)] mr-4 lg:mr-[100px]">

        {{-- ═══════ TOP NAV ═══════ --}}
        <x-navbar />

        {{-- ═══════ MAIN CONTENT ═══════ --}}
        <main class="flex-1 p-5 lg:p-7 space-y-6">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        {{-- ═══════ FOOTER ═══════ --}}
        <x-footer />
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
    function initIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var currentDateEl = document.getElementById('currentDate');
        if (currentDateEl) {
            var now = new Date();
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            currentDateEl.textContent = now.toLocaleDateString('ar-SA', options);
        }
        initIcons();

        var observer = new MutationObserver(function() {
            requestAnimationFrame(initIcons);
        });
        observer.observe(document.body, { childList: true, subtree: true });
    });

    document.addEventListener('livewire:navigated', function() {
        initIcons();
    });
</script>
@livewireScripts
</body>
</html>
