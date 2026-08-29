<div>
    {{-- Print-only rules; screen rendering unaffected --}}
    <style>
        @media print {
            .bg-gradient-to-l { background: #ffffff !important; border-bottom: 1px solid #DDE5DC; }
            .bg-gradient-to-l h3, .bg-gradient-to-l p, .bg-gradient-to-l span { color: #13251C !important; }
            .bg-white\/20 { background: #EAF3EC !important; }
        }
    </style>
    <div class="print:hidden">
    @livewire('public-page-carousel', [
        'pageKey' => 'water-schedule',
        'fallbackTitle' => "جدول توزيع المياه",
        'fallbackDescription' => "تفقد جدول الضخ الأسبوعي للمياه في مختلف مناطق بلدية إذنا.",
        'fallbackBadge' => 'جدول المياه',
        'fallbackIcon' => 'droplets',
        'fallbackImage' => $slides->isNotEmpty() ? $slides->first()->image_url : null,
        'compact' => true,
    ])
    </div>

    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                        <i data-lucide="droplets" class="w-3.5 h-3.5"></i>
                        جدول توزيع المياه
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight">جدول ضخ المياه</h2>
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed">تابع مواعيد ضخ المياه في جميع مناطق بلدية إذنا</p>
                </div>
            </div>

            @if ($activeMaintenance)
                <div class="mb-8 rounded-2xl border-2 border-red-300 bg-red-50 p-5 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-red-800 text-lg">{{ $activeMaintenance->title }}</h3>
                            @if ($activeMaintenance->description)
                                <p class="text-red-700 text-sm mt-1">{{ $activeMaintenance->description }}</p>
                            @endif
                            @if ($activeMaintenance->affected_areas)
                                <div class="mt-2">
                                    <p class="text-xs font-semibold text-red-700">المناطق المتأثرة:</p>
                                    <div class="flex flex-wrap gap-1.5 mt-1">
                                        @foreach ($activeMaintenance->affected_areas as $area)
                                            <span class="inline-block bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium">{{ $area }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (empty($areaSchedules))
                <div class="bg-white rounded-3xl border border-border/50 p-12 text-center shadow-lg">
                    <div class="w-20 h-20 rounded-full bg-primary-light flex items-center justify-center mx-auto mb-5">
                        <i data-lucide="droplets" class="w-10 h-10 text-primary"></i>
                    </div>
                    <h3 class="font-bold text-text text-xl mb-2">لا توجد جداول مياه حالياً</h3>
                    <p class="text-sm text-text-secondary max-w-md mx-auto">لم يتم إنشاء جداول ضخ المياه بعد. يرجى المحاولة لاحقاً.</p>
                </div>
            @else
                @php
                    $statusConfig = [
                        'available' => ['label' => 'متوفر', 'color' => 'bg-green-500', 'bg' => 'bg-green-50 border-green-200'],
                        'low_pressure' => ['label' => 'ضغط منخفض', 'color' => 'bg-yellow-500', 'bg' => 'bg-yellow-50 border-yellow-200'],
                        'maintenance' => ['label' => 'صيانة', 'color' => 'bg-orange-500', 'bg' => 'bg-orange-50 border-orange-200'],
                        'emergency' => ['label' => 'طارئ', 'color' => 'bg-red-500', 'bg' => 'bg-red-50 border-red-200'],
                        'no_water' => ['label' => 'لا يوجد ضخ', 'color' => 'bg-gray-500', 'bg' => 'bg-gray-50 border-gray-200'],
                    ];
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($areaSchedules as $data)
                        @php
                            $area = $data['area'];
                            $current = $data['current'];
                            $history = $data['history'];
                            $status = $current?->status?->value ?? 'available';
                            $statusInfo = $statusConfig[$status] ?? $statusConfig['available'];
                        @endphp
                        <div class="bg-white rounded-3xl border border-border/50 overflow-hidden shadow-lg">
                            {{-- Area Header --}}
                            <div class="bg-gradient-to-l from-[#0F4F28] to-[#0B3A24] px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                            <i data-lucide="map-pin" class="w-5 h-5 text-white"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-white text-lg">{{ $area->name }}</h3>
                                            @if ($current)
                                                <p class="text-xs text-white/70">{{ $todayDayName }} — {{ now()->format('d/m/Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($current)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-white/20 text-white">
                                            <span class="w-2 h-2 rounded-full {{ $statusInfo['color'] }}"></span>
                                            {{ $statusInfo['label'] }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if ($current)
                                {{-- Time Display --}}
                                <div class="px-6 py-6">
                                    <div class="flex items-center justify-center gap-6">
                                        <div class="text-center">
                                            <div class="w-16 h-16 rounded-2xl bg-primary-light flex items-center justify-center mx-auto mb-2">
                                                <i data-lucide="sun" class="w-8 h-8 text-primary"></i>
                                            </div>
                                            <p class="text-2xl font-black text-text">{{ $current->start_time ? \Carbon\Carbon::parse($current->start_time)->format('h:i') : '—' }}</p>
                                            <p class="text-[11px] text-text-tertiary mt-0.5">{{ $current->start_time ? (\Carbon\Carbon::parse($current->start_time)->format('A') === 'AM' ? 'صباحًا' : 'مساءً') : '' }}</p>
                                        </div>
                                        <div class="flex flex-col items-center">
                                            <div class="w-10 h-10 rounded-full bg-border/30 flex items-center justify-center">
                                                <i data-lucide="arrow-left" class="w-5 h-5 text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="w-16 h-16 rounded-2xl bg-accent-light/30 flex items-center justify-center mx-auto mb-2">
                                                <i data-lucide="moon" class="w-8 h-8 text-accent-dark"></i>
                                            </div>
                                            <p class="text-2xl font-black text-text">{{ $current->end_time ? \Carbon\Carbon::parse($current->end_time)->format('h:i') : '—' }}</p>
                                            <p class="text-[11px] text-text-tertiary mt-0.5">{{ $current->end_time ? (\Carbon\Carbon::parse($current->end_time)->format('A') === 'AM' ? 'صباحًا' : 'مساءً') : '' }}</p>
                                        </div>
                                    </div>
                                    @if ($current->notes)
                                        <div class="mt-4 p-3 rounded-xl bg-surface-secondary border border-border/50">
                                            <div class="flex items-start gap-2">
                                                <i data-lucide="info" class="w-3.5 h-3.5 text-text-muted mt-0.5 shrink-0"></i>
                                                <p class="text-xs text-text-secondary">{{ $current->notes }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- History --}}
                                @if (count($history) > 0)
                                    <div class="border-t border-border/30 px-6 py-4">
                                        <p class="text-[11px] font-bold text-text-muted mb-3">الجدول السابق</p>
                                        <div class="space-y-2">
                                            @foreach ($history as $h)
                                                @php
                                                    $hDate = $h['schedule_date'] ?? '';
                                                    $hDay = '';
                                                    if ($hDate) {
                                                        try { $hDay = \Carbon\Carbon::parse($hDate)->locale('ar')->translatedFormat('l'); } catch (\Throwable $e) { $hDay = $hDate; }
                                                    }
                                                    $hSt = $h['status'] ?? 'available';
                                                    $hStInfo = $statusConfig[$hSt] ?? $statusConfig['available'];
                                                @endphp
                                                <div class="flex items-center justify-between py-1.5">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-lg {{ \Carbon\Carbon::parse($hDate)->isToday() ? 'bg-primary/10' : 'bg-surface-secondary' }} flex flex-col items-center justify-center shrink-0">
                                                            <span class="text-[11px] font-bold text-text-muted leading-none">{{ $hDay }}</span>
                                                            <span class="text-sm font-black text-text leading-none mt-0.5">{{ $hDate ? \Carbon\Carbon::parse($hDate)->format('d') : '—' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            @if ($h['start_time'] ?? null)
                                                                <span class="text-xs text-text-secondary">
                                                                    {{ \Carbon\Carbon::parse($h['start_time'])->format('h:i') }} — {{ \Carbon\Carbon::parse($h['end_time'])->format('h:i') }}
                                                                </span>
                                                            @else
                                                                <span class="text-xs text-text-muted">غير محدد</span>
                                                            @endif
                                                            @if ($hDate && \Carbon\Carbon::parse($hDate)->isToday())
                                                                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-bold">اليوم</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold {{ $hStInfo['color'] }}">
                                                        {{ $hStInfo['label'] }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="px-6 py-8 text-center">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="calendar-x" class="w-6 h-6 text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-text-secondary">لا يوجد جدول متاح لهذه المنطقة</p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="text-center pt-8 mt-12 border-t border-border/30 print:hidden">
                <p class="text-xs text-text-muted">جميع الحقوق محفوظة &copy; {{ date('Y') }} بلدية إذنا</p>
            </div>
        </div>
    </section>
</div>
