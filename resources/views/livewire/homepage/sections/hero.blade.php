@props([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'portalUrl' => '',
])

@php
    $singleSlide = count($slides) === 1;
    $primaryBtn = $settings['primary_button_text'] ?? 'الدخول إلى البوابة';
    $secondaryBtn = $settings['secondary_button_text'] ?? 'تعرف على البلدية';
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
                     aria-label="{{ 'شريحة ' . ($index + 1) . ' من ' . count($slides) }}">
                    @if (!empty($slide['image_url']))
                        <img src="{{ $slide['image_url'] }}"
                             alt="{{ $slide['title'] ?? '' }}"
                             class="w-full h-full object-cover object-center"
                             style="min-height: clamp(520px, 80vh, 720px);"
                             @if ($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif
                             onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);min-height: clamp(520px, 80vh, 720px);"></div>
                    @endif
                </div>
            @endforeach

            {{-- Clean Gradient Overlay - lets city image show through beautifully --}}
            <div class="absolute inset-0 z-10" style="background: linear-gradient(135deg, rgba(3,31,16,0.85) 0%, rgba(7,58,37,0.65) 45%, rgba(7,58,37,0.35) 75%, rgba(7,58,37,0.08) 100%);"></div>

            {{-- Content --}}
            <div class="relative z-20 w-full h-full flex items-center" dir="rtl">
                <div class="w-full py-16 sm:py-20 lg:py-24" style="padding-top:max(70px, 5vh);padding-bottom:max(70px, 10vh);">
                    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="max-w-[600px] lg:max-w-[680px] mr-auto lg:mr-[5%] text-right">
                            @foreach ($slides as $index => $slide)
                                <div x-show="current === {{ $index }}" x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

                                    {{-- Badge --}}
                                    @if (!empty($slide['badge_text']))
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#C8A85A]/20 backdrop-blur-sm text-[#C8A85A] text-xs sm:text-sm font-semibold mb-4 sm:mb-5 border border-[#C8A85A]/30 relative z-10">
                                            {{ $slide['badge_text'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#C8A85A]/20 backdrop-blur-sm text-[#C8A85A] text-xs sm:text-sm font-semibold mb-4 sm:mb-5 border border-[#C8A85A]/30 relative z-10">
                                            الخدمات الإلكترونية
                                        </span>
                                    @endif

                                    {{-- Title with beautiful color hierarchy --}}
                                    <h1 class="font-extrabold leading-[1.1] mb-3 relative z-10" style="font-size: clamp(30px, 5.8vw, 68px); text-shadow: 0 2px 24px rgba(0,0,0,0.35); letter-spacing: -0.02em;">
                                        @if (!empty($slide['title']))
                                            <span class="text-white">{{ $slide['title'] }}</span>
                                        @else
                                            <span class="text-white/90">مرحباً بكم في</span>
                                            <br>
                                            <span class="relative inline-block" style="color: #D4B76A; text-shadow: 0 2px 24px rgba(0,0,0,0.3);">
                                                {{ $municipalityName }}
                                            </span>
                                        @endif
                                    </h1>

                                    {{-- Description with better readability --}}
                                    @if (!empty($slide['description']))
                                        <p class="text-white/90 leading-[1.9] mb-8 max-w-[520px] relative z-10" style="font-size: clamp(15px, 1.6vw, 18px); text-shadow: 0 1px 8px rgba(0,0,0,0.15); font-weight: 400;">
                                            {{ $slide['description'] }}
                                        </p>
                                    @endif

                                    {{-- Buttons - Clean & Professional --}}
                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 relative z-10">
                                        @if ($portalUrl)
                                            <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
                                               class="inline-flex items-center gap-2 px-7 sm:px-9 py-3.5 sm:py-4 rounded-xl bg-white text-[#073A25] font-bold text-sm sm:text-base hover:bg-gray-50 transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.15)] group">
                                                <i data-lucide="arrow-up-left" class="w-4 h-4 sm:w-5 sm:h-5 relative z-10"></i>
                                                <span class="relative z-10">{{ $primaryBtn }}</span>
                                            </a>
                                        @endif
                                        <a href="{{ $secondaryBtnUrl }}"
                                           class="inline-flex items-center gap-2 px-7 sm:px-9 py-3.5 sm:py-4 rounded-xl border-2 border-white/30 text-white font-medium text-sm sm:text-base hover:bg-white/10 hover:border-white/60 transition-all duration-300 backdrop-blur-sm relative group">
                                            <i data-lucide="info" class="w-4 h-4 sm:w-5 sm:h-5 relative z-10"></i>
                                            <span class="relative z-10">{{ $secondaryBtn }}</span>
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
            <div class="absolute bottom-5 sm:bottom-7 left-1/2 -translate-x-1/2 z-30 flex items-center gap-1.5" role="tablist" aria-label="اختيار الشريحة">
                @foreach ($slides as $index => $slide)
                    <button @click="goTo({{ $index }})"
                            :class="current === {{ $index }} ? 'bg-[#D4B76A] w-6' : 'bg-white/40 w-2 hover:bg-white/70'"
                            class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4B76A]/60"
                            role="tab"
                            :aria-selected="current === {{ $index }}"
                            :aria-label="'الانتقال إلى الشريحة ' + ({{ $index }} + 1)">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>