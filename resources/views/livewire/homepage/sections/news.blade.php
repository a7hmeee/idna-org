@php
    $allNews = collect($latestNews)->take(4);
    $featuredNews = $allNews->first();
    $secondaryNews = $allNews->skip(1)->take(3);
    $carouselConfig = \App\Domains\Homepage\Services\CarouselRegistry::getConfigArray('homepage-news');
    $resolvedTitle = $sectionTitle ?? $carouselConfig['title'] ?? 'آخر الأخبار';
    $resolvedSubtitle = $sectionSubtitle ?? $carouselConfig['subtitle'] ?? 'تابع آخر أخبار وفعاليات بلدية إذنا';

    $emergencyItems = collect($municipality['emergency_contacts'] ?? [])->take(4);
    $fallbackEmergency = collect([
        ['name' => 'الشرطة', 'phone' => '100'],
        ['name' => 'الدفاع المدني', 'phone' => '101'],
        ['name' => 'الإسعاف الطبي', 'phone' => '102'],
        ['name' => 'طوارئ البلدية', 'phone' => '106'],
    ]);
    $emergencyItems = $emergencyItems->isNotEmpty() ? $emergencyItems : $fallbackEmergency;

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
        {{-- MAIN GRID: NEWS + SIDEBAR                                --}}
        {{-- ═══════════════════════════════════════════════════════════ --}}
        <div class="grid xl:grid-cols-12 gap-8 lg:gap-10 items-start">

            {{-- ──────────── NEWS AREA (9 cols) ──────────── --}}
            <div class="xl:col-span-9">
                @if ($allNews->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                        {{-- ═══ FEATURED NEWS ═══ --}}
                        @if ($featuredNews)
                            @php
                                $day = $formatDay($featuredNews['date'] ?? '');
                                $month = $formatMonth($featuredNews['date'] ?? '');
                                $fullDate = $formatFullDate($featuredNews['date'] ?? '');
                            @endphp
                            <a href="{{ !empty($featuredNews['url']) ? $featuredNews['url'] : '#' }}"
                               @if(!empty($featuredNews['url'])) wire:navigate @endif
                               class="group block lg:col-span-7 bg-white rounded-2xl overflow-hidden no-underline transition-all duration-300"
                               style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                               onmouseover="this.style.boxShadow='0 20px 50px rgba(23,107,50,0.10)';this.style.transform='translateY(-3px)'"
                               onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                <div class="relative overflow-hidden" style="aspect-ratio:16/9;">
                                    @if (!empty($featuredNews['image']))
                                        <img src="{{ $featuredNews['image'] }}" alt="{{ $featuredNews['title'] ?? '' }}"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                             loading="eager">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);">
                                            <i data-lucide="newspaper" class="w-16 h-16" style="color:#176B32;opacity:0.15;"></i>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,0.55) 0%,transparent 50%;"></div>

                                    {{-- Category Badge --}}
                                    @if (!empty($featuredNews['category']))
                                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background:#176B32;color:white;">
                                            {{ $featuredNews['category'] }}
                                        </span>
                                    @endif

                                    {{-- Bottom overlay content --}}
                                    <div class="absolute bottom-0 right-0 left-0 p-5 sm:p-6">
                                        <h3 class="text-lg sm:text-xl lg:text-2xl font-black text-white leading-snug m-0 line-clamp-2">
                                            {{ $featuredNews['title'] ?? '' }}
                                        </h3>
                                        @if (!empty($featuredNews['summary']))
                                            <p class="text-sm mt-2 leading-relaxed m-0 line-clamp-2" style="color:rgba(255,255,255,0.85);">
                                                {{ $featuredNews['summary'] }}
                                            </p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-3">
                                            @if ($fullDate)
                                                <span class="flex items-center gap-1.5 text-xs font-medium" style="color:rgba(255,255,255,0.75);">
                                                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                                    {{ $fullDate }}
                                                </span>
                                            @endif
                                            <span class="flex items-center gap-1 text-xs font-bold" style="color:#A5D6A7;">
                                                اقرأ المزيد
                                                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endif

                        {{-- ═══ SECONDARY NEWS ═══ --}}
                        @if ($secondaryNews->isNotEmpty())
                            <div class="lg:col-span-5 flex flex-col gap-5">
                                @foreach ($secondaryNews as $newsItem)
                                    @php
                                        $sDay = $formatDay($newsItem['date'] ?? '');
                                        $sMonth = $formatMonth($newsItem['date'] ?? '');
                                    @endphp
                                    <a href="{{ !empty($newsItem['url']) ? $newsItem['url'] : '#' }}"
                                       @if(!empty($newsItem['url'])) wire:navigate @endif
                                       class="group flex gap-4 bg-white rounded-xl p-4 no-underline transition-all duration-200"
                                       style="box-shadow:0 1px 3px rgba(0,0,0,0.04);"
                                       onmouseover="this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)';this.style.transform='translateY(0)'">
                                        {{-- Image --}}
                                        <div class="flex-shrink-0 rounded-lg overflow-hidden" style="width:110px;height:90px;">
                                            @if (!empty($newsItem['image']))
                                                <img src="{{ $newsItem['image'] }}" alt="{{ $newsItem['title'] ?? '' }}"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                     loading="lazy">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center" style="background:#E8F5E9;">
                                                    <i data-lucide="image" class="w-6 h-6" style="color:#176B32;opacity:0.2;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                            <div>
                                                @if (!empty($newsItem['type']))
                                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mb-1.5" style="background:#E8F5E9;color:#176B32;">
                                                        {{ $newsItem['type'] }}
                                                    </span>
                                                @endif
                                                <h4 class="text-sm font-bold leading-snug line-clamp-2 m-0" style="color:#0F1A14;">
                                                    {{ $newsItem['title'] ?? '' }}
                                                </h4>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                @if ($sDay && $sMonth)
                                                    <span class="flex items-center gap-1 text-[11px] font-medium" style="color:#8A9A8D;">
                                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                                        {{ $sDay }} {{ $sMonth }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                    </div>
                @else
                    <x-empty-state-section icon="newspaper" title="لا توجد أخبار منشورة حالياً" description="سيتم إضافة الأخبار فور نشرها" />
                @endif
            </div>

            {{-- ──────────── SIDEBAR (3 cols) ──────────── --}}
            <aside class="xl:col-span-3 flex flex-col gap-6" aria-label="الإعلانات وأرقام الطوارئ">

                {{-- ═══ ANNOUNCEMENTS ═══ --}}
                @php $announcementsList = collect($latestAnnouncements ?? [])->take(3); @endphp
                <div class="bg-white rounded-2xl overflow-hidden" style="box-shadow:0 1px 3px rgba(0,0,0,0.04);">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid #F0F4F0;">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#E8F5E9;">
                                <i data-lucide="megaphone" class="w-4 h-4" style="color:#176B32;"></i>
                            </span>
                            <h3 class="text-sm font-black m-0" style="color:#0F1A14;">الإعلانات</h3>
                        </div>
                        @if (Route::has('public.announcements.index'))
                            <a href="{{ route('public.announcements.index') }}" wire:navigate
                               class="text-xs font-bold no-underline transition-colors" style="color:#176B32;"
                               onmouseover="this.style.color='#0D5A28'"
                               onmouseout="this.style.color='#176B32'">
                                عرض الكل
                            </a>
                        @endif
                    </div>
                    {{-- List --}}
                    @if ($announcementsList->isNotEmpty())
                        <div>
                            @foreach ($announcementsList as $index => $announcement)
                                <a href="{{ !empty($announcement['url']) ? $announcement['url'] : (Route::has('public.announcements.index') ? route('public.announcements.index') : '#') }}"
                                   @if(!empty($announcement['url'])) wire:navigate @endif
                                   class="flex items-start gap-3 px-5 py-3.5 no-underline transition-colors duration-150"
                                   style="{{ $index < $announcementsList->count() - 1 ? 'border-bottom:1px solid #F0F4F0;' : '' }}"
                                   onmouseover="this.style.background='#F8FAF8'"
                                   onmouseout="this.style.background='transparent'">
                                    <span class="w-6 h-6 rounded flex items-center justify-center flex-shrink-0 mt-0.5" style="background:#E8F5E9;">
                                        <i data-lucide="megaphone" class="w-3 h-3" style="color:#176B32;"></i>
                                    </span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block text-[13px] font-bold leading-snug line-clamp-2" style="color:#0F1A14;">
                                            {{ $announcement['title'] ?? '' }}
                                        </span>
                                        @if (!empty($announcement['date']))
                                            <span class="block text-[11px] mt-1" style="color:#8A9A8D;">
                                                {{ $announcement['date'] }}
                                            </span>
                                        @endif
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="px-5 py-6 text-center">
                            <p class="text-xs m-0" style="color:#8A9A8D;">لا توجد إعلانات حالياً</p>
                        </div>
                    @endif
                </div>

                {{-- ═══ EMERGENCY NUMBERS ═══ --}}
                <div class="rounded-2xl overflow-hidden" style="background:#176B32;">
                    {{-- Header --}}
                    <div class="flex items-center gap-2.5 px-5 py-4">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.15);">
                            <i data-lucide="phone-call" class="w-4 h-4 text-white"></i>
                        </span>
                        <h3 class="text-sm font-black text-white m-0">أرقام الطوارئ</h3>
                    </div>
                    {{-- List --}}
                    <div class="px-3 pb-3">
                        @foreach ($emergencyItems as $index => $emergency)
                            @php
                                $name = $emergency['name'] ?? $emergency['department'] ?? '';
                                $phone = $emergency['phone'] ?? '';
                            @endphp
                            <a href="{{ $phone ? 'tel:' . preg_replace('/\s+/', '', $phone) : '#' }}"
                               class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 no-underline transition-all duration-150"
                               style="{{ $index < $emergencyItems->count() - 1 ? 'border-bottom:1px solid rgba(255,255,255,0.12);' : '' }}"
                               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                               onmouseout="this.style.background='transparent'">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(255,255,255,0.12);">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-white"></i>
                                </span>
                                <span class="flex-1 min-w-0 text-[13px] font-bold text-white truncate">{{ $name }}</span>
                                <span class="text-sm font-black text-white flex-shrink-0" dir="ltr">{{ $phone }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

            </aside>
        </div>

    </div>
</section>
