@php
    $allNews = collect($latestNews)->take(4);

    $emergencyItems = collect($municipality['emergency_contacts'] ?? [])->take(4);
    $fallbackEmergency = collect([
        ['name' => 'الطوارئ العامة', 'phone' => '911'],
        ['name' => 'الدفاع المدني', 'phone' => '102'],
        ['name' => 'الشرطة', 'phone' => '100'],
        ['name' => 'قسم المياه', 'phone' => '06-5810012'],
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
@endphp

<section id="news" class="bg-white overflow-hidden" style="padding-top:clamp(70px,7vw,110px);padding-bottom:clamp(70px,7vw,110px);">
    <div class="container-home">
        {{-- Header: يمين العنوان + يسار رابط "عرض جميع الأخبار" --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-10 sm:mb-12">
            <h2 class="text-3xl sm:text-4xl xl:text-[42px] font-black text-text leading-tight m-0">آخر الأخبار</h2>
            <a href="{{ Route::has('public.news.index') ? route('public.news.index') : '#' }}" wire:navigate
               class="inline-flex items-center gap-1.5 text-sm font-bold no-underline transition-colors hover:opacity-80"
               style="color:#2d6b3f;">
                <span>عرض جميع الأخبار</span>
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid xl:grid-cols-12 gap-6 lg:gap-8 items-start">
            {{-- ============ الأخبار: 4 بطاقات ============ --}}
            <div class="xl:col-span-9">
                @if ($allNews->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                        @foreach ($allNews as $newsItem)
                            @php
                                $day = $formatDay($newsItem['date'] ?? '');
                                $month = $formatMonth($newsItem['date'] ?? '');
                            @endphp
                            <a href="{{ !empty($newsItem['url']) ? $newsItem['url'] : '#' }}"
                               @if(!empty($newsItem['url'])) target="_blank" rel="noopener noreferrer" @endif
                               class="group block bg-white rounded-2xl overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline shadow-card">
                                <div class="relative aspect-[3/2] overflow-hidden">
                                    @if (!empty($newsItem['image']))
                                        <img src="{{ $newsItem['image'] }}" alt="{{ $newsItem['title'] ?? '' }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-primary-light to-surface-secondary flex items-center justify-center">
                                            <i data-lucide="image" class="w-10 h-10 text-primary/20"></i>
                                        </div>
                                    @endif
                                    {{-- Badge التاريخ: اليوم فوق الشهر --}}
                                    @if ($day && $month)
                                        <span class="absolute top-3 right-3 flex flex-col items-center justify-center rounded-lg text-white leading-none shadow-lg"
                                              style="background:#173f27;min-width:52px;padding:8px 10px;">
                                            <span class="text-xl font-black m-0">{{ $day }}</span>
                                            <span class="text-[10px] font-bold mt-1 m-0" style="color:rgba(255,255,255,0.9);">{{ $month }}</span>
                                        </span>
                                    @endif
                                </div>
                                <div class="p-4 sm:p-5">
                                    @if (!empty($newsItem['type']))
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold inline-block mb-2" style="background:#eef6ef;color:#173f27;">{{ $newsItem['type'] }}</span>
                                    @endif
                                    <h3 class="text-sm font-extrabold text-text group-hover:text-primary transition-colors leading-snug line-clamp-1">{{ $newsItem['title'] ?? '' }}</h3>
                                    @if (!empty($newsItem['summary']))
                                        <p class="text-xs text-text-secondary mt-2 leading-relaxed line-clamp-1">{{ $newsItem['summary'] }}</p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <x-empty-state-section icon="newspaper" title="لا توجد أخبار منشورة حالياً" description="سيتم إضافة الأخبار فور نشرها" />
                @endif
            </div>

            {{-- ============ بطاقة أرقام الطوارئ ============ --}}
            <aside class="xl:col-span-3" aria-label="أرقام الطوارئ">
                <div class="rounded-3xl p-6" style="background:#f0f7f0;box-shadow:0 16px 40px rgba(23,63,39,0.10);">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:#173f27;">
                            <i data-lucide="phone-call" class="w-5 h-5 text-white"></i>
                        </span>
                        <h3 class="text-base sm:text-lg font-black text-text leading-tight m-0">أرقام الطوارئ</h3>
                    </div>

                    <div class="space-y-3">
                        @foreach ($emergencyItems as $emergency)
                            @php
                                $name = $emergency['name'] ?? $emergency['department'] ?? '';
                                $phone = $emergency['phone'] ?? '';
                            @endphp
                            <a href="{{ $phone ? 'tel:' . preg_replace('/\s+/', '', $phone) : '#' }}"
                               class="flex items-center gap-3 rounded-xl bg-white px-3.5 py-3 no-underline transition-all hover:shadow-md hover:-translate-y-0.5 group/row">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:#eef6ef;">
                                    <i data-lucide="phone" class="w-3.5 h-3.5" style="color:#173f27;"></i>
                                </span>
                                <span class="flex-1 min-w-0">
                                    <span class="block text-[13px] font-bold text-text leading-tight truncate">{{ $name }}</span>
                                </span>
                                <span class="text-sm font-black flex-shrink-0" style="color:#173f27;" dir="ltr">{{ $phone }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>