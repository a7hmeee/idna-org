@props([
    'slides' => [],
    'settings' => [],
    'municipalityName' => '',
    'portalUrl' => '',
    'logoUrl' => null,
])

@php
    $singleSlide = count($slides) === 1;
    $primaryBtn = $settings['primary_button_text'] ?? 'الدخول إلى البوابة';
    $secondaryBtn = $settings['secondary_button_text'] ?? 'تعرف على البلدية';
    $secondaryBtnUrl = $settings['secondary_button_url'] ?? '#municipality-intro';
    $activeSlides = $slides ?: [[
        'title' => 'مرحبا بكم في <span class="text-[#9bd0af]">'.$municipalityName.'</span>',
        'description' => 'استمتع بتجربة سلسة للحصول على الخدمات البلدية عبر البوابة الإلكترونية.',
        'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1600&auto=format&fit=crop',
        'badge_text' => 'خدمات إلكترونية',
    ]];
@endphp

<section id="hero" class="relative overflow-hidden bg-[#0d7a52]" dir="rtl" aria-label="الشريط الرئيسي">
    <div
        x-data="{
            current: 0,
            total: {{ count($activeSlides) }},
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
        <div class="grid lg:grid-cols-[1.06fr_1.14fr] min-h-[700px]">
            @foreach ($activeSlides as $index => $slide)
                <div x-show="current === {{ $index }}"
                    x-transition:enter="transition-all duration-500"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-all duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="contents"
                    role="group"
                    aria-roledescription="slide"
                    aria-label="{{ 'شريحة ' . ($index + 1) . ' من ' . count($activeSlides) }}">
                    <div class="relative flex flex-col justify-start bg-[#0d7a52] px-4 sm:px-6 lg:px-8 xl:px-10 pt-4 pb-6 sm:pt-5 lg:pt-6 xl:pt-8">
                       <div class="absolute inset-0 bg-gradient-to-r from-[#0b683f]/90 via-[#0d7a52]/90 to-[#0d7a52]/85"></div>
                       <div class="relative z-10 flex items-center justify-between gap-4 mb-5 sm:mb-6">
                           <button type="button" aria-label="إغلاق" class="flex h-12 w-12 items-center justify-center rounded-lg bg-white/90 text-[#0d7a52] shadow-md text-2xl font-black transition hover:bg-white cursor-pointer">×</button>
                           <div class="flex items-center justify-end gap-3 flex-1">
                               <div class="text-right leading-tight text-white">
                                   <p class="text-[12px] sm:text-[14px] font-bold text-white/90">ببلدية إذنا</p>
                                   <p class="text-[10px] sm:text-[11px] text-white/80">الجهة الرسمية لبلدية إذنا</p>
                               </div>
                               @if (!empty($logoUrl))
                                   <img src="{{ $logoUrl }}" alt="{{ $municipalityName }}" class="h-12 w-12 rounded-full object-cover border-2 border-white/80 bg-white/10 shadow-sm" />
                               @else
                                   <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-white/80 bg-white/10 text-lg text-white shadow-sm">🌿</div>
                               @endif
                           </div>
                       </div>

                       <div class="relative z-10 flex flex-col gap-4 text-right text-white">
                           <div class="flex flex-col gap-3">
                               <button type="button" class="w-full rounded-[16px] bg-white/12 px-5 py-4 text-center text-[25px] sm:text-[32px] font-black text-white shadow-sm ring-1 ring-white/10 backdrop-blur-sm">
                                   الرئيسية
                               </button>
                               <button type="button" class="w-full rounded-[16px] bg-white/0 px-5 py-4 text-center text-[25px] sm:text-[32px] font-black text-white/90 shadow-none">
                                   عن البلدية
                               </button>
                           </div>

                           <div class="mt-2 sm:mt-3">
                               <h1 class="leading-[1.05] font-black text-white text-[30px] sm:text-[42px] lg:text-[52px] xl:text-[62px]" style="text-shadow: 0 2px 18px rgba(0,0,0,0.15);">
                                   {!! str_replace('<span class="text-[#9bd0af]">', '<span class="text-[#baebd3]">', $slide['title'] ?? 'مرحبا بكم في <span class="text-[#baebd3]">'.$municipalityName.'</span>') !!}
                               </h1>
                           </div>

                           <div class="mt-1 flex items-center justify-center gap-3 text-white/80">
                               <div class="h-px flex-1 bg-white/20"></div>
                               <div class="text-[24px] leading-none">✦</div>
                               <div class="h-px flex-1 bg-white/20"></div>
                           </div>

                           <div class="mt-1 rounded-[20px] bg-white/8 p-4 ring-1 ring-white/10 backdrop-blur-sm">
                               <p class="text-[14px] sm:text-[16px] leading-8 text-white/90">
                                   {{ $slide['description'] ?? 'استمتع بتجربة سلسة للحصول على الخدمات البلدية عبر البوابة الإلكترونية.' }}
                               </p>
                           </div>
                       </div>

                       <div class="relative z-10 mt-auto pt-4">
                           <div class="rounded-[26px] bg-white/90 p-4 text-right shadow-[0_14px_30px_rgba(0,0,0,0.12)]">
                               <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                   <div class="flex items-center gap-2 text-[13px] sm:text-[16px] font-bold text-[#0d7a52]">
                                       <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#f5d57a] text-lg">✦</span>
                                       <span>أنا أذانوي</span>
                                   </div>
                                   <div class="flex-1 text-[12px] sm:text-[14px] font-medium text-[#0d7a52]/80 leading-7">
                                       مساعدك الذكي من بلدية إذنا<br class="hidden sm:block" />
                                       <span class="font-bold">شو تحتاج تعرف؟ أنا جاهز أسألك!</span>
                                   </div>
                               </div>
                           </div>
                       </div>
                    </div>

                    <div class="relative min-h-[420px] lg:min-h-full bg-[#0d7a52]">
                       @if (!empty($slide['image_url']))
                           <img src="{{ $slide['image_url'] }}"
                                alt="{{ $slide['title'] ?? $municipalityName }}"
                                class="absolute inset-0 h-full w-full object-cover object-center"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                       @endif
                       <div class="absolute inset-0 bg-gradient-to-l from-[#071d18]/65 via-[#0f2d21]/30 to-[#0d7a52]/10" style="display: {{ !empty($slide['image_url']) ? 'block' : 'none' }};"></div>
                       <div class="absolute inset-0 hidden" style="background:linear-gradient(135deg,#0d7a52,#1d8257,#1d7f54);"></div>

                       <div class="relative z-10 flex h-full flex-col justify-start px-4 pb-6 pt-5 sm:px-6 lg:px-8 xl:px-10">
                           <div class="flex justify-end">
                               <button type="button" class="rounded-full border border-white/65 bg-white/6 px-5 py-3 text-[18px] sm:text-[22px] font-bold text-white shadow-md backdrop-blur-sm">
                                   {{ $slide['badge_text'] ?? 'خدمات إلكترونية' }}
                               </button>
                           </div>

                           <div class="mt-auto pb-4">
                               <div class="flex flex-col items-center gap-3 sm:flex-row sm:justify-center lg:justify-end">
                                   <a href="{{ $secondaryBtnUrl }}" class="inline-flex items-center justify-center gap-2 rounded-[18px] border border-white/40 bg-white/8 px-5 py-3 text-[18px] sm:text-[20px] font-bold text-white shadow-lg backdrop-blur-sm transition hover:bg-white/12">
                                       <span>تعرّف على البلدية</span>
                                       <span class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-white/60 bg-white/10 text-xs">i</span>
                                   </a>
                                   @if ($portalUrl)
                                       <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-[18px] bg-white px-5 py-3 text-[18px] sm:text-[20px] font-black text-[#0d7a52] shadow-xl transition hover:bg-gray-100">
                                           <span>الدخول إلى البوابة</span>
                                           <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#0d7a52] text-[12px] text-white">◌</span>
                                       </a>
                                   @endif
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (!$singleSlide && count($activeSlides) > 1)
            <div class="absolute bottom-6 left-1/2 z-30 flex -translate-x-1/2 items-center gap-2" role="tablist" aria-label="اختيار الشريحة">
                @foreach ($activeSlides as $index => $slide)
                    <button @click="goTo({{ $index }})"
                           :class="current === {{ $index }} ? 'bg-[#d7f4dc] w-8' : 'bg-white/40 w-2.5 hover:bg-white/70'"
                           class="h-2.5 rounded-full transition-all duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#d7f4dc]/60"
                           role="tab"
                           :aria-selected="current === {{ $index }}"
                           :aria-label="'الانتقال إلى الشريحة ' + ({{ $index }} + 1)">
                    </button>
                @endforeach
            </div>
        @endif
    </div>
</section>
