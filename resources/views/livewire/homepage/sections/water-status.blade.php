@php
    $statusConfig = [
        'available'     => ['label' => 'متوفر',   'color' => '#176B32', 'bg' => '#EAF5EE', 'dot' => '#176B32'],
        'low_pressure'  => ['label' => 'ضغط منخفض', 'color' => '#B45309', 'bg' => '#FEF3C7', 'dot' => '#B45309'],
        'maintenance'   => ['label' => 'صيانة',    'color' => '#B45309', 'bg' => '#FEF3C7', 'dot' => '#B45309'],
        'emergency'     => ['label' => 'طارئ',     'color' => '#DC2626', 'bg' => '#FEE2E2', 'dot' => '#DC2626'],
        'no_water'      => ['label' => 'مقطوع',    'color' => '#6B7280', 'bg' => '#F3F4F6', 'dot' => '#D1D5DB'],
    ];

    $schedules = collect($waterSchedule);
    $hasData = $schedules->isNotEmpty();

    $todayDayName = now()->locale('ar')->translatedFormat('l');
    $todayDateShort = now()->locale('ar')->translatedFormat('d/m/Y');
    $scheduleDate = $hasData ? \Carbon\Carbon::parse($schedules->first()['schedule_date'] ?? now())->locale('ar')->translatedFormat('d/m/Y') : null;
    $isToday = $hasData && \Carbon\Carbon::parse($schedules->first()['schedule_date'] ?? now())->isToday();
@endphp

<section id="water-schedule" style="background: #F8F9F6; padding: 1.5rem 0;">
    <div class="container-home">

        {{-- Header Row --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2.5">
                <span class="w-1 h-5 rounded-full" style="background: var(--color-primary);"></span>
                <h2 class="text-lg font-black" style="color: #1A1F16;">{{ $sectionTitle ?? 'جدول توزيع المياه' }}</h2>
            </div>
            <div class="flex items-center gap-2">
                @if ($hasData)
                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded" style="background: #EAF5EE; color: #176B32;">
                        {{ $schedules->count() }} منطقة
                    </span>
                @endif
                @if (Route::has('public.water-schedule'))
                    <a href="{{ route('public.water-schedule') }}" wire:navigate
                       class="inline-flex items-center gap-0.5 text-[11px] font-semibold transition-opacity"
                       style="color: var(--color-primary);"
                       onmouseover="this.style.opacity='0.6'" onmouseout="this.style.opacity='1'">
                        عرض الجدول الكامل
                        <i data-lucide="chevron-left" class="w-3 h-3"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Schedule List --}}
        @if ($hasData)
            <div class="rounded-xl overflow-hidden" style="background: white; border: 1px solid #E5E7E0;">

                {{-- Top Info Strip --}}
                <div class="flex items-center gap-3 px-4 py-2" style="background: #FAFAF8; border-bottom: 1px solid #EEF0EB;">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="calendar" class="w-3 h-3" style="color: var(--color-primary);"></i>
                        <span class="text-[11px] font-semibold" style="color: #1A1F16;">{{ $todayDayName }} {{ $todayDateShort }}</span>
                    </div>
                    @if ($hasData && $scheduleDate)
                        <span class="text-[10px]" style="color: #D1D5CB;">|</span>
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-2.5 h-2.5" style="color: #9CA3AF;"></i>
                            <span class="text-[10px]" style="color: #6B7562;">آخر تحديث {{ $scheduleDate }}</span>
                        </div>
                    @endif
                    @if ($isToday)
                        <span class="inline-flex items-center gap-1 mr-auto">
                            <span class="w-1.5 h-1.5 rounded-full" style="background: #176B32;"></span>
                            <span class="text-[10px] font-bold" style="color: #176B32;">جدول اليوم</span>
                        </span>
                    @endif
                </div>

                {{-- Entries --}}
                <div>
                    @foreach ($schedules as $index => $schedule)
                        @php
                            $areaName = $schedule['area']['name'] ?? '—';
                            $status = $schedule['status'] ?? '';
                            $statusStr = is_object($status) ? ($status->value ?? (string) $status) : (string) $status;
                            $statusInfo = $statusConfig[$statusStr] ?? ['label' => $statusStr, 'color' => '#6B7562', 'bg' => '#F3F4F6', 'dot' => '#D1D5DB'];
                            $start = $schedule['start_time'] ?? '';
                            $end = $schedule['end_time'] ?? '';
                            $isLast = $index === $schedules->count() - 1;
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-2.5"
                             style="{{ $isLast ? '' : 'border-bottom: 1px solid #F3F4F6;' }}">
                            {{-- Timeline Dot --}}
                            <div class="flex-shrink-0 relative">
                                <span class="block w-2 h-2 rounded-full" style="background: {{ $statusInfo['dot'] }};"></span>
                            </div>

                            {{-- Area Name --}}
                            <div class="flex items-center gap-1.5 min-w-0 flex-shrink-0" style="width: 30%;">
                                <i data-lucide="map-pin" class="w-3 h-3 flex-shrink-0" style="color: #9CA3AF;"></i>
                                <span class="text-sm font-bold truncate" style="color: #1A1F16;">{{ $areaName }}</span>
                            </div>

                            {{-- Time --}}
                            <div class="flex items-center gap-1.5 flex-1 min-w-0 justify-center">
                                @if ($start && $end)
                                    <span class="text-[13px] font-medium" style="color: #374151;" dir="ltr">{{ $start }} — {{ $end }}</span>
                                @elseif ($start)
                                    <span class="text-[13px] font-medium" style="color: #374151;" dir="ltr">{{ $start }}</span>
                                @else
                                    <span class="text-[10px]" style="color: #D1D5CB;">—</span>
                                @endif
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                      style="background: {{ $statusInfo['bg'] }}; color: {{ $statusInfo['color'] }};">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background: {{ $statusInfo['dot'] }};"></span>
                                    {{ $statusInfo['label'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="flex items-center gap-1.5 px-4 py-1.5" style="background: #FAFAF8; border-top: 1px solid #EEF0EB;">
                    <i data-lucide="info" class="w-2.5 h-2.5" style="color: #D1D5CB;"></i>
                    <span class="text-[9px]" style="color: #9CA3AF;">قد تتغير المواعيد وفق الظروف الفنية</span>
                </div>
            </div>

        @else
            {{-- Empty State --}}
            <div class="rounded-xl px-4 py-6 text-center" style="background: white; border: 1px solid #E5E7E0;">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mx-auto mb-2" style="background: #F3F4F6;">
                    <i data-lucide="droplets" class="w-4 h-4" style="color: #9CA3AF;"></i>
                </div>
                <p class="text-xs font-bold mb-0.5" style="color: #1A1F16;">لا يوجد جدول توزيع منشور حالياً</p>
                <p class="text-[10px]" style="color: #6B7562;">سيتم نشره فور توفره</p>
            </div>
        @endif

    </div>
</section>
