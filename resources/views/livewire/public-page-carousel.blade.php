<div>
    @php
        $hasSlides = $slides->isNotEmpty();
        $slideCount = $slides->count();
        $heroHeight = $compact ? 'clamp(300px, 44vh, 460px)' : 'clamp(380px, 58vh, 620px)';
        $bgImage = null;
        if ($hasSlides) {
            $bgImage = $slides->first()->image_url;
        } elseif ($fallbackImage) {
            $bgImage = $fallbackImage;
        }
        $displayTitle = $pageTitle ?? ($hasSlides ? $slides->first()->title ?? null : null) ?? $fallbackTitle ?? '';
        $displaySubtitle = $pageSubtitle ?? ($hasSlides ? $slides->first()->description ?? null : null) ?? $fallbackDescription ?? '';
        $displayBadge = $pageBadge ?? ($hasSlides ? $slides->first()->badge_text ?? null : null) ?? $fallbackBadge ?? '';
        $isExternal = fn ($url) => parse_url($url, PHP_URL_HOST) !== null && parse_url($url, PHP_URL_HOST) !== request()->getHost();
    @endphp

    <section class="relative overflow-hidden bg-[#073A25]" dir="ltr" aria-label="الشريط الرئيسي">
        <div
            x-data="{
                current: 0,
                total: {{ max($slideCount, 1) }},
                interval: null,
                autoplay: {{ ($hasSlides && $slideCount > 1) ? 'true' : 'false' }},
                init() {
                    if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        this.startAutoplay();
                    }
                },
                startAutoplay() {
                    this.interval = setInterval(() => { this.next(); }, 8000);
                },
                stopAutoplay() {
                    if (this.interval) { clearInterval(this.interval); this.interval = null; }
                },
                next() { this.current = (this.current + 1) % this.total; },
                prev() { this.current = (this.current - 1 + this.total) % this.total; },
                goTo(i) { this.current = i; }
            }"
            @mouseenter="stopAutoplay()"
            @mouseleave="autoplay && startAutoplay()"
            @focusin="stopAutoplay()"
            @focusout="autoplay && startAutoplay()"
            class="relative"
            role="region"
            aria-roledescription="carousel"
            aria-label="عرض الشرائح">

            {{-- Slides --}}
            <div class="relative overflow-hidden" style="height:{{ $heroHeight }};">
                @if ($hasSlides)
                    @foreach ($slides as $index => $slide)
                        <div x-show="current === {{ $index }}"
                             x-transition:enter="transition-opacity duration-700"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-500"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0"
                             role="group"
                             aria-roledescription="slide"
                             aria-label="{{ 'شريحة ' . ($index + 1) . ' من ' . $slideCount }}">
                            <img src="{{ $slide->image_url }}"
                                 alt="{{ $slide->title ?? '' }}"
                                 class="hidden md:block absolute inset-0 w-full h-full object-cover object-center page-hero-zoom"
                                 style="animation:pageHeroZoom 9s ease-out forwards;"
                                 @if ($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                            <img src="{{ $slide->mobile_image_url ?? $slide->image_url }}"
                                 alt="{{ $slide->title ?? '' }}"
                                 class="md:hidden absolute inset-0 w-full h-full object-cover object-center page-hero-zoom"
                                 style="animation:pageHeroZoom 9s ease-out forwards;"
                                 loading="lazy" decoding="async"
                                 onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                        </div>
                    @endforeach
                @elseif ($bgImage)
                    <img src="{{ $bgImage }}" alt="" class="absolute inset-0 w-full h-full object-cover object-center page-hero-zoom" style="animation:pageHeroZoom 9s ease-out forwards;" fetchpriority="high" loading="eager">
                @else
                    <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);min-height:{{ $heroHeight }};"></div>
                @endif

                {{-- Light dark gradient overlay (right-to-left, image remains dominant) --}}
                <div class="absolute inset-0 z-10 bg-gradient-to-l from-black/50 via-black/15 via-30% to-transparent to-60%"></div>

                {{-- Decorative green shape (left side) --}}
                <div class="absolute left-0 top-0 bottom-0 w-[35%] max-w-[460px] z-20 pointer-events-none overflow-hidden hidden lg:block" aria-hidden="true">
                    <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #0F6A3D 0%, #0F6A3D 40%, transparent 100%); clip-path: polygon(100% 0, 0 0, 0 100%, 100% 100%, 85% 80%, 78% 60%, 82% 40%, 90% 20%); opacity: 0.85;"></div>
                    <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #2B8A4B 0%, transparent 70%); clip-path: polygon(100% 0, 5% 0, 15% 100%, 100% 100%, 88% 82%, 82% 60%, 85% 35%); opacity: 0.30; transform: translateY(5%);"></div>
                </div>

                {{-- Content --}}
                <div class="relative z-30 w-full h-full flex items-center" dir="rtl">
                    <div class="w-full py-16 sm:py-20 lg:py-24" style="padding-top:max(44px,5vh);padding-bottom:max(60px,9vh);">
                        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                            <div class="max-w-[580px] lg:max-w-[640px] mr-auto lg:mr-[5%] text-right">

                                {{-- Breadcrumb --}}
                                @if (!empty($breadcrumb))
                                    <nav aria-label="مسار التنقل" class="mb-4 sm:mb-5">
                                        <ol class="flex flex-wrap items-center gap-2 list-none m-0 p-0 text-xs sm:text-[13px]">
                                            @foreach ($breadcrumb as $item)
                                                <li class="flex items-center gap-2">
                                                    @if (!$loop->last && !empty($item['url']))
                                                        <a href="{{ $item['url'] }}" wire:navigate class="text-white/65 hover:text-white no-underline font-medium whitespace-nowrap transition-colors">{{ $item['label'] }}</a>
                                                    @else
                                                        <span class="font-bold text-white">{{ $item['label'] }}</span>
                                                    @endif
                                                    @unless($loop->last)
                                                        <span aria-hidden="true" class="text-white/30 text-sm leading-none select-none">‹</span>
                                                    @endunless
                                                </li>
                                            @endforeach
                                        </ol>
                                    </nav>
                                @endif

                                @if ($hasSlides)
                                    @foreach ($slides as $index => $slide)
                                        <div x-show="current === {{ $index }}"
                                             x-transition:enter="transition-all duration-500"
                                             x-transition:enter-start="opacity-0 translate-y-4"
                                             x-transition:enter-end="opacity-100 translate-y-0">
                                            {{-- Badge --}}
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-4 sm:mb-5 shadow-lg" style="box-shadow: 0 4px 16px rgba(15,106,61,0.35);">
                                                <i data-lucide="{{ $pageBadgeIcon ?? 'zap' }}" class="w-4 h-4"></i>
                                                <span>{{ $slide->badge_text ?? $displayBadge }}</span>
                                            </span>

                                            {{-- Title --}}
                                            <h1 class="font-black text-white leading-[1.15] mb-4 sm:mb-5" style="font-size:clamp(26px,4vw,52px);max-width:560px;text-shadow:0 2px 20px rgba(0,0,0,0.3);">
                                                {{ $slide->title ?? $displayTitle }}
                                            </h1>

                                            {{-- Subtitle --}}
                                            @if ($slide->description ?? $displaySubtitle)
                                                <p class="text-white/85 leading-relaxed mb-6 sm:mb-7 max-w-[520px]" style="font-size:clamp(13px,1.4vw,16px);text-shadow:0 1px 8px rgba(0,0,0,0.15);">
                                                    {{ $slide->description ?? $displaySubtitle }}
                                                </p>
                                            @endif

                                            {{-- CTA Buttons --}}
                                            <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                                @if ($slide->button_text && $slide->button_url)
                                                    <a href="{{ $slide->button_url }}"
                                                       @if ($isExternal($slide->button_url)) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif
                                                       class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl bg-white text-primary font-bold text-sm sm:text-base hover:bg-gray-50 hover:-translate-y-0.5 transition-all shadow-xl no-underline"
                                                       style="box-shadow:0 4px 20px rgba(0,0,0,0.15);">
                                                        <span>{{ $slide->button_text }}</span>
                                                        @if ($isExternal($slide->button_url))
                                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                                        @else
                                                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                                                        @endif
                                                    </a>
                                                @endif
                                                <a href="{{ route('home') }}#contact" wire:navigate
                                                   class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl border-2 border-white/25 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-white/40 transition-all backdrop-blur-sm no-underline">
                                                    <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                                    <span>تواصل معنا</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    {{-- Static fallback content --}}
                                    @if ($displayBadge)
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-4 sm:mb-5 shadow-lg" style="box-shadow: 0 4px 16px rgba(15,106,61,0.35);">
                                            <i data-lucide="{{ $pageBadgeIcon ?? 'zap' }}" class="w-4 h-4"></i>
                                            <span>{{ $displayBadge }}</span>
                                        </span>
                                    @endif
                                    @if ($displayTitle)
                                        <h1 class="font-black text-white leading-[1.15] mb-4 sm:mb-5" style="font-size:clamp(26px,4vw,52px);max-width:560px;text-shadow:0 2px 20px rgba(0,0,0,0.3);">
                                            {{ $displayTitle }}
                                        </h1>
                                    @endif
                                    @if ($displaySubtitle)
                                        <p class="text-white/85 leading-relaxed mb-6 sm:mb-7 max-w-[520px]" style="font-size:clamp(13px,1.4vw,16px);text-shadow:0 1px 8px rgba(0,0,0,0.15);">
                                            {{ $displaySubtitle }}
                                        </p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                        <a href="{{ route('home') }}#contact" wire:navigate
                                           class="inline-flex items-center gap-2.5 px-6 sm:px-7 py-3 rounded-xl border-2 border-white/25 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-white/40 transition-all backdrop-blur-sm no-underline">
                                            <i data-lucide="message-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                            <span>تواصل معنا</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dots navigation --}}
                @if ($hasSlides && $slideCount > 1)
                    <div class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2" role="tablist" aria-label="اختيار الشريحة">
                        @foreach ($slides as $index => $slide)
                            <button type="button" @click="goTo({{ $index }})"
                                    :class="current === {{ $index }} ? 'bg-primary w-7' : 'bg-white/40 w-2.5 hover:bg-white/70'"
                                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50 cursor-pointer border-none"
                                    role="tab"
                                    :aria-selected="current === {{ $index }}"
                                    :aria-label="'الانتقال إلى الشريحة ' + ({{ $index }} + 1)">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            @keyframes pageHeroZoom {
                0% { transform: scale(1); }
                100% { transform: scale(1.08); }
            }
            @media (prefers-reduced-motion: reduce) {
                .page-hero-zoom { animation: none !important; }
            }
        </style>
    @endpush
</div>