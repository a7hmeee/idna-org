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
        <div class="relative ih-hero-stage">
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
                             @if ($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" decoding="async" @endif
                             onerror="this.parentElement.style.background='linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B)'; this.style.display='none';">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg, #073A25, #0F6A3D, #2B8A4B);"></div>
                    @endif
                </div>
            @endforeach

            {{-- Clean Gradient Overlay --}}
            <div class="absolute inset-0 z-10" style="background: linear-gradient(135deg, rgba(3,31,16,0.85) 0%, rgba(7,58,37,0.65) 45%, rgba(7,58,37,0.35) 75%, rgba(7,58,37,0.08) 100%);"></div>

            {{-- Content --}}
            <div class="relative z-20 w-full h-full ih-hero-content" dir="rtl">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-[600px] lg:max-w-[680px] mx-auto lg:ml-0 lg:mr-[5%] text-center lg:text-right">
                        @foreach ($slides as $index => $slide)
                            <div x-show="current === {{ $index }}" x-transition:enter="transition-all duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">

                                {{-- Badge --}}
                                @if (!empty($slide['badge_text']))
                                    <div class="ih-hero-badge">{{ $slide['badge_text'] }}</div>
                                @else
                                    <div class="ih-hero-badge">الخدمات الإلكترونية</div>
                                @endif

                                {{-- Title --}}
                                <h1 class="ih-hero-title">
                                    @php
                                        $rawTitle = !empty($slide['title']) ? $slide['title'] : 'مرحباً بكم في بلدية إذنا';
                                        $greenWord = 'إذنا';
                                        $titleHtml = preg_replace(
                                            '/' . preg_quote($greenWord, '/') . '/',
                                            '<span class="ih-hero-title-green" style="color:#3BAF56 !important;">' . $greenWord . '</span>',
                                            e($rawTitle),
                                            1
                                        );
                                    @endphp
                                    <span class="ih-hero-title-white">{!! $titleHtml !!}</span>
                                </h1>

                                {{-- Description --}}
                                @if (!empty($slide['description']))
                                    <p class="ih-hero-desc">{{ $slide['description'] }}</p>
                                @endif

                                {{-- Buttons --}}
                                <div class="ih-hero-buttons">
                                    @if ($portalUrl)
                                        <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="ih-hero-btn ih-hero-btn-primary">
                                            <i data-lucide="arrow-up-left" style="width:14px;height:14px;"></i>
                                            <span>{{ $primaryBtn }}</span>
                                        </a>
                                    @endif
                                    <a href="{{ $secondaryBtnUrl }}" class="ih-hero-btn ih-hero-btn-secondary">
                                        <i data-lucide="info" style="width:14px;height:14px;"></i>
                                        <span>{{ $secondaryBtn }}</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Slider Controls --}}
        @if (!$singleSlide && count($slides) > 1)
            <div class="absolute left-1/2 -translate-x-1/2 z-30 flex items-center gap-1.5" style="bottom:12px;" role="tablist" aria-label="اختيار الشريحة">
                @foreach ($slides as $index => $slide)
                    <button @click="goTo({{ $index }})"
                            :class="current === {{ $index }} ? 'bg-[#3BAF56] w-6' : 'bg-white/40 w-2 hover:bg-white/70'"
                            class="h-2 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#3BAF56]/60"
                            role="tab"
                            :aria-selected="current === {{ $index }}"
                            :aria-label="'الانتقال إلى الشريحة ' + ({{ $index }} + 1)">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>

