@php
    $facilities = collect($featuredFacilities)->take(4);
    $total = $facilities->count();
    $featured = $facilities->first();
    $rest = $facilities->slice(1)->take(3);
    $wide = $rest->shift();
    $smallCards = $rest->take(2);

    $iconMap = [
        'حدائق' => 'tree-pine',
        'ملاعب' => 'zap',
        'قاعات' => 'layout',
        'مراكز ثقافية' => 'landmark',
        'مكتبات' => 'book-open',
        'مبانٍ بلدية' => 'building-2',
        'مرافق عامة' => 'globe',
    ];

    $resolveIcon = function ($categoryName) use ($iconMap) {
        if (empty($categoryName)) return 'building-2';
        return $iconMap[$categoryName] ?? 'building-2';
    };
@endphp

<section id="public-facilities" class="facilities-section" aria-label="المرافق العامة" role="region"
         style="background:#F8FBF8;padding-top:clamp(44px,5vw,72px);padding-bottom:clamp(44px,5vw,72px);">

    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">

        {{-- ============================= --}}
        {{-- SECTION HEADER              --}}
        {{-- ============================= --}}
        <div class="facilities-header">
            <div style="text-align:right;">
                <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                    <i data-lucide="building-2" style="width:14px;height:14px;color:#176B32;"></i>
                    <span style="font-size:12px;font-weight:700;color:#176B32;letter-spacing:0.3px;">مرافقنا</span>
                    <span style="display:block;width:20px;height:2px;border-radius:9999px;background:#176B32;"></span>
                </div>
                <h2 style="color:#17243A;font-size:clamp(26px,3vw,36px);font-weight:800;line-height:1.3;margin:0;">
                    {{ $sectionTitle ?? 'المرافق العامة' }}
                </h2>
                <p style="max-width:650px;margin:10px 0 0;font-size:clamp(13px,1.1vw,15px);line-height:1.8;color:#66756D;">
                    {{ $sectionSubtitle ?? 'تعرف على المرافق العامة التي تديرها البلدية والخدمات التي تقدمها للمواطنين.' }}
                </p>
            </div>

            @if (Route::has('public.facilities.index'))
                <div class="facilities-header-action">
                    <a href="{{ route('public.facilities.index') }}" wire:navigate
                       class="facilities-view-all-btn"
                       style="display:inline-flex;align-items:center;gap:6px;height:44px;padding:0 22px;border-radius:11px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(23,107,50,0.2);transition:background 200ms,box-shadow 200ms;">
                        <span>عرض جميع المرافق</span>
                        <i data-lucide="arrow-left" style="width:15px;height:15px;transition:transform 200ms;"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- ============================= --}}
        {{-- FACILITIES GRID             --}}
        {{-- ============================= --}}
        @if ($total > 0)

            {{-- 1 FACILITY — Featured only --}}
            @if ($total === 1 && $featured)
                <div style="margin-top:44px;">
                    @include('livewire.homepage.sections.facilities-featured', ['facility' => $featured, 'resolveIcon' => $resolveIcon])
                </div>

            @else
                {{-- 2+ FACILITIES — 2-column grid --}}
                <div class="facilities-grid" style="display:grid;grid-template-columns:minmax(0,1.1fr) minmax(0,1fr);gap:clamp(16px,2vw,22px);margin-top:44px;min-width:0;">

                    {{-- Featured Column --}}
                    <div style="min-width:0;">
                        @if ($featured)
                            @include('livewire.homepage.sections.facilities-featured', ['facility' => $featured, 'resolveIcon' => $resolveIcon])
                        @endif
                    </div>

                    {{-- Secondary Column --}}
                    <div style="display:flex;flex-direction:column;gap:clamp(14px,1.6vw,20px);min-width:0;">
                        @if ($wide)
                            @include('livewire.homepage.sections.facilities-wide', ['facility' => $wide, 'resolveIcon' => $resolveIcon])
                        @endif

                        @if ($smallCards->isNotEmpty())
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:clamp(14px,1.6vw,20px);min-width:0;">
                                @foreach ($smallCards as $facility)
                                    @include('livewire.homepage.sections.facilities-small', ['facility' => $facility, 'resolveIcon' => $resolveIcon])
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            @endif

        @else
            {{-- ============================= --}}
            {{-- EMPTY STATE                 --}}
            {{-- ============================= --}}
            <div style="display:flex;align-items:center;justify-content:center;min-height:180px;max-height:210px;margin-top:44px;">
                <div style="text-align:center;">
                    <i data-lucide="building-2" style="width:36px;height:36px;color:#A0CFB8;margin-bottom:10px;"></i>
                    <p style="font-size:15px;font-weight:600;color:#66756D;margin:0;">لا توجد مرافق عامة متاحة حالياً</p>
                    <p style="font-size:12px;color:#94A3B8;margin:6px 0 0;">سيتم إضافة المرافق العامة قريباً</p>
                </div>
            </div>
        @endif

    </div>

    @once
        @push('styles')
            <style>
                /* =============================== */
                /* HEADER LAYOUT                  */
                /* =============================== */
                @media (min-width:1025px) {
                    .facilities-header {
                        display: flex;
                        align-items: flex-start;
                        justify-content: space-between;
                        gap: 24px;
                    }
                    .facilities-header-action {
                        flex-shrink: 0;
                        margin-top: 28px;
                    }
                }

                @media (max-width:1024px) {
                    .facilities-header-action {
                        margin-top: 20px;
                    }
                }

                /* =============================== */
                /* GRID — TABLET                  */
                /* =============================== */
                @media (max-width:1024px) and (min-width:641px) {
                    .facilities-grid {
                        grid-template-columns: 1fr !important;
                    }
                }

                /* =============================== */
                /* GRID — MOBILE                  */
                /* =============================== */
                @media (max-width:640px) {
                    .facilities-grid {
                        grid-template-columns: 1fr !important;
                        margin-top: 32px !important;
                    }
                    .facilities-grid > div:last-child > div:last-child {
                        grid-template-columns: 1fr !important;
                    }
                }

                /* =============================== */
                /* VIEW ALL BUTTON HOVER          */
                /* =============================== */
                .facilities-view-all-btn:hover {
                    background: #0F4F28 !important;
                    box-shadow: 0 6px 20px rgba(23,107,50,0.3) !important;
                }
                .facilities-view-all-btn:hover i {
                    transform: translateX(-2px);
                }

                /* =============================== */
                /* FEATURED CARD HOVER            */
                /* =============================== */
                .facility-featured-card {
                    transition: box-shadow 300ms ease;
                }
                .facility-featured-card img {
                    transition: transform 300ms ease;
                }
                .facility-featured-card:hover img {
                    transform: scale(1.025);
                }
                .facility-featured-card:hover {
                    box-shadow: 0 16px 40px rgba(20,50,30,0.2) !important;
                }
                .facility-featured-card:hover .featured-action-arrow {
                    transform: translateX(-2px);
                }

                /* =============================== */
                /* WIDE CARD HOVER                */
                /* =============================== */
                .facility-wide-card {
                    transition: all 300ms ease;
                }
                .facility-wide-card:hover {
                    transform: translateY(-4px);
                    border-color: rgba(23,107,50,0.2) !important;
                    box-shadow: 0 10px 28px rgba(20,50,30,0.1) !important;
                }
                .facility-wide-card:hover img {
                    transform: scale(1.02);
                }
                .facility-wide-card:hover .wide-card-arrow {
                    transform: translateX(-2px);
                    color: #176B32 !important;
                }
                .facility-wide-card img {
                    transition: transform 300ms ease;
                }

                /* =============================== */
                /* SMALL CARD HOVER               */
                /* =============================== */
                .facility-small-card {
                    transition: all 300ms ease;
                }
                .facility-small-card:hover {
                    transform: translateY(-4px);
                    border-color: rgba(23,107,50,0.2) !important;
                    box-shadow: 0 10px 28px rgba(20,50,30,0.1) !important;
                }
                .facility-small-card:hover img {
                    transform: scale(1.02);
                }
                .facility-small-card img {
                    transition: transform 300ms ease;
                }

                /* =============================== */
                /* FOCUS                          */
                /* =============================== */
                .facilities-section a:focus-visible,
                .facilities-section button:focus-visible {
                    outline: 2px solid #176B32;
                    outline-offset: 2px;
                    border-radius: 8px;
                }

                /* =============================== */
                /* REDUCED MOTION                 */
                /* =============================== */
                @media (prefers-reduced-motion: reduce) {
                    .facility-featured-card,
                    .facility-featured-card *,
                    .facility-wide-card,
                    .facility-wide-card *,
                    .facility-small-card,
                    .facility-small-card *,
                    .facilities-view-all-btn,
                    .facilities-view-all-btn i {
                        transition-duration: 0.01ms !important;
                        transform: none !important;
                    }
                }
            </style>
        @endpush
    @endonce
</section>
