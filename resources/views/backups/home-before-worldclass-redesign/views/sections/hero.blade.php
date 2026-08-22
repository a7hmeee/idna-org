@props([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'portalUrl' => '',
])

@php
    $singleSlide = count($slides) === 1;
    $primaryBtn = $settings['primary_button_text'] ?? 'الدخول إلى البوابة';
    $secondaryBtn = $settings['secondary_button_text'] ?? 'تعرف علينا';
    $secondaryBtnUrl = $settings['secondary_button_url'] ?? '#municipality-intro';
@endphp

<section id="hero" class="relative overflow-hidden bg-[#073A25]" dir="ltr" aria-label="الشريط الرئيسي">
    {{-- Slider Container --}}
    <div
        x-data="{
            current: 0,
            total: {{ count($slides) }},
            interval: null,
            autoplay: {{ $singleSlide ? 'false' : 'true' }},
            init() {
                if (this.total > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    this.startAutoplay();
                }
            },
            startAutoplay() {
                this.interval = setInterval(() => {
                    this.next();
                }, 8000);
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
        aria-label="عرض الشرائح"
    >
        {{-- Slides --}}
        <div class="relative" style="min-height: clamp(520px, 80vh, 720px);">
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
                     aria-label="{{ 'شريحة ' . ($index + 1) . ' من ' . count($slides) }}"
                    @if ($index === 0)
                     {{-- First slide: eager load --}}
                    @endif>
                    @if (!empty($slide['image_url']))
                        <img src="{{ $slide['image_url'] }}"
                             alt="{{ $slide['title'] ?? '' }}"
                             class="w-full h-full object-cover object-center"
                             style="min-height: clamp(520px, 80vh, 720px);"
                             @if ($index === 0)
                             fetchpriority="high" loading="eager"
                             @else
                             loading="lazy" decoding="async"
                             @endif
                             onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);min-height: clamp(520px, 80vh, 720px);"></div>
                    @endif
                </div>
            @endforeach

            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 z-10 bg-gradient-to-l from-[#073A25]/90 via-[#073A25]/60 via-40% to-transparent to-75%"></div>

            {{-- Decorative Green Shape (right side) --}}
            <div class="absolute left-0 top-0 bottom-0 w-[35%] max-w-[500px] z-20 pointer-events-none overflow-hidden hidden lg:block" aria-hidden="true">
                <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #0F6A3D 0%, #0F6A3D 40%, transparent 100%); clip-path: polygon(100% 0, 0 0, 0 100%, 100% 100%, 85% 80%, 78% 60%, 82% 40%, 90% 20%); opacity: 0.85;"></div>
                <div class="absolute left-0 top-0 w-full h-full" style="background: linear-gradient(135deg, #2B8A4B 0%, transparent 70%); clip-path: polygon(100% 0, 5% 0, 15% 100%, 100% 100%, 88% 82%, 82% 60%, 85% 35%); opacity: 0.30; transform: translateY(5%);"></div>
            </div>

            {{-- Content --}}
            <div class="relative z-30 w-full h-full flex items-center" dir="rtl">
                <div class="w-full py-20 sm:py-24 lg:py-28" style="padding-top:max(80px, 6vh);padding-bottom:max(80px, 12vh);">
                    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-[580px] lg:max-w-[640px] mr-auto lg:mr-[5%] text-right">
                            @foreach ($slides as $index => $slide)
                                <div x-show="current === {{ $index }}" x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                                    {{-- Badge --}}
                                    @if (!empty($slide['badge_text']))
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-5 sm:mb-6 shadow-lg" style="box-shadow: 0 4px 16px rgba(15,106,61,0.35);">
                                            <i data-lucide="zap" class="w-4 h-4"></i>
                                            {{ $slide['badge_text'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-primary/90 backdrop-blur-sm text-white text-xs sm:text-sm font-bold mb-5 sm:mb-6 shadow-lg">
                                            <i data-lucide="zap" class="w-4 h-4"></i>
                                            الخدمات الإلكترونية
                                        </span>
                                    @endif

                                    {{-- Title --}}
                                    <h1 class="font-black text-white leading-[1.15] mb-5" style="font-size: clamp(32px, 6vw, 72px); text-shadow: 0 2px 20px rgba(0,0,0,0.3);">
                                        @if (!empty($slide['title']))
                                            {{ $slide['title'] }}
                                        @else
                                            مرحباً بكم في
                                            <br>
                                            <span style="color: #7BBC9D;">{{ $municipalityName }}</span>
                                        @endif
                                    </h1>

                                    {{-- Description --}}
                                    @if (!empty($slide['description']))
                                        <p class="text-white/85 leading-relaxed mb-8 max-w-[500px]" style="font-size: clamp(14px, 1.6vw, 17px); text-shadow: 0 1px 8px rgba(0,0,0,0.15);">
                                            {{ $slide['description'] }}
                                        </p>
                                    @endif

                                    {{-- Buttons --}}
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                        @if ($portalUrl)
                                            <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl bg-white text-primary font-bold text-sm sm:text-base hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-0.5"
                                               style="box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
                                                <i data-lucide="external-link" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                                <span>{{ $primaryBtn }}</span>
                                            </a>
                                        @endif
                                        <a href="{{ $secondaryBtnUrl }}"
                                           class="inline-flex items-center gap-2.5 px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl border-2 border-white/25 text-white font-semibold text-sm sm:text-base hover:bg-white/10 hover:border-white/40 transition-all backdrop-blur-sm">
                                            <i data-lucide="info" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                            <span>{{ $secondaryBtn }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slider Controls --}}
        @if (!$singleSlide && count($slides) > 1)
            <div class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2" role="tablist" aria-label="اختيار الشريحة">
                @foreach ($slides as $index => $slide)
                    <button @click="goTo({{ $index }})"
                            :class="current === {{ $index }} ? 'bg-primary w-7' : 'bg-white/40 w-2.5 hover:bg-white/70'"
                            class="h-2.5 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-white/50"
                            role="tab"
                            :aria-selected="current === {{ $index }}"
                            :aria-label="'الانتقال إلى الشريحة ' + ({{ $index }} + 1)">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>
