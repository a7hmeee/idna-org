<div>
    <div class="space-y-7">

        {{-- ═══════════════════════════════════════════════════════════
             1. HERO HEADER
             ═══════════════════════════════════════════════════════════ --}}
        <div class="relative overflow-hidden rounded-2xl hero-gradient p-6 sm:p-8 lg:p-10">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNCI+PHBhdGggZD0iTTM2IDM0djItSDI0di0yaDEyek0zNiAyNHYySDI0di0yaDEyeiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
            <div class="absolute top-0 left-0 w-80 h-80 bg-white/[0.03] rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/[0.03] rounded-full translate-x-1/4 translate-y-1/4"></div>
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center shrink-0 border border-white/10 shadow-sm">
                            <img src="{{ asset('logo.png') }}" alt="بلدية إذنا" class="w-10 h-10 object-contain">
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-2 flex-wrap">
                                <div class="bg-white/15 backdrop-blur-sm rounded-full px-3 py-1 text-[11px] font-bold text-white/80 border border-white/10">
                                    <i data-lucide="calendar" class="w-3 h-3 inline-block ml-1"></i>
                                    <span>{{ $data['header']['date'] }}</span>
                                </div>
                                <div class="bg-white/15 backdrop-blur-sm rounded-full px-3 py-1 text-[11px] font-bold text-white/80 border border-white/10 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <span x-data x-init="setInterval(() => $el.textContent = new Date().toLocaleTimeString('ar-SA', {hour: '2-digit', minute:'2-digit'}), 1000)">{{ $data['header']['time'] }}</span>
                                </div>
                            </div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white mb-1">
                                {{ $data['header']['welcomeMessage'] }}، {{ auth()->user()->name }}
                            </h1>
                            <p class="text-white/70 text-sm sm:text-base font-medium">{{ $data['header']['municipalityName'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if (!empty($data['quickActions']))
                            @foreach (array_slice($data['quickActions'], 0, 3) as $action)
                                @if (isset($action['route']) && Route::has($action['route']))
                                <a href="{{ route($action['route']) }}" wire:navigate
                                    class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white text-sm font-bold transition-all flex items-center gap-2 border border-white/10 backdrop-blur-sm hover:shadow-lg">
                                    <i data-lucide="{{ $action['icon'] }}" class="w-4 h-4"></i>
                                    <span class="hidden sm:inline">{{ $action['label'] }}</span>
                                </a>
                                @endif
                            @endforeach
                        @endif
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2.5 rounded-xl bg-white text-primary text-sm font-bold transition-all flex items-center gap-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            <span class="hidden sm:inline">تحديث</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════
             2. ALERT BANNER
             ═══════════════════════════════════════════════════════════ --}}
        @php
            $urgentNotifications = array_filter($data['notifications'] ?? [], fn($n) => in_array($n['color'] ?? '', ['danger', 'warning']));
            $infoNotifications = array_filter($data['notifications'] ?? [], fn($n) => ($n['color'] ?? '') === 'info');
        @endphp

        @if (!empty($urgentNotifications) || !empty($infoNotifications))
            <div class="space-y-2">
                @foreach ($urgentNotifications as $note)
                    <div class="flex items-center gap-3 px-5 py-3 rounded-xl
                        @if ($note['color'] === 'danger') bg-danger-light border border-red-200
                        @else bg-warning-light border border-amber-200 @endif
                    ">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                            @if ($note['color'] === 'danger') bg-red-200 text-red-700
                            @else bg-amber-200 text-amber-700 @endif
                        ">
                            <i data-lucide="{{ $note['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <p class="text-sm font-semibold
                            @if ($note['color'] === 'danger') text-red-800
                            @else text-amber-800 @endif
                        ">{{ $note['message'] }}</p>
                    </div>
                @endforeach
                @foreach ($infoNotifications as $note)
                    <div class="flex items-center gap-3 px-5 py-3 rounded-xl bg-info-light border border-blue-200">
                        <div class="w-8 h-8 rounded-lg bg-blue-200 text-blue-700 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $note['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <p class="text-sm font-semibold text-blue-800">{{ $note['message'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             3. KPI CARDS
             ═══════════════════════════════════════════════════════════ --}}
        @if (!empty($data['quickStats']))
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-text">مؤشرات الأداء الرئيسية</h2>
                        <p class="text-xs text-text-tertiary font-medium mt-0.5">نظرة سريعة على إحصائيات النظام</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @php
                        $routeMap = [
                            'services' => 'dashboard.electronic-services.services',
                            'departments' => 'dashboard.departments',
                            'council_members' => 'dashboard.municipality.council-members',
                            'council_decisions' => 'dashboard.municipality.council-decisions',
                            'engineering_offices' => 'dashboard.engineering-offices',
                            'jobs' => 'dashboard.jobs',
                            'facilities' => 'dashboard.facilities',
                            'water_schedules' => 'dashboard.water-schedule',
                        ];
                    @endphp
                    @foreach ($data['quickStats'] as $stat)
                        @php
                            $kpiRoute = $routeMap[$stat['key']] ?? null;
                            $tag = $kpiRoute && Route::has($kpiRoute) ? 'a' : 'div';
                            $attrs = $kpiRoute && Route::has($kpiRoute)
                                ? 'href="'.route($kpiRoute).'" wire:navigate'
                                : '';
                        @endphp
                        <{{ $tag }} {{ $attrs }}
                            class="stat-card hover:stat-card-hover cursor-pointer transition-all duration-200 block @if($kpiRoute && Route::has($kpiRoute)) no-underline @endif">
                            <div class="flex items-start justify-between mb-3">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center
                                    @switch($stat['color'])
                                        @case('primary') bg-primary/10 text-primary @break
                                        @case('blue') bg-blue-100 text-blue-600 @break
                                        @case('amber') bg-amber-100 text-amber-600 @break
                                        @case('purple') bg-purple-100 text-purple-600 @break
                                        @case('cyan') bg-cyan-100 text-cyan-600 @break
                                        @case('green') bg-green-100 text-green-600 @break
                                        @case('rose') bg-rose-100 text-rose-600 @break
                                        @case('sky') bg-sky-100 text-sky-600 @break
                                        @default bg-municipal-50 text-text-secondary @endswitch
                                ">
                                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                                </div>
                            </div>
                            <p class="text-3xl font-black text-text tracking-tight">{{ number_format($stat['count']) }}</p>
                            <p class="text-sm text-text-secondary font-semibold mt-0.5">{{ $stat['label'] }}</p>
                        </{{ $tag }}>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             4. QUICK ACTIONS
             ═══════════════════════════════════════════════════════════ --}}
        @if (!empty($data['quickActions']))
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-text">إجراءات سريعة</h2>
                        <p class="text-xs text-text-tertiary font-medium mt-0.5">أكثر الإجراءات استخداماً</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach ($data['quickActions'] as $action)
                        @if (isset($action['route']) && Route::has($action['route']))
                        <a href="{{ route($action['route']) }}" wire:navigate
                            class="quick-action hover:quick-action-hover no-underline">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                @switch($action['color'])
                                    @case('blue') bg-blue-100 text-blue-600 @break
                                    @case('green') bg-green-100 text-green-600 @break
                                    @case('purple') bg-purple-100 text-purple-600 @break
                                    @case('rose') bg-rose-100 text-rose-600 @break
                                    @case('cyan') bg-cyan-100 text-cyan-600 @break
                                    @case('amber') bg-amber-100 text-amber-600 @break
                                    @default bg-primary/10 text-primary @endswitch
                            ">
                                <i data-lucide="{{ $action['icon'] }}" class="w-5 h-5"></i>
                            </div>
                            <span class="text-xs font-bold text-text text-center leading-tight">{{ $action['label'] }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             5. ANALYTICS / CHARTS
             ═══════════════════════════════════════════════════════════ --}}
        @if (!empty($data['analytics']))
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-text">التحليلات والإحصائيات</h2>
                        <p class="text-xs text-text-tertiary font-medium mt-0.5">تحليل البيانات حسب التصنيف</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- Services by Category --}}
                    @if (!empty($data['analytics']['servicesByCategory']))
                        <div class="card">
                            <h3 class="font-bold text-text mb-1 text-sm">الخدمات حسب التصنيف</h3>
                            <p class="text-xs text-text-tertiary mb-5">توزيع الخدمات الإلكترونية على التصنيفات</p>
                            <div class="space-y-4">
                                @php $maxCount = max(array_column($data['analytics']['servicesByCategory'], 'services_count')); @endphp
                                @foreach ($data['analytics']['servicesByCategory'] as $cat)
                                    <div>
                                        <div class="flex items-center justify-between text-sm mb-1.5">
                                            <span class="font-semibold text-text">{{ $cat['name'] }}</span>
                                            <span class="text-xs font-bold text-text-secondary">{{ $cat['services_count'] }} خدمة</span>
                                        </div>
                                        <div class="w-full h-2.5 bg-municipal-50 rounded-full overflow-hidden" x-data x-init="$nextTick(() => { $el.querySelector('div').style.width = '{{ $maxCount > 0 ? ($cat['services_count'] / $maxCount) * 100 : 0 }}%' })">
                                            <div class="h-full rounded-full bg-primary transition-all duration-1000 ease-out" style="width: 0%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Most Viewed Services --}}
                    @if (!empty($data['analytics']['mostViewedServices']))
                        <div class="card">
                            <h3 class="font-bold text-text mb-1 text-sm">أكثر الخدمات زيارة</h3>
                            <p class="text-xs text-text-tertiary mb-5">أعلى 5 خدمات من حيث المشاهدات</p>
                            <div class="space-y-3">
                                @php $maxViews = max(array_column($data['analytics']['mostViewedServices'], 'views_count')); @endphp
                                @foreach ($data['analytics']['mostViewedServices'] as $service)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-municipal-50/50 border border-border/50">
                                        <span class="text-sm font-semibold text-text truncate ml-3">{{ $service['name'] }}</span>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <div class="w-28 sm:w-36 h-2 bg-white rounded-full overflow-hidden" x-data x-init="$nextTick(() => { $el.querySelector('div').style.width = '{{ $maxViews > 0 ? ($service['views_count'] / $maxViews) * 100 : 0 }}%' })">
                                                <div class="h-full rounded-full bg-primary transition-all duration-1000 ease-out" style="width: 0%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-text-tertiary min-w-[40px] text-left chart-container">{{ number_format($service['views_count']) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Most Clicked Services --}}
                    @if (!empty($data['analytics']['mostClickedServices']))
                        <div class="card">
                            <h3 class="font-bold text-text mb-1 text-sm">أكثر الخدمات نقراً</h3>
                            <p class="text-xs text-text-tertiary mb-5">أعلى 5 خدمات من حيث النقرات على البوابة</p>
                            <div class="space-y-3">
                                @php $maxClicks = max(array_column($data['analytics']['mostClickedServices'], 'portal_clicks_count')); @endphp
                                @foreach ($data['analytics']['mostClickedServices'] as $service)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-municipal-50/50 border border-border/50">
                                        <span class="text-sm font-semibold text-text truncate ml-3">{{ $service['name'] }}</span>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <div class="w-28 sm:w-36 h-2 bg-white rounded-full overflow-hidden" x-data x-init="$nextTick(() => { $el.querySelector('div').style.width = '{{ $maxClicks > 0 ? ($service['portal_clicks_count'] / $maxClicks) * 100 : 0 }}%' })">
                                                <div class="h-full rounded-full bg-accent transition-all duration-1000 ease-out" style="width: 0%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-text-tertiary min-w-[40px] text-left chart-container">{{ number_format($service['portal_clicks_count']) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Most Viewed Facilities --}}
                    @if (!empty($data['analytics']['mostViewedFacilities']))
                        <div class="card">
                            <h3 class="font-bold text-text mb-1 text-sm">أكثر المرافق زيارة</h3>
                            <p class="text-xs text-text-tertiary mb-5">أعلى 5 مرافق عامة من حيث المشاهدات</p>
                            <div class="space-y-3">
                                @php $maxViewsF = max(array_column($data['analytics']['mostViewedFacilities'], 'views_count')); @endphp
                                @foreach ($data['analytics']['mostViewedFacilities'] as $facility)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-municipal-50/50 border border-border/50">
                                        <span class="text-sm font-semibold text-text truncate ml-3">{{ $facility['name'] }}</span>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <div class="w-28 sm:w-36 h-2 bg-white rounded-full overflow-hidden" x-data x-init="$nextTick(() => { $el.querySelector('div').style.width = '{{ $maxViewsF > 0 ? ($facility['views_count'] / $maxViewsF) * 100 : 0 }}%' })">
                                                <div class="h-full rounded-full bg-rose-500 transition-all duration-1000 ease-out" style="width: 0%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-text-tertiary min-w-[40px] text-left chart-container">{{ number_format($facility['views_count']) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Most Viewed Jobs --}}
                    @if (!empty($data['analytics']['mostViewedJobs']))
                        <div class="card">
                            <h3 class="font-bold text-text mb-1 text-sm">أكثر الوظائف مشاهدة</h3>
                            <p class="text-xs text-text-tertiary mb-5">أعلى 5 وظائف من حيث المشاهدات</p>
                            <div class="space-y-3">
                                @php $maxViewsJ = max(array_column($data['analytics']['mostViewedJobs'], 'views_count')); @endphp
                                @foreach ($data['analytics']['mostViewedJobs'] as $job)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-municipal-50/50 border border-border/50">
                                        <span class="text-sm font-semibold text-text truncate ml-3">{{ $job['title'] }}</span>
                                        <div class="flex items-center gap-3 shrink-0">
                                            <div class="w-28 sm:w-36 h-2 bg-white rounded-full overflow-hidden" x-data x-init="$nextTick(() => { $el.querySelector('div').style.width = '{{ $maxViewsJ > 0 ? ($job['views_count'] / $maxViewsJ) * 100 : 0 }}%' })">
                                                <div class="h-full rounded-full bg-green-500 transition-all duration-1000 ease-out" style="width: 0%"></div>
                                            </div>
                                            <span class="text-xs font-bold text-text-tertiary min-w-[40px] text-left chart-container">{{ number_format($job['views_count']) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </section>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             6. MODULE STATS GRID
             ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- 6a. Electronic Services Stats --}}
            @if (!empty($data['serviceStats']))
                <div class="card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i data-lucide="laptop" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-text text-sm">الخدمات الإلكترونية</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">إحصائيات الخدمات</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-center">
                            <p class="text-xl font-black text-blue-700">{{ number_format($data['serviceStats']['total']) }}</p>
                            <p class="text-[10px] text-blue-600 font-semibold">إجمالي</p>
                        </div>
                        <div class="p-3 rounded-xl bg-green-50 border border-green-100 text-center">
                            <p class="text-xl font-black text-green-700">{{ number_format($data['serviceStats']['active']) }}</p>
                            <p class="text-[10px] text-green-600 font-semibold">نشطة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-xl font-black text-amber-700">{{ number_format($data['serviceStats']['draft']) }}</p>
                            <p class="text-[10px] text-amber-600 font-semibold">مسودة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-purple-50 border border-purple-100 text-center">
                            <p class="text-xl font-black text-purple-700">{{ number_format($data['serviceStats']['categories_count']) }}</p>
                            <p class="text-[10px] text-purple-600 font-semibold">تصنيف</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
                        <span class="text-xs text-text-tertiary font-medium">إجمالي المشاهدات</span>
                        <span class="text-sm font-bold text-text">{{ number_format($data['serviceStats']['total_views']) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-text-tertiary font-medium">إجمالي النقرات</span>
                        <span class="text-sm font-bold text-text">{{ number_format($data['serviceStats']['total_clicks']) }}</span>
                    </div>
                </div>
            @endif

            {{-- 6b. Water Stats --}}
            @if (!empty($data['waterStats']))
                <div class="card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center">
                            <i data-lucide="droplets" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-text text-sm">جدول توزيع المياه</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">حالة الضخ اليوم</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-3 rounded-xl bg-sky-50 border border-sky-100 text-center">
                            <p class="text-xl font-black text-sky-700">{{ number_format($data['waterStats']['today_schedules']) }}</p>
                            <p class="text-[10px] text-sky-600 font-semibold">مجدولة اليوم</p>
                        </div>
                        <div class="p-3 rounded-xl bg-green-50 border border-green-100 text-center">
                            <p class="text-xl font-black text-green-700">{{ number_format($data['waterStats']['available']) }}</p>
                            <p class="text-[10px] text-green-600 font-semibold">ضخ منتظم</p>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-xl font-black text-amber-700">{{ number_format($data['waterStats']['low_pressure']) }}</p>
                            <p class="text-[10px] text-amber-600 font-semibold">ضغط منخفض</p>
                        </div>
                        <div class="p-3 rounded-xl bg-red-50 border border-red-100 text-center">
                            <p class="text-xl font-black text-red-700">{{ number_format(($data['waterStats']['maintenance'] ?? 0) + ($data['waterStats']['emergency'] ?? 0)) }}</p>
                            <p class="text-[10px] text-red-600 font-semibold">صيانة/طوارئ</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
                        <span class="text-xs text-text-tertiary font-medium">مناطق المياه</span>
                        <span class="text-sm font-bold text-text">{{ number_format($data['waterStats']['total_areas'] ?? 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span class="text-xs text-text-tertiary font-medium">صيانة نشطة</span>
                        <span class="text-sm font-bold text-text">{{ number_format($data['waterStats']['active_maintenance'] ?? 0) }}</span>
                    </div>
                </div>
            @endif

            {{-- 6c. Jobs Stats --}}
            @if (!empty($data['jobStats']))
                <div class="card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-text text-sm">الوظائف</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">حالة الوظائف المعلنة</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-3 rounded-xl bg-green-50 border border-green-100 text-center">
                            <p class="text-xl font-black text-green-700">{{ number_format($data['jobStats']['open']) }}</p>
                            <p class="text-[10px] text-green-600 font-semibold">مفتوحة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-orange-50 border border-orange-100 text-center">
                            <p class="text-xl font-black text-orange-700">{{ number_format($data['jobStats']['closing_this_week']) }}</p>
                            <p class="text-[10px] text-orange-600 font-semibold">ستنتهي قريباً</p>
                        </div>
                        <div class="p-3 rounded-xl bg-red-50 border border-red-100 text-center">
                            <p class="text-xl font-black text-red-700">{{ number_format($data['jobStats']['closed']) }}</p>
                            <p class="text-[10px] text-red-600 font-semibold">مغلقة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-xl font-black text-amber-700">{{ number_format($data['jobStats']['draft']) }}</p>
                            <p class="text-[10px] text-amber-600 font-semibold">مسودة</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-border">
                        <span class="text-xs text-text-tertiary font-medium">إجمالي المشاهدات</span>
                        <span class="text-sm font-bold text-text">{{ number_format($data['jobStats']['total_views']) }}</span>
                    </div>
                </div>
            @endif

            {{-- 6d. Engineering Offices Stats --}}
            @if (!empty($data['engineeringStats']))
                <div class="card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-xl bg-cyan-100 text-cyan-600 flex items-center justify-center">
                            <i data-lucide="hard-hat" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-text text-sm">المكاتب الهندسية</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">حالة الاعتماد</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-3 rounded-xl bg-green-50 border border-green-100 text-center">
                            <p class="text-xl font-black text-green-700">{{ number_format($data['engineeringStats']['approved']) }}</p>
                            <p class="text-[10px] text-green-600 font-semibold">معتمدة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-center">
                            <p class="text-xl font-black text-amber-700">{{ number_format($data['engineeringStats']['pending']) }}</p>
                            <p class="text-[10px] text-amber-600 font-semibold">معلقة</p>
                        </div>
                        <div class="p-3 rounded-xl bg-red-50 border border-red-100 text-center">
                            <p class="text-xl font-black text-red-700">{{ number_format($data['engineeringStats']['expired']) }}</p>
                            <p class="text-[10px] text-red-600 font-semibold">منتهية</p>
                        </div>
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 text-center">
                            <p class="text-xl font-black text-gray-700">{{ number_format($data['engineeringStats']['suspended']) }}</p>
                            <p class="text-[10px] text-gray-600 font-semibold">موقوفة</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- ═══════════════════════════════════════════════════════════
             7. TIMELINE + ACTIVITY + EVENTS (3 cols)
             ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- 7a. Timeline --}}
            @if (!empty($data['timeline']))
                <div class="card lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-text text-sm">آخر التحديثات</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">أحدث الأنشطة في النظام</p>
                        </div>
                        <span class="badge badge-neutral">{{ count($data['timeline']) }}</span>
                    </div>
                    <div class="space-y-0">
                        @foreach ($data['timeline'] as $item)
                            <div class="timeline-item @if($loop->last) pb-0 @endif">
                                <div class="timeline-dot">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $item['iconColor'] ?? 'text-primary' }} bg-current"></div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $item['iconBg'] ?? 'bg-municipal-50' }} flex items-center justify-center shrink-0">
                                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 {{ $item['iconColor'] ?? 'text-text-secondary' }}"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-text truncate">{{ $item['title'] }}</p>
                                        <p class="text-[10px] text-text-tertiary font-medium mt-0.5">
                                            {{ $item['type'] }}
                                            @if (!empty($item['user']) && $item['user'] !== 'النظام')
                                                • {{ $item['user'] }}
                                            @endif
                                        </p>
                                        <p class="text-[9px] text-text-muted mt-0.5">{{ $item['time'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 7b. Today's Activity --}}
            @if (!empty($data['todayActivity']))
                <div class="card lg:col-span-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="font-bold text-text text-sm">أحداث اليوم</h3>
                            <p class="text-[10px] text-text-tertiary font-medium">آخر النشاطات المسجلة اليوم</p>
                        </div>
                        <span class="badge badge-info">{{ count($data['todayActivity']) }}</span>
                    </div>
                    <div class="space-y-3">
                        @foreach ($data['todayActivity'] as $activity)
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-municipal-50/50 border border-border/50 hover:bg-municipal-50 transition-colors">
                                <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $activity['icon'] }}" class="w-4 h-4"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-text truncate">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-text-tertiary font-medium mt-0.5">
                                        <span class="badge badge-neutral text-[9px]">{{ $activity['type'] }}</span>
                                        <span class="mr-1">• {{ $activity['time'] }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 7c. Upcoming Events --}}
            <div class="space-y-5">
                @if (!empty($data['upcomingEvents']))
                    <div class="card">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="font-bold text-text text-sm">الأحداث القادمة</h3>
                                <p class="text-[10px] text-text-tertiary font-medium">المواعيد القادمة في النظام</p>
                            </div>
                            <span class="badge badge-neutral">{{ count($data['upcomingEvents']) }}</span>
                        </div>
                        <div class="space-y-2">
                            @foreach ($data['upcomingEvents'] as $event)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-municipal-50/50 border border-border/50 hover:bg-municipal-50 transition-colors">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                                        @switch($event['color'])
                                            @case('orange') bg-orange-100 text-orange-600 @break
                                            @case('sky') bg-sky-100 text-sky-600 @break
                                            @case('red') bg-red-100 text-red-600 @break
                                            @default bg-municipal-50 text-text-secondary @endswitch
                                    ">
                                        <i data-lucide="{{ $event['icon'] }}" class="w-4 h-4"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-text truncate">{{ $event['title'] }}</p>
                                        <p class="text-[10px] text-text-tertiary font-medium mt-0.5">{{ $event['dateFormatted'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Homepage Stats --}}
                @if (!empty($data['homepageStats']))
                    <div class="card">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-municipal-100 text-primary flex items-center justify-center">
                                <i data-lucide="layout" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-text text-sm">لوحة الصفحة الرئيسية</h3>
                                <p class="text-[10px] text-text-tertiary font-medium">إحصائيات المحتوى</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            @if (isset($data['homepageStats']['slides_count']))
                                <div class="p-2.5 rounded-xl bg-municipal-50 border border-border text-center">
                                    <p class="text-lg font-black text-text">{{ number_format($data['homepageStats']['slides_count']) }}</p>
                                    <p class="text-[9px] text-text-tertiary font-semibold">شرائح</p>
                                </div>
                            @endif
                            @if (isset($data['homepageStats']['quick_links_count']))
                                <div class="p-2.5 rounded-xl bg-municipal-50 border border-border text-center">
                                    <p class="text-lg font-black text-text">{{ number_format($data['homepageStats']['quick_links_count']) }}</p>
                                    <p class="text-[9px] text-text-tertiary font-semibold">روابط سريعة</p>
                                </div>
                            @endif
                            @if (isset($data['homepageStats']['sections_count']))
                                <div class="p-2.5 rounded-xl bg-municipal-50 border border-border text-center">
                                    <p class="text-lg font-black text-text">{{ number_format($data['homepageStats']['sections_count']) }}</p>
                                    <p class="text-[9px] text-text-tertiary font-semibold">أقسام</p>
                                </div>
                            @endif
                            @if (isset($data['homepageStats']['statistics_count']))
                                <div class="p-2.5 rounded-xl bg-municipal-50 border border-border text-center">
                                    <p class="text-lg font-black text-text">{{ number_format($data['homepageStats']['statistics_count']) }}</p>
                                    <p class="text-[9px] text-text-tertiary font-semibold">إحصائيات</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- ═══════════════════════════════════════════════════════════
             8. SYSTEM HEALTH
             ═══════════════════════════════════════════════════════════ --}}
        @if (!empty($data['systemHealth']))
            <section>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-text">صحة النظام</h2>
                        <p class="text-xs text-text-tertiary font-medium mt-0.5">معلومات عامة عن حالة النظام</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="card text-center">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <p class="text-2xl font-black text-text">{{ number_format($data['systemHealth']['users_count']) }}</p>
                        <p class="text-xs text-text-tertiary font-semibold mt-0.5">المستخدمين</p>
                        <p class="text-[10px] text-green-600 font-bold mt-1">
                            <i data-lucide="circle" class="w-2 h-2 inline fill-current ml-0.5"></i>
                            {{ number_format($data['systemHealth']['active_users']) }} نشط
                        </p>
                    </div>

                    <div class="card text-center">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="shield" class="w-5 h-5"></i>
                        </div>
                        <p class="text-2xl font-black text-text">{{ number_format($data['systemHealth']['permissions_count']) }}</p>
                        <p class="text-xs text-text-tertiary font-semibold mt-0.5">صلاحية</p>
                        <p class="text-[10px] text-text-tertiary font-bold mt-1">
                            {{ number_format($data['systemHealth']['roles_count']) }} دور
                        </p>
                    </div>

                    <div class="card text-center">
                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="server" class="w-5 h-5"></i>
                        </div>
                        <p class="text-lg font-black text-text">{{ data_get($data['systemHealth'], 'laravel_version', '--') }}</p>
                        <p class="text-xs text-text-tertiary font-semibold mt-0.5">Laravel</p>
                        <p class="text-[10px] text-text-tertiary font-bold mt-1">PHP {{ data_get($data['systemHealth'], 'php_version', '--') }}</p>
                    </div>

                    <div class="card text-center">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="hard-drive" class="w-5 h-5"></i>
                        </div>
                        <p class="text-lg font-black text-text">{{ data_get($data['systemHealth'], 'storage_usage', '--') }}</p>
                        <p class="text-xs text-text-tertiary font-semibold mt-0.5">مساحة التخزين</p>
                        <p class="text-[10px] text-text-tertiary font-bold mt-1">{{ data_get($data['systemHealth'], 'cache_driver', '--') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div class="card">
                        <h3 class="font-bold text-text text-sm mb-3">معلومات النظام</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between py-1.5 border-b border-border/50 text-sm">
                                <span class="text-text-tertiary">البيئة</span>
                                <span class="text-text font-semibold">{{ $data['systemHealth']['environment'] }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-border/50 text-sm">
                                <span class="text-text-tertiary">وضع التصحيح</span>
                                <span class="text-text font-semibold">{{ $data['systemHealth']['debug_mode'] ? 'مفعل' : 'معطل' }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 text-sm">
                                <span class="text-text-tertiary">ذاكرة التخزين المؤقت</span>
                                <span class="text-text font-semibold">{{ $data['systemHealth']['cache_driver'] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <h3 class="font-bold text-text text-sm mb-3">إحصائيات المستخدمين</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between py-1.5 border-b border-border/50 text-sm">
                                <span class="text-text-tertiary">إجمالي المستخدمين</span>
                                <span class="text-text font-bold">{{ number_format($data['systemHealth']['users_count']) }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 border-b border-border/50 text-sm">
                                <span class="text-text-tertiary">المستخدمين النشطين</span>
                                <span class="text-green-600 font-bold">{{ number_format($data['systemHealth']['active_users']) }}</span>
                            </div>
                            <div class="flex justify-between py-1.5 text-sm">
                                <span class="text-text-tertiary">الأدوار</span>
                                <span class="text-text font-bold">{{ number_format($data['systemHealth']['roles_count']) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ═══════════════════════════════════════════════════════════
             FOOTER / LAST UPDATED
             ═══════════════════════════════════════════════════════════ --}}
        <div class="text-center py-4">
            <p class="text-[10px] text-text-muted font-medium">
                <i data-lucide="clock" class="w-3 h-3 inline ml-1"></i>
                آخر تحديث للوحة: {{ now()->translatedFormat('l d F Y - h:i A') }}
            </p>
        </div>

    </div>
</div>