@push('styles')
    @once
        <style>
            /* ===== HERO STAGE HEIGHT ===== */
            .ih-hero-stage { height: clamp(520px, 80vh, 720px); overflow: hidden; position: relative; }
            .ih-hero-content {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: clamp(40px, 6vw, 96px) 0 clamp(48px, 7vw, 96px);
            }

            /* ===== BADGE ===== */
            .ih-hero-badge {
                display: inline-flex;
                align-items: center;
                padding: 5px 14px;
                border-radius: 9999px;
                background: rgba(59,175,86,0.12);
                backdrop-filter: blur(8px);
                color: #5EC97A;
                font-size: 13px;
                font-weight: 600;
                margin-bottom: 18px;
                border: 1px solid rgba(59,175,86,0.2);
                letter-spacing: 0.03em;
            }

            /* ===== TITLE ===== */
            .ih-hero-title {
                font-weight: 800;
                line-height: 1.15;
                margin: 0 0 14px;
                font-size: clamp(30px, 5.5vw, 68px);
                text-shadow: 0 2px 24px rgba(0,0,0,0.35);
                letter-spacing: -0.01em;
            }
            .ih-hero-title-white {
                color: #FFFFFF;
            }
            .ih-hero-title-green {
                color: #3BAF56;
                position: relative;
                display: inline;
                text-shadow: 0 0 40px rgba(59,175,86,0.25), 0 2px 24px rgba(0,0,0,0.35);
            }
            .ih-hero-title-green::after {
                content: '';
                position: absolute;
                bottom: -2px;
                right: 0;
                width: 100%;
                height: 3px;
                background: linear-gradient(90deg, transparent 0%, #3BAF56 20%, #3BAF56 80%, transparent 100%);
                border-radius: 2px;
                opacity: 0.45;
            }

            /* ===== DESCRIPTION ===== */
            .ih-hero-desc {
                color: rgba(255,255,255,0.85);
                line-height: 1.85;
                margin: 0 0 30px;
                max-width: 520px;
                margin-left: auto;
                margin-right: auto;
                font-size: 15px;
                text-shadow: 0 1px 8px rgba(0,0,0,0.12);
                font-weight: 400;
                letter-spacing: 0.01em;
            }

            /* ===== BUTTONS ===== */
            .ih-hero-buttons {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }
            .ih-hero-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 14px 32px;
                border-radius: 14px;
                font-size: 15px;
                font-weight: 700;
                text-decoration: none;
                white-space: nowrap;
                transition: all 0.3s ease;
                cursor: pointer;
                border: none;
            }
            .ih-hero-btn-primary {
                background: linear-gradient(135deg, #176B32 0%, #0F4F28 100%);
                color: white;
                box-shadow: 0 4px 20px rgba(23,107,50,0.35);
            }
            .ih-hero-btn-primary:hover {
                background: linear-gradient(135deg, #1a7a38 0%, #126030 100%);
                box-shadow: 0 6px 28px rgba(23,107,50,0.5);
                transform: translateY(-1px);
            }
            .ih-hero-btn-secondary {
                background: rgba(255,255,255,0.12);
                color: white;
                border: 1.5px solid rgba(212,183,106,0.4);
                backdrop-filter: blur(8px);
            }
            .ih-hero-btn-secondary:hover {
                background: rgba(255,255,255,0.2);
                border-color: rgba(212,183,106,0.7);
                transform: translateY(-1px);
            }

            /* ===== DESKTOP (>= 1024px) ===== */
            @media (min-width: 1024px) {
                .ih-hero-stage { height: clamp(520px, 80vh, 720px); }
                .ih-hero-content { padding: 96px 0; }
                .ih-hero-badge { font-size: 13px; padding: 6px 18px; margin-bottom: 22px; }
                .ih-hero-title { font-size: clamp(40px, 5vw, 68px); margin-bottom: 18px; }
                .ih-hero-desc { font-size: 17px; margin-bottom: 36px; }
                .ih-hero-btn { padding: 16px 36px; font-size: 16px; border-radius: 14px; gap: 10px; }
            }

            /* ===== TABLET (768px – 1023px) ===== */
            @media (min-width: 768px) and (max-width: 1023.98px) {
                .ih-hero-stage { height: 480px; }
                .ih-hero-content { padding: 60px 0; }
                .ih-hero-title { font-size: clamp(32px, 5vw, 48px); }
                .ih-hero-desc { font-size: 16px; margin-bottom: 28px; }
                .ih-hero-btn { padding: 14px 30px; font-size: 14px; }
            }

            /* ===== MOBILE (< 768px) ===== */
            @media (max-width: 767.98px) {
                .ih-hero-stage {
                    height: 400px;
                }
                .ih-hero-content {
                    padding: 40px 20px 24px;
                    align-items: center;
                }
                .ih-hero-badge {
                    font-size: 11px;
                    padding: 4px 12px;
                    margin-bottom: 12px;
                }
                .ih-hero-title {
                    font-size: clamp(26px, 8vw, 38px);
                    margin-bottom: 10px;
                    line-height: 1.12;
                }
                .ih-hero-desc {
                    font-size: 14px;
                    line-height: 1.7;
                    margin-bottom: 24px;
                }
                .ih-hero-buttons {
                    gap: 12px;
                    flex-wrap: nowrap;
                    justify-content: center;
                }
                .ih-hero-btn {
                    padding: 12px 22px;
                    font-size: 13px;
                    border-radius: 12px;
                    gap: 7px;
                    flex-shrink: 0;
                }
                .ih-hero-btn i, .ih-hero-btn svg {
                    width: 14px !important;
                    height: 14px !important;
                }
            }

            /* ===== SMALL MOBILE (< 380px) ===== */
            @media (max-width: 379.98px) {
                .ih-hero-content { padding: 28px 14px 24px; }
                .ih-hero-title { font-size: 24px; }
                .ih-hero-desc { font-size: 13px; margin-bottom: 20px; }
                .ih-hero-buttons { gap: 10px; flex-wrap: wrap; }
                .ih-hero-btn {
                    padding: 11px 18px;
                    font-size: 12px;
                    flex: 1 1 auto;
                    min-width: 0;
                }
            }

            /* ===== REDUCED MOTION ===== */
            @media (prefers-reduced-motion: reduce) {
                .ih-hero-btn { transition: none; }
            }
        </style>
    @endonce
@endpush