@php
    $allNews = collect($latestNews)->take(4);
    $featuredNews = $allNews->first();
    $otherNews = $allNews->skip(1)->take(3);
@endphp

<section id="news" class="section-py bg-white overflow-hidden">
    <div class="container-home">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-12">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold bg-primary-light text-primary mb-3">
                    <i data-lucide="newspaper" class="w-3.5 h-3.5"></i>
                    آخر المستجدات
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-[34px] font-black text-text leading-tight">{{ $sectionTitle ?? 'أخبار وإعلانات البلدية' }}</h2>
                @if ($sectionSubtitle)
                    <p class="text-sm sm:text-base text-text-secondary mt-3 max-w-xl leading-relaxed">{{ $sectionSubtitle }}</p>
                @endif
            </div>
        </div>

        @if ($allNews->isNotEmpty())
            <div class="grid lg:grid-cols-12 gap-6">
                @if ($featuredNews)
                    <div class="lg:col-span-7">
                        <a href="{{ !empty($featuredNews['url']) ? $featuredNews['url'] : '#' }}" @if(!empty($featuredNews['url'])) target="_blank" rel="noopener noreferrer" @endif
                           class="block group bg-white rounded-2xl border border-border/60 overflow-hidden hover:shadow-card-featured hover:-translate-y-0.5 transition-all duration-200 no-underline h-full shadow-card">
                            @if (!empty($featuredNews['image']))
                                <div class="aspect-[16/9] overflow-hidden">
                                    <img src="{{ $featuredNews['image'] }}" alt="{{ $featuredNews['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                                </div>
                            @else
                                <div class="aspect-[16/9] bg-gradient-to-br from-primary-light to-surface-secondary flex items-center justify-center">
                                    <i data-lucide="image" class="w-12 h-12 text-primary/20"></i>
                                </div>
                            @endif
                            <div class="p-5 sm:p-6">
                                @if (!empty($featuredNews['type']))
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-primary-light text-primary inline-block mb-3">{{ $featuredNews['type'] }}</span>
                                @endif
                                <h3 class="text-base sm:text-lg font-black text-text group-hover:text-primary transition-colors leading-snug">{{ $featuredNews['title'] ?? '' }}</h3>
                                @if (!empty($featuredNews['summary']))
                                    <p class="text-sm text-text-secondary mt-2 line-clamp-2 leading-relaxed">{{ $featuredNews['summary'] }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-4 pt-4 border-t border-border/50">
                                    @if (!empty($featuredNews['date']))
                                        <span class="text-xs text-text-muted flex items-center gap-1.5">
                                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-primary"></i>
                                            {{ $featuredNews['date'] }}
                                        </span>
                                    @endif
                                    <span class="text-xs font-bold text-primary group-hover:gap-2 transition-all inline-flex items-center gap-1 mr-auto">
                                        <span>قراءة المزيد</span>
                                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif

                <div class="lg:col-span-5">
                    <div class="space-y-4">
                        @foreach ($otherNews as $newsItem)
                            <a href="{{ !empty($newsItem['url']) ? $newsItem['url'] : '#' }}" @if(!empty($newsItem['url'])) target="_blank" rel="noopener noreferrer" @endif
                               class="flex gap-4 bg-surface-secondary rounded-xl border border-border/40 p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 no-underline group">
                                @if (!empty($newsItem['image']))
                                    <div class="w-20 h-20 sm:w-20 sm:h-20 rounded-xl overflow-hidden flex-shrink-0">
                                        <img src="{{ $newsItem['image'] }}" alt="" class="w-full h-full object-cover" loading="lazy">
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-xl bg-primary-light flex items-center justify-center flex-shrink-0">
                                        <i data-lucide="file-text" class="w-8 h-8 text-primary/40"></i>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    @if (!empty($newsItem['type']))
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-primary-light text-primary">{{ $newsItem['type'] }}</span>
                                    @endif
                                    <h4 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-2 mt-1">{{ $newsItem['title'] ?? '' }}</h4>
                                    @if (!empty($newsItem['date']))
                                        <p class="text-[10px] text-text-muted mt-1.5 flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            {{ $newsItem['date'] }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <x-empty-state-section icon="newspaper" title="لا توجد أخبار منشورة حالياً" description="سيتم إضافة الأخبار فور نشرها" />
        @endif
    </div>
</section>
