@php
    $allNews = collect($latestNews ?? [])->take(6);
    $carouselConfig = \App\Domains\Homepage\Services\CarouselRegistry::getConfigArray('homepage-news');
    $resolvedTitle = $sectionTitle ?? $carouselConfig['title'] ?? 'آخر الأخبار';
    $resolvedSubtitle = $sectionSubtitle ?? $carouselConfig['subtitle'] ?? 'تابع آخر أخبار وفعاليات بلدية إذنا';

    $formatDay = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->format('d'); } catch (\Throwable) { return ''; }
    };
    $formatMonth = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('F'); } catch (\Throwable) { return ''; }
    };
    $formatFullDate = function ($date): string {
        if (empty($date)) return '';
        try { return \Carbon\Carbon::parse($date)->locale('ar')->translatedFormat('d F Y'); } catch (\Throwable) { return ''; }
    };
@endphp

<section id="news" class="overflow-hidden" style="background:#F8FAF8;padding-top:clamp(64px,6vw,100px);padding-bottom:clamp(64px,6vw,100px);">
    <div class="container-home">

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- SECTION HEADER                                           --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 sm:mb-14">
            <div class="flex items-center gap-4">
                <span class="hidden sm:flex w-1.5 h-12 rounded-full" style="background:#176B32;"></span>
                <div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-tight m-0" style="color:#0F1A14;">
                        {{ $resolvedTitle }}
                    </h2>
                    <p class="text-sm mt-1.5 m-0" style="color:#6B7B6E;">{{ $resolvedSubtitle }}</p>
                </div>
            </div>
            @if (Route::has('public.news.index'))
                <a href="{{ route('public.news.index') }}" wire:navigate
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold no-underline transition-all duration-200"
                   style="background:#176B32;color:white;"
                   onmouseover="this.style.background='#0D5A28'"
                   onmouseout="this.style.background='#176B32'">
                    <span>عرض جميع الأخبار</span>
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════════ --}}
        {{-- NEWS CAROUSEL                                             --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        @if ($allNews->isNotEmpty())
            @php
                $featuredNews = $allNews->first();
                $carouselNews = $allNews->skip(1)->values();
            @endphp

            {{-- Featured News Card --}}
            @if ($featuredNews)
                @php
                    $fDay = $formatDay($featuredNews['date'] ?? '');
                    $fMonth = $formatMonth($featuredNews['date'] ?? '');
                    $fFullDate = $formatFullDate($featuredNews['date'] ?? '');
                @endphp
                <a href="{{ !empty($featuredNews['url']) ? $featuredNews['url'] : '#' }}"
                   @if(!empty($featuredNews['url'])) wire:navigate @endif
                   class="group block bg-white rounded-2xl overflow-hidden no-underline transition-all duration-300 mb-8"
                   style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                   onmouseover="this.style.boxShadow='0 20px 50px rgba(23,107,50,0.10)';this.style.transform='translateY(-3px)'"
                   onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                    <div class="grid lg:grid-cols-2 gap-0">
                        {{-- Image --}}
                        <div class="relative overflow-hidden" style="aspect-ratio:16/9;min-height:240px;">
                            @if (!empty($featuredNews['image']))
                                <img src="{{ $featuredNews['image'] }}" alt="{{ $featuredNews['title'] ?? '' }}"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                     loading="eager">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);">
                                    <i data-lucide="newspaper" class="w-16 h-16" style="color:#176B32;opacity:0.15;"></i>
                                </div>
                            @endif
                            @if (!empty($featuredNews['category']))
                                <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background:#176B32;color:white;">
                                    {{ $featuredNews['category'] }}
                                </span>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="flex flex-col justify-center p-6 sm:p-8">
                            @if (!empty($featuredNews['category']))
                                <span class="inline-block px-2.5 py-0.5 rounded text-xs font-bold mb-3 w-fit" style="background:#E8F5E9;color:#176B32;">
                                    {{ $featuredNews['category'] }}
                                </span>
                            @endif
                            <h3 class="text-xl sm:text-2xl font-black leading-snug m-0" style="color:#0F1A14;">
                                {{ $featuredNews['title'] ?? '' }}
                            </h3>
                            @if (!empty($featuredNews['summary']))
                                <p class="text-sm mt-3 leading-relaxed line-clamp-3 m-0" style="color:#6B7B6E;">
                                    {{ $featuredNews['summary'] }}
                                </p>
                            @endif
                            <div class="flex items-center gap-4 mt-5">
                                @if ($fFullDate)
                                    <span class="flex items-center gap-1.5 text-xs font-medium" style="color:#8A9A8D;">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                        {{ $fFullDate }}
                                    </span>
                                @endif
                                <span class="flex items-center gap-1.5 text-sm font-bold" style="color:#176B32;">
                                    اقرأ المزيد
                                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @endif

            {{-- Remaining News Carousel --}}
            @if ($carouselNews->isNotEmpty())
                <div x-data="{
                    slides: @js($carouselNews->all()),
                    slider: null,
                    currentPage: 0,
                    canPrev: false,
                    canNext: false,
                    init() {
                        this.$nextTick(() => {
                            this.slider = this.$refs.track;
                            this.refresh();
                        });
                        window.addEventListener('resize', () => this.refresh(), { passive: true });
                    },
                    visible() {
                        return window.innerWidth >= 1280 ? 3 : window.innerWidth >= 768 ? 2 : 1;
                    },
                    step() {
                        const card = this.slider?.querySelector('.news-slide');
                        return card ? card.getBoundingClientRect().width + 24 : 0;
                    },
                    pages() {
                        return Math.max(1, Math.ceil(this.slides.length / this.visible()));
                    },
                    refresh() {
                        if (!this.slider) return;
                        const max = this.slider.scrollWidth - this.slider.clientWidth;
                        this.canPrev = this.slider.scrollLeft > 4;
                        this.canNext = this.slider.scrollLeft < max - 4;
                        this.currentPage = this.step() ? Math.round(this.slider.scrollLeft / (this.step() * this.visible())) : 0;
                    },
                    move(page) {
                        if (this.slider) this.slider.scrollTo({ left: page * this.step() * this.visible(), behavior: 'smooth' });
                    }
                }" dir="rtl">
                    <div class="relative">
                        <button x-show="canPrev" x-transition.opacity @click="move(Math.max(0, currentPage - 1))"
                                class="absolute -left-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]"
                                aria-label="السابق">
                            <i data-lucide="chevron-left" class="h-5 w-5"></i>
                        </button>

                        <div x-ref="track" @scroll.throttle.100ms="refresh()" tabindex="0" role="region"
                             aria-label="آخر الأخبار"
                             class="flex items-start gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#176B32]/30"
                             style="-ms-overflow-style:none;scrollbar-width:none;"
                             ::style="'-ms-overflow-style:none;scrollbar-width:none;'">
                            @foreach ($carouselNews as $index => $newsItem)
                                @php
                                    $sDay = $formatDay($newsItem['date'] ?? '');
                                    $sMonth = $formatMonth($newsItem['date'] ?? '');
                                @endphp
                                <div class="news-slide flex shrink-0 snap-start" style="flex:0 0 calc((100% - 48px) / 3);min-width:280px;">
                                    <a href="{{ !empty($newsItem['url']) ? $newsItem['url'] : '#' }}"
                                       @if(!empty($newsItem['url'])) wire:navigate @endif
                                       class="group flex flex-col w-full bg-white rounded-2xl overflow-hidden no-underline transition-all duration-300"
                                       style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                                       onmouseover="this.style.boxShadow='0 20px 50px rgba(23,107,50,0.10)';this.style.transform='translateY(-3px)'"
                                       onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                        {{-- Image --}}
                                        <div class="relative overflow-hidden" style="aspect-ratio:16/10;">
                                            @if (!empty($newsItem['image']))
                                                <img src="{{ $newsItem['image'] }}" alt="{{ $newsItem['title'] ?? '' }}"
                                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                                     loading="lazy">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);">
                                                    <i data-lucide="newspaper" class="w-12 h-12" style="color:#176B32;opacity:0.15;"></i>
                                                </div>
                                            @endif
                                            @if (!empty($newsItem['category']))
                                                <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full text-[11px] font-bold" style="background:#176B32;color:white;">
                                                    {{ $newsItem['category'] }}
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex flex-col flex-1 p-5">
                                            <h3 class="text-sm font-black leading-snug line-clamp-2 m-0" style="color:#0F1A14;">
                                                {{ $newsItem['title'] ?? '' }}
                                            </h3>
                                            @if (!empty($newsItem['summary']))
                                                <p class="text-xs mt-2 leading-relaxed line-clamp-2 m-0" style="color:#6B7B6E;">
                                                    {{ $newsItem['summary'] }}
                                                </p>
                                            @endif
                                            <div class="flex items-center gap-2 mt-auto pt-3" style="border-top:1px solid #F0F4F0;">
                                                @if ($sDay && $sMonth)
                                                    <span class="flex items-center gap-1 text-[11px] font-medium" style="color:#8A9A8D;">
                                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                                        {{ $sDay }} {{ $sMonth }}
                                                    </span>
                                                @endif
                                                <span class="flex items-center gap-1 text-xs font-bold mr-auto" style="color:#176B32;">
                                                    اقرأ المزيد
                                                    <i data-lucide="arrow-left" class="w-3 h-3"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <button x-show="canNext" x-transition.opacity @click="move(currentPage + 1)"
                                class="absolute -right-4 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-[#E9EFEA] bg-white text-[#176B32] shadow-lg transition hover:bg-[#F0F7F2]"
                                aria-label="التالي">
                            <i data-lucide="chevron-right" class="h-5 w-5"></i>
                        </button>
                    </div>

                    {{-- Dots --}}
                    <div class="mt-6 flex items-center justify-center gap-2" x-show="pages() > 1">
                        <template x-for="index in pages()" :key="index">
                            <button @click="move(index - 1)"
                                    :class="currentPage === index - 1 ? 'h-2 w-7 rounded bg-[#176B32]' : 'h-2 w-2 rounded-full bg-[#DDE5DC]'"
                                    class="transition-all border-none cursor-pointer"
                                    :aria-label="'الانتقال إلى مجموعة ' + index"></button>
                        </template>
                    </div>
                </div>

                <style>
                    .news-slide::-webkit-scrollbar { display: none; }
                    @media (max-width: 1279px) {
                        .news-slide { flex: 0 0 calc((100% - 24px) / 2) !important; min-width: 260px !important; }
                    }
                    @media (max-width: 639px) {
                        .news-slide { flex: 0 0 88% !important; min-width: 0 !important; }
                    }
                    @media (prefers-reduced-motion: reduce) {
                        .news-slide { scroll-behavior: auto; }
                    }
                </style>
            @endif
        @else
            <div class="rounded-2xl border border-dashed border-[#DDE5DC] bg-white py-16 text-center">
                <i data-lucide="newspaper" class="mx-auto h-10 w-10" style="color:#176B32;opacity:0.3;"></i>
                <p class="mt-3 text-sm font-semibold" style="color:#6B7B6E;">لا توجد أخبار منشورة حالياً</p>
            </div>
        @endif

    </div>
</section>
