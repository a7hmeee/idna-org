@php
    $allStats = collect(array_merge(
        is_array($statistics) ? $statistics : [],
        is_array($autoStatistics) ? $autoStatistics : []
    ))->filter(function ($stat) {
        return !empty($stat['value']) && $stat['value'] !== '0';
    })->take(6);
@endphp

@if ($allStats->isNotEmpty())
    <section id="statistics" class="relative overflow-hidden" style="background:#14233B;">
        <div class="absolute inset-0 pointer-events-none">
            @if (!empty($statisticsBg))
                <img src="{{ $statisticsBg }}" alt="" class="w-full h-full object-cover opacity-[0.04]" loading="lazy" decoding="async">
            @endif
            <div class="absolute top-0 right-0 w-96 h-96 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl" style="background:rgba(255,255,255,0.03);" aria-hidden="true"></div>
        </div>

        <div class="relative container-home py-14 lg:py-16">
            <div class="text-center mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold mb-3" style="background:rgba(255,255,255,0.08);color:#A5D6A7;">
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i>
                    إحصائيات البلدية
                </span>
                <h2 class="text-[clamp(30px,3.5vw,42px)] font-extrabold text-white leading-[1.2]">{{ $sectionTitle ?? 'إحصائيات بلدية إذنا' }}</h2>
                @if ($sectionSubtitle)
                    <p class="mt-3 text-sm sm:text-base max-w-2xl mx-auto" style="color:rgba(255,255,255,0.55);">{{ $sectionSubtitle }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach ($allStats as $stat)
                    <div class="text-center p-4 rounded-xl transition-all duration-200" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08);">
                        @if (!empty($stat['icon']))
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mx-auto mb-2" style="background:rgba(255,255,255,0.08);">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5" style="color:#A5D6A7;"></i>
                            </div>
                        @endif
                        <p class="text-3xl sm:text-4xl font-black text-white leading-none">
                            {{ number_format((int) ($stat['value'] ?? 0)) }}
                            @if (!empty($stat['suffix']))
                                <span class="text-base font-bold mr-0.5" style="color:#C8A85A;">{{ $stat['suffix'] }}</span>
                            @endif
                        </p>
                        <p class="text-sm font-medium mt-1.5" style="color:rgba(255,255,255,0.6);">{{ $stat['label'] ?? '' }}</p>
                        @if (!empty($stat['description']))
                            <p class="text-[10px] mt-0.5" style="color:rgba(255,255,255,0.3);">{{ $stat['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
