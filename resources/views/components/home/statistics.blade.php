@props([
    'statistics' => [],
    'autoStatistics' => [],
    'statisticsBg' => null,
    'sectionTitle' => null,
    'sectionSubtitle' => null,
])

@php
    $allStats = collect(array_merge(
        is_array($statistics) ? $statistics : [],
        is_array($autoStatistics) ? $autoStatistics : []
    ))->filter(function ($stat) {
        return !empty($stat['value']) && $stat['value'] !== '0';
    })->take(6);
@endphp

@if ($allStats->isNotEmpty())
    <section id="statistics" class="relative overflow-hidden band-pine">
        {{-- Depth texture + optional bg image --}}
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            @if (!empty($statisticsBg))
                <img src="{{ $statisticsBg }}" alt="" class="w-full h-full object-cover opacity-[0.05]" loading="lazy" decoding="async">
            @endif
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[640px] h-[340px] rounded-[100%]"
                 style="background:radial-gradient(closest-side, rgba(200,168,90,0.13), transparent);"></div>
            <div class="paper-dots absolute inset-0" style="opacity:0.4;"></div>
            <div class="absolute bottom-0 right-0 left-0 h-px" style="background:linear-gradient(90deg, transparent, rgba(200,168,90,0.6), transparent);"></div>
        </div>

        <div class="relative container-home section-py">
            <div class="text-center max-w-[640px] mx-auto mb-12 lg:mb-14" data-reveal>
                <span class="eyebrow-pill inline-flex" style="background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.22);color:#CBE7D3;">
                    <span class="eyebrow-pill-dot" style="background:#C8A85A;box-shadow:0 0 0 4px rgba(200,168,90,0.25);"></span>
                    <i data-lucide="bar-chart-3" class="w-3.5 h-3.5"></i>
                    إحصائيات البلدية
                </span>
                <h2 class="display-heading text-white mt-4" style="font-size:clamp(26px, 3.2vw, 40px);">
                    {{ $sectionTitle ?? 'إحصائيات بلدية إذنا' }}
                </h2>
                @if ($sectionSubtitle)
                    <p class="mt-3 text-sm sm:text-base leading-relaxed" style="color:rgba(255,255,255,0.6);">{{ $sectionSubtitle }}</p>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4" data-reveal data-reveal-delay="0.12">
                @foreach ($allStats as $stat)
                    <div class="group relative overflow-hidden rounded-2xl p-4 sm:p-5 text-center transition-all duration-300 hover:-translate-y-1"
                         style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);backdrop-filter:blur(8px);">
                        <div class="absolute inset-x-0 top-0 h-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                             style="background:linear-gradient(90deg, transparent, #C8A85A, transparent);"></div>

                        @if (!empty($stat['icon']))
                            <div class="w-11 h-11 rounded-2xl mx-auto mb-3 flex items-center justify-center"
                                 style="background:rgba(200,168,90,0.12);">
                                <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5" style="color:#C8A85A;"></i>
                            </div>
                        @endif

                        <p class="stat-value" style="font-size:clamp(26px, 2.6vw, 34px);">
                            <span data-count-up="{{ (int) ($stat['value'] ?? 0) }}">0</span>
                            @if (!empty($stat['suffix']))
                                <span class="font-bold" style="color:#C8A85A;font-size:0.55em;">{{ $stat['suffix'] }}</span>
                            @endif
                        </p>
                        <p class="text-xs font-semibold mt-2" style="color:rgba(255,255,255,0.85);">{{ $stat['label'] ?? '' }}</p>
                        @if (!empty($stat['description']))
                            <p class="text-[10px] mt-1 leading-relaxed" style="color:rgba(255,255,255,0.45);">{{ $stat['description'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif