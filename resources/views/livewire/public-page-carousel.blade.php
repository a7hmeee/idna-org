<div>
    @php
        $hasSlides = $slides->isNotEmpty();
        $heroHeight = $compact ? '320px' : 'clamp(320px, 38vw, 420px)';
        $bgImage = null;
        if ($hasSlides) {
            $firstSlide = $slides->first();
            $bgImage = $firstSlide->image_url;
        } elseif ($fallbackImage) {
            $bgImage = $fallbackImage;
        }
        $displayTitle = $pageTitle ?? ($hasSlides ? $firstSlide->title ?? null : null) ?? $fallbackTitle ?? '';
        $displaySubtitle = $pageSubtitle ?? ($hasSlides ? $firstSlide->description ?? null : null) ?? $fallbackDescription ?? '';
        $displayBadge = $pageBadge ?? ($hasSlides ? $firstSlide->badge_text ?? null : null) ?? $fallbackBadge ?? '';
    @endphp

    <section class="relative flex items-end overflow-hidden"
             style="min-height:{{ $heroHeight }};padding-top:116px;border-radius:0 0 24px 24px;"
             @if ($hasSlides)
             x-data="{
                 current: 0,
                 total: {{ $slides->count() }},
                 interval: null,
                 autoplay: {{ $hasMultiple ? 'true' : 'false' }},
                 init() {
                     if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                         this.interval = setInterval(() => { this.next(); }, 6000);
                     }
                 },
                 next() { this.current = (this.current + 1) % this.total; },
                 prev() { this.current = (this.current - 1 + this.total) % this.total; },
                 goTo(i) { this.current = i; this.resetAutoplay(); },
                 resetAutoplay() {
                     if (this.interval) { clearInterval(this.interval); }
                     if (this.autoplay) { this.interval = setInterval(() => { this.next(); }, 6000); }
                 }
             }"
             @if ($hasMultiple)
             @mouseenter="if (interval) clearInterval(interval); interval = null"
             @mouseleave="if (autoplay) { interval = setInterval(() => { next(); }, 6000); }"
             @endif
             @endif
             role="region" aria-label="رئيسي" aria-roledescription="carousel">

        {{-- Background images / gradient --}}
        <div class="absolute inset-0">
            @if ($hasSlides)
                @foreach ($slides as $i => $slide)
                    <div x-show="current === {{ $i }}"
                         x-transition:enter="transition-opacity duration-700"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         style="position:absolute;inset:0;width:100%;height:100%;"
                         role="group" aria-roledescription="slide" aria-label="{{ $slide->title }}">
                        <img src="{{ $slide->image_url }}"
                             alt="{{ $slide->title }}"
                             class="hidden md:block w-full h-full object-cover"
                             style="animation:heroZoom 8s ease-out forwards;"
                             @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif />
                        <img src="{{ $slide->mobile_image_url ?? $slide->image_url }}"
                             alt="{{ $slide->title }}"
                             class="md:hidden w-full h-full object-cover"
                             loading="lazy" />
                    </div>
                @endforeach
            @elseif ($bgImage)
                <img src="{{ $bgImage }}" alt="" class="w-full h-full object-cover" style="animation:heroZoom 8s ease-out forwards;" fetchpriority="high" />
            @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#0F4F28,#176B32,#1A7A3E);"></div>
            @endif
            <div style="position:absolute;inset:0;background:linear-gradient(to left,rgba(0,0,0,0.60) 0%,rgba(0,0,0,0.30) 40%,rgba(8,20,25,0.20) 70%,rgba(8,20,25,0.10) 100%);"></div>
        </div>

        {{-- Decorative SVG shape --}}
        <div class="hidden lg:block overflow-hidden" style="position:absolute;right:0;top:0;bottom:0;width:420px;pointer-events:none;">
            <svg viewBox="0 0 420 1000" fill="none" xmlns="http://www.w3.org/2000/svg" style="position:absolute;right:0;top:0;height:100%;width:auto;" preserveAspectRatio="xMinYMin slice">
                <path d="M420 0 L420 1000 L60 1000 C100 800 140 600 125 480 C110 360 80 240 155 130 C230 20 320 0 420 0 Z" fill="#1B5E20" fill-opacity="0.92"/>
                <path d="M420 0 L420 1000 L95 1000 C130 810 165 620 150 500 C135 380 105 260 170 150 C235 40 335 10 420 0 Z" fill="#2E7D32" fill-opacity="0.30"/>
            </svg>
        </div>

        {{-- Arrows --}}
        @if ($hasSlides && $hasMultiple)
            <button type="button" @click="prev()"
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all cursor-pointer"
                    aria-label="السابقة">
                <i data-lucide="chevron-right" class="w-5 h-5"></i>
            </button>
            <button type="button" @click="next()"
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all cursor-pointer"
                    aria-label="التالية">
                <i data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
        @endif

        {{-- Content --}}
        <div class="relative z-10 w-full" style="padding-bottom:clamp(28px,3vw,48px);">
            <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8">
                <div style="max-width:750px;margin-right:6%;margin-left:auto;text-align:right;">

                    {{-- Breadcrumb --}}
                    @if (!empty($breadcrumb))
                        <nav aria-label="مسار التنقل" style="margin-bottom:16px;">
                            <ol style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin:0;padding:0;list-style:none;font-size:13px;color:rgba(255,255,255,0.75);">
                                @foreach ($breadcrumb as $item)
                                    <li style="display:flex;align-items:center;gap:8px;">
                                        @if (!$loop->last && !empty($item['url']))
                                            <a href="{{ $item['url'] }}"
                                               wire:navigate
                                               style="color:rgba(255,255,255,0.65);text-decoration:none;transition:color 0.2s;font-weight:500;white-space:nowrap;"
                                               onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">
                                                {{ $item['label'] }}
                                            </a>
                                        @else
                                            <span style="font-weight:700;color:#F5B041;">{{ $item['label'] }}</span>
                                        @endif
                                        @unless($loop->last)
                                            <span aria-hidden="true" style="color:rgba(255,255,255,0.3);font-size:15px;line-height:1;user-select:none;">‹</span>
                                        @endunless
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    @endif

                    {{-- Badge --}}
                    @if ($displayBadge)
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:9999px;font-size:11px;font-weight:700;background:rgba(46,125,50,0.9);backdrop-filter:blur(8px);color:white;margin-bottom:12px;box-shadow:0 4px 16px rgba(46,125,50,0.35);">
                            @if ($pageBadgeIcon)
                                <i data-lucide="{{ $pageBadgeIcon }}" style="width:12px;height:12px;"></i>
                            @endif
                            <span>{{ $displayBadge }}</span>
                        </span>
                    @endif

                    {{-- Title --}}
                    @if ($displayTitle)
                        <h1 style="font-size:clamp(22px,3.2vw,40px);font-weight:900;color:white;line-height:1.15;margin-bottom:8px;text-shadow:0 2px 20px rgba(0,0,0,0.35);">
                            {{ $displayTitle }}
                        </h1>
                    @endif

                    {{-- Subtitle --}}
                    @if ($displaySubtitle)
                        <p style="font-size:clamp(13px,1.3vw,15px);color:rgba(255,255,255,0.85);max-width:560px;line-height:1.7;margin:0;text-shadow:0 1px 8px rgba(0,0,0,0.2);">
                            {{ $displaySubtitle }}
                        </p>
                    @endif

                    {{-- Slide CTAs --}}
                    @if ($hasSlides)
                        @foreach ($slides as $i => $slide)
                            <div x-show="current === {{ $i }}" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                @if ($slide->button_text && $slide->button_url)
                                    <div style="margin-top:16px;">
                                        <a href="{{ $slide->button_url }}"
                                           @if (parse_url($slide->button_url, PHP_URL_HOST) !== null && parse_url($slide->button_url, PHP_URL_HOST) !== request()->getHost()) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif
                                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 22px;border-radius:12px;background:rgba(255,255,255,0.12);color:white;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.3s;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.25);"
                                           onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                                           onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                                            <span>{{ $slide->button_text }}</span>
                                            @if (parse_url($slide->button_url, PHP_URL_HOST) !== null && parse_url($slide->button_url, PHP_URL_HOST) !== request()->getHost())
                                                <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                                            @else
                                                <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    {{-- Dots navigation --}}
                    @if ($hasSlides && $hasMultiple)
                        <div style="display:flex;align-items:center;gap:6px;margin-top:16px;" role="tablist" aria-label="مؤشرات الشرائح">
                            @foreach ($slides as $i => $slide)
                                <button type="button" role="tab"
                                        :aria-selected="current === {{ $i }}"
                                        :aria-label="'الشريحة ' + ({{ $i + 1 }})"
                                        @click="goTo({{ $i }})"
                                        :style="`width:{{ $i === 0 ? '28' : '10' }}px;height:10px;border-radius:9999px;border:none;cursor:pointer;transition:all 0.3s;background:{{ $i === 0 ? 'white' : 'rgba(255,255,255,0.4)' }}`"
                                        x-init="$watch('current', val => {
                                            $el.style.width = (val === {{ $i }} ? '28px' : '10px');
                                            $el.style.background = (val === {{ $i }} ? 'white' : 'rgba(255,255,255,0.4)');
                                        })">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            @keyframes heroZoom {
                0% { transform: scale(1); }
                100% { transform: scale(1.08); }
            }
        </style>
    @endpush
</div>
