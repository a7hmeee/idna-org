@extends('layouts.dashboard')

@section('content')

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
    @endphp
    @foreach ($statCards as $i => $card)
    <x-dashboard.stat-card
        :icon="$card['icon']"
        :color="$card['color']"
        :key="$card['key']"
        :label="$card['label']"
        :trend="$card['trend']"
        :trendUp="$card['trendUp']"
        :sparkline="$card['sparkline']"
        :sparkColor="$card['sparkColor']"
        :delay="min(($i % 4) * 100 + 100, 500)"
    />
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
    <x-dashboard.card title="آخر الشكاوى" subtitle="غير المقروءة" actionText="عرض الكل" class="lg:col-span-1">
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
    </x-dashboard.card>

    {{-- Recent Activity Timeline --}}
    <x-dashboard.card title="آخر النشاطات" subtitle="اليوم" actionText="عرض الكل" class="lg:col-span-1">
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
    </x-dashboard.card>

    {{-- Departments Overview --}}
    <x-dashboard.card title="الأقسام" subtitle="نظرة عامة" actionText="عرض الكل" class="lg:col-span-1">
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
    </x-dashboard.card>
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

@endsection
