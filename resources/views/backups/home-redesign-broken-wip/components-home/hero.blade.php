@props([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'municipality' => [],
    'portalUrl' => '',
    'primaryBtn' => 'الدخول إلى البوابة',
    'secondaryBtn' => 'تعرف علينا',
    'secondaryBtnUrl' => '#municipality-intro',
])

@php
    $singleSlide = count($slides) <= 1;
    $heroImg = !empty($slides[0]['image_path'])
        ? asset('storage/' . $slides[0]['image_path'])
        : (!empty($slides[0]['image_url']) ? $slides[0]['image_url'] : null);

    $phone = collect($municipality['contacts'] ?? [])->firstWhere('type', 'phone');
    $email = collect($municipality['contacts'] ?? [])->firstWhere('type', 'email');
    $hours = collect($municipality['business_hours'] ?? [])->first();
@endphp

<section id="hero" class="relative overflow-hidden band-pine" dir="ltr" aria-label="الشريط الرئيسي">
    {{-- Layered background: image on glass + tuned overlays --}}
    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
        @if ($heroImg)
            <img src="{{ $heroImg }}"
                 alt=""
                 class="w-full h-full object-cover object-center opacity-90"
                 style="object-position:60% center;"
                 fetchpriority="high"
                 data-parallax-speed="0.22">
        @endif
        <div class="absolute inset-0" style="background:linear-gradient(105deg, rgba(6,40,26,0.96) 0%, rgba(11,58,36,0.86) 38%, rgba(14,74,46,0.55) 68%, rgba(14,74,46,0.18) 100%);"></div>
        <div class="absolute inset-0 paper-dots" style="opacity:0.5;"></div>
        {{-- Gold hairline --}}
        <div class="absolute top-0 right-0 left-0 h-[3px]" style="background:linear-gradient(90deg, transparent, #C8A85A 30%, #C8A85A 70%, transparent);"></div>
        {{-- Ceremonial crown arc (right in RTL content) --}}
        <div class="hidden lg:block absolute top-0 bottom-0 right-0 overflow-hidden" style="width:clamp(220px, 24vw, 360px);">
            <div class="absolute right-0 -top-[12%] w-full h-[124%]"
                 style="border-radius:50% 0 0 50% / 42% 0 0 42%;
                        background:linear-gradient(180deg, #6BAA3B 0%, #1E6F45 45%, #0B3A24 100%);
                        opacity:0.5;"></div>
        </div>
    </div>

    {{-- Content --}}
    <div class="relative container-home" style="min-height:clamp(600px, 88vh, 860px);">
        <div class="w-full h-full flex flex-col" style="padding-top:clamp(120px, 14vh, 170px);">

            <div class="max-w-[620px] lg:max-w-[680px] mr-auto lg:mr-[6%] text-right" dir="rtl">

                {{-- : --}}
                <div data-hero-item="1">
                    <span class="eyebrow-pill" style="background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.22);color:#E6F2E9;backdrop-filter:blur(10px);">
                        <span class="eyebrow-pill-dot" style="background:#C8A85A;box-shadow:0 0 0 4px rgba(200,168,90,0.25);"></span>
                        <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                        <span>الموقع الرسمي لبلدية {{ $municipalityName }}</span>
                    </span>
                </div>

                <h1 data-hero-item="2"
                    class="display-heading text-white mt-6 mb-5"
                    style="font-size:clamp(36px, 6vw, 76px);text-shadow:0 3px 24px rgba(0,0,0,0.35);">
                    @if (!empty($slides[0]['title']))
                        {!! e($slides[0]['title']) !!}
                    @else
                        مرحباً بكم في<br> <span style="color:#A0CF93;">{{ $municipalityName }}</span>
                    @endif
                </h1>

                <p data-hero-item="3"
                       class="text-white/85 leading-relaxed mb-9 max-w-[540px]"
                       style="font-size:clamp(14px, 1.5vw, 17px);text-shadow:0 1px 10px rgba(0,0,0,0.25);">
                    {{ $slides[0]['description'] ?? ($municipality['short_description'] ?? 'نسعى لتقديم أفضل الخدمات البلدية بثقافة وشفافية من أجل مجتمع أفضل.') }}
                </p>

                <div data-hero-item="4" class="flex flex-wrap items-center gap-4">
                    @if ($portalUrl)
                        <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="btn-pine" style="background:linear-gradient(150deg,#0F4F28,#0B3A24);">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span>{{ $primaryBtn ?: 'الدخول إلى البوابة' }}</span>
                        </a>
                    @endif
                    <a href="{{ $secondaryBtnUrl }}" class="btn-ghost-light">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        <span>{{ $secondaryBtn ?: 'تعرف علينا' }}</span>
                    </a>
                </div>
            </div>

            {{-- Bottom info strip --}}
            <div class="mt-auto pb-10 md:pb-12" style="padding-top:80px;">
                <div class="flex flex-col md:flex-row md:items-end gap-4 md:gap-0 md:justify-between" dir="rtl">
                    <div class="flex flex-wrap items-center gap-3" data-hero-item="5">
                        @if ($phone && !empty($phone['value']))
                            <a href="tel:{{ $phone['value'] }}" class="chip-glass no-underline hover:bg-white/20 transition-colors" style="text-decoration:none;">
                                <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                <span dir="ltr">{{ $phone['value'] }}</span>
                            </a>
                        @endif
                        @if ($email && !empty($email['value']))
                            <a href="mailto:{{ $email['value'] }}" class="chip-glass no-underline hover:bg-white/20 transition-colors" style="text-decoration:none;">
                                <i data-lucide="mail" class="w-3.5 h-3.5"></i>
                                <span class="max-w-[220px] truncate" dir="ltr">{{ $email['value'] }}</span>
                            </a>
                        @endif
                        @if ($hours && !empty($hours['opening_time']))
                            <span class="chip-glass">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                <span>ساعات الدوام: {{ $hours['opening_time'] }} – {{ $hours['closing_time'] ?? '' }}</span>
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3" data-hero-item="6" dir="rtl">
                        {{-- CTA to bottom sections: scroll cue --}}
                        <a href="#services" class="hidden sm:inline-flex items-center gap-2 text-white/70 hover:text-white text-sm font-semibold transition-colors no-underline" style="text-decoration:none;">
                            <span>استكشف خدمات البلدية</span>
                            <i data-lucide="arrow-down" class="w-4 h-4 animate-bounce"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>