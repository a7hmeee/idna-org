@php
    $statusConfig = [
        'available' => ['label' => 'متوفرة', 'color' => 'bg-success-light text-success'],
        'low_pressure' => ['label' => 'ضغط منخفض', 'color' => 'bg-accent-light/30 text-accent-dark'],
        'maintenance' => ['label' => 'صيانة', 'color' => 'bg-warning-light text-warning'],
        'emergency' => ['label' => 'طارئ', 'color' => 'bg-danger-light text-danger'],
        'no_water' => ['label' => 'مقطوعة', 'color' => 'bg-surface-hover text-text-secondary'],
    ];
    $todaySchedules = collect($waterSchedule)->take(6);
@endphp

@if ($todaySchedules->isNotEmpty())
<section data-reveal id="water-schedule" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        {{-- Section Header --}}
        <x-home.section-head
            eyebrow="جدول توزيع المياه"
            eyebrowIcon="droplets"
            :title="$sectionTitle ?? 'جدول توزيع المياه'"
            :subtitle="$sectionSubtitle ?? null"
            :actionUrl="Route::has('public.water-schedule') ? route('public.water-schedule') : null"
            actionLabel="عرض الجدول الكامل"
        />

        {{-- Dashboard Card --}}
        <div class="bg-gradient-to-br from-[#F4F5F1] to-white rounded-3xl border border-border/60 p-8 sm:p-10 shadow-lg">
            <div class="grid lg:grid-cols-12 gap-8 items-start">
                {{-- Left: Info --}}
                <div class="lg:col-span-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center">
                            <i data-lucide="calendar-clock" class="w-7 h-7 text-white"></i>
                        </div>
                        <div>
                            <p class="font-black text-text text-lg">جدول توزيع المياه</p>
                            <p class="text-xs text-text-muted">{{ now()->locale('ar')->translatedFormat('l d F Y') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach ($statusConfig as $key => $cfg)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $cfg['color'] }}">{{ $cfg['label'] }}</span>
                        @endforeach
                    </div>
                    <div class="mt-6 p-4 rounded-2xl bg-accent-light/20 border border-accent/20">
                        <p class="text-xs text-accent-dark flex items-center gap-1.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 flex-shrink-0"></i>
                            قد تتغير المواعيد وفق الظروف الفنية
                        </p>
                    </div>
                </div>

                {{-- Right: Schedule Table --}}
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl border border-border/50 overflow-hidden shadow-sm">
                        <div class="grid grid-cols-12 gap-0">
                            {{-- Header --}}
                            <div class="col-span-12 grid grid-cols-12 gap-0 px-5 py-3 bg-[#F4F5F1] border-b border-border/50 text-[10px] font-bold text-text-muted">
                                <div class="col-span-5">المنطقة</div>
                                <div class="col-span-3 text-center">التوقيت</div>
                                <div class="col-span-2 text-center">الحالة</div>
                                <div class="col-span-2 text-left">ملاحظات</div>
                            </div>
                            {{-- Rows --}}
                            @foreach ($todaySchedules as $schedule)
                                @php
                                    $areaName = $schedule['area']['name'] ?? 'منطقة غير محددة';
                                    $status = $schedule['status'] ?? '';
                                    $statusStr = is_object($status) ? ($status->value ?? $status) : (string) $status;
                                    $statusInfo = $statusConfig[$statusStr] ?? ['label' => $statusStr, 'color' => 'bg-surface-hover text-text-secondary'];
                                    $start = $schedule['start_time'] ?? '';
                                    $end = $schedule['end_time'] ?? '';
                                    $notes = $schedule['notes'] ?? '';
                                @endphp
                                <div class="col-span-12 grid grid-cols-12 gap-0 px-5 py-4 border-b border-border/30 last:border-0 hover:bg-primary-light/20 transition-colors items-center">
                                    <div class="col-span-5 flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                                        </div>
                                        <span class="text-sm font-bold text-text">{{ $areaName }}</span>
                                    </div>
                                    <div class="col-span-3 text-center">
                                        <span class="text-xs font-semibold text-text inline-flex items-center gap-1">
                                            <i data-lucide="clock" class="w-3 h-3 text-primary"></i>
                                            {{ $start }}@if($end) - {{ $end }}@endif
                                        </span>
                                    </div>
                                    <div class="col-span-2 text-center">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold whitespace-nowrap {{ $statusInfo['color'] }}">{{ $statusInfo['label'] }}</span>
                                    </div>
                                    <div class="col-span-2 text-left">
                                        @if ($notes)
                                            <span class="text-[10px] text-text-muted">{{ $notes }}</span>
                                        @else
                                            <span class="text-[10px] text-text-muted">—</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
