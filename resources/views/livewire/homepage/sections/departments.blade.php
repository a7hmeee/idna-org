@php
    $departments = collect($featuredDepartments)->take(7);
    $highlight = $departments->first();
    $otherDepartments = $departments->slice(1)->take(6);

    $iconMap = [
        'هندسة' => 'DraftingCompass', 'تخطيط' => 'DraftingCompass',
        'خدمات' => 'Building2', 'صحة' => 'Leaf', 'بيئة' => 'Leaf',
        'مالية' => 'WalletCards', 'إداري' => 'WalletCards',
        'اجتماعي' => 'Users', 'شؤون' => 'Users',
        'مياه' => 'Droplets', 'ثقافة' => 'BookOpen',
        'رياضة' => 'Trophy', 'تعليم' => 'GraduationCap',
        'زراعة' => 'Sprout', 'استثمار' => 'TrendingUp',
        'قانوني' => 'Scale', 'كهرباء' => 'Zap',
        'نظافة' => 'Trash2', 'طرق' => 'Route',
        'سوق' => 'Store',
    ];

    $resolveIcon = function ($name, $configured) use ($iconMap) {
        if (!empty($configured)) return $configured;
        foreach ($iconMap as $keyword => $icon) {
            if (mb_strpos($name, $keyword) !== false) return $icon;
        }
        return 'Building2';
    };

    $fallbackGradient = 'linear-gradient(145deg,#1C7736,#145D2B)';
@endphp

<section id="departments" class="departments-section" style="background:#FFFFFF;padding-top:clamp(54px,5.8vw,78px);padding-bottom:clamp(52px,5.8vw,78px);">

    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">

        {{-- SECTION HEADER --}}
        <div class="departments-header">
            <div style="text-align:center;">
                <div style="display:flex;align-items:center;justify-content:center;gap:5px;margin-bottom:10px;">
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                    <span style="display:block;width:4px;height:4px;border-radius:50%;background:#176B32;"></span>
                    <span style="display:block;width:26px;height:2px;border-radius:9999px;background:#176B32;"></span>
                </div>
                <h2 style="text-align:center;color:#17243A;font-size:clamp(26px,3vw,36px);font-weight:800;line-height:1.3;margin:0;">
                    {{ $sectionTitle ?? 'أقسام البلدية' }}
                </h2>
                <p style="text-align:center;max-width:640px;margin:10px auto 0;font-size:15px;line-height:1.8;color:#66756D;">
                    {{ $sectionSubtitle ?? 'نضع بين يديك أهم أقسام البلدية والخدمات التي يقدمها كل قسم للمواطنين.' }}
                </p>
            </div>

            @if (Route::has('public.departments.index'))
                <div class="departments-header-action">
                    <a href="{{ route('public.departments.index') }}" wire:navigate
                       class="departments-view-all-btn"
                       style="display:inline-flex;align-items:center;gap:6px;height:42px;padding:0 20px;border-radius:9px;background:#176B32;color:white;font-size:13px;font-weight:600;text-decoration:none;box-shadow:0 4px 14px rgba(23,107,50,0.2);transition:background 200ms,box-shadow 200ms;">
                        <span>عرض جميع الأقسام</span>
                        <i data-lucide="arrow-left" style="width:15px;height:15px;transition:transform 200ms;"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- DEPARTMENTS LAYOUT --}}
        @if ($departments->isNotEmpty())
            <div class="departments-layout" style="display:grid;grid-template-columns:1fr 320px;gap:20px;margin-top:44px;min-width:0;align-items:start;">

                {{-- LEFT: Regular Department Cards --}}
                <div class="departments-regular" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                    @foreach ($otherDepartments as $department)
                        @php
                            $regUrl = !empty($department['slug']) && Route::has('public.departments.show')
                                ? route('public.departments.show', ['department' => $department['slug']])
                                : '#';
                            $regIcon = $resolveIcon($department['name'] ?? '', $department['icon'] ?? '');
                            $regCover = $department['cover_image_url'] ?? null;
                        @endphp
                        <a href="{{ $regUrl }}" @if($regUrl !== '#') wire:navigate @endif
                           class="dept-card"
                           style="display:flex;flex-direction:column;border-radius:14px;overflow:hidden;position:relative;min-width:0;text-decoration:none;box-shadow:0 8px 24px rgba(10,50,25,0.12);transition:all 240ms ease-out;height:280px;{{ $regCover ? 'background:#145D2B;' : 'background:linear-gradient(145deg,#1C7736,#145D2B);' }}">
                            {{-- Background Image --}}
                            @if ($regCover)
                                <img src="{{ $regCover }}" alt="{{ $department['name'] ?? '' }}"
                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;"
                                     loading="lazy">
                            @endif

                            {{-- Green Overlay --}}
                            <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(20,93,43,0.15) 0%,rgba(20,93,43,0.55) 50%,rgba(20,93,43,0.92) 100%);{{ $regCover ? '' : 'display:none;' }}"></div>
                            @if (!$regCover)
                                <div style="position:absolute;inset:0;background:radial-gradient(circle at top left,rgba(255,255,255,0.1),transparent 55%);pointer-events:none;"></div>
                            @endif

                            {{-- Content --}}
                            <div style="position:relative;z-index:1;display:flex;flex-direction:column;padding:18px;flex:1;justify-content:flex-end;">
                                {{-- Icon --}}
                                <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.15);backdrop-filter:blur(4px);margin-bottom:auto;">
                                    <i data-lucide="{{ $regIcon }}" style="width:18px;height:18px;stroke-width:1.7;color:white;"></i>
                                </div>

                                {{-- Title --}}
                                <h4 style="margin:10px 0 0;font-size:15px;font-weight:700;color:white;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $department['name'] ?? '' }}
                                </h4>

                                {{-- Description --}}
                                @if (!empty($department['short_description']))
                                    <p style="margin:4px 0 0;font-size:11px;line-height:1.7;color:rgba(255,255,255,0.82);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $department['short_description'] }}
                                    </p>
                                @endif

                                {{-- Action --}}
                                <span class="dept-card-action"
                                   style="display:inline-flex;align-items:center;gap:4px;margin-top:10px;padding:0;color:rgba(255,255,255,0.9);font-size:11px;font-weight:600;text-decoration:none;transition:color 200ms;align-self:flex-start;">
                                    <span>عرض التفاصيل</span>
                                    <i data-lucide="arrow-left" style="width:11px;height:11px;transition:transform 200ms;"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- RIGHT: Featured Department Card --}}
                @if ($highlight)
                    @php
                        $fgUrl = !empty($highlight['slug']) && Route::has('public.departments.show')
                            ? route('public.departments.show', ['department' => $highlight['slug']])
                            : '#';
                        $fgIcon = $resolveIcon($highlight['name'] ?? '', $highlight['icon'] ?? '');
                        $fgCover = $highlight['cover_image_url'] ?? null;
                    @endphp
                    <div class="departments-featured" style="position:sticky;top:100px;">
                        <a href="{{ $fgUrl }}" @if($fgUrl !== '#') wire:navigate @endif
                           class="dept-card dept-featured"
                           style="display:flex;flex-direction:column;border-radius:16px;overflow:hidden;position:relative;min-width:0;text-decoration:none;box-shadow:0 14px 32px rgba(18,75,36,0.18);transition:all 240ms ease-out;height:100%;min-height:580px;{{ $fgCover ? 'background:#145D2B;' : 'background:linear-gradient(145deg,#1C7736,#145D2B);' }}">
                            {{-- Background Image --}}
                            @if ($fgCover)
                                <img src="{{ $fgCover }}" alt="{{ $highlight['name'] ?? '' }}"
                                     style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block;"
                                     loading="lazy">
                            @endif

                            {{-- Green Overlay --}}
                            <div style="position:absolute;inset:0;background:linear-gradient(to bottom,rgba(20,93,43,0.1) 0%,rgba(20,93,43,0.5) 45%,rgba(20,93,43,0.93) 100%);{{ $fgCover ? '' : 'display:none;' }}"></div>
                            @if (!$fgCover)
                                <div style="position:absolute;inset:0;background:radial-gradient(circle at top left,rgba(255,255,255,0.12),transparent 55%);pointer-events:none;"></div>
                            @endif

                            {{-- Content --}}
                            <div style="position:relative;z-index:1;display:flex;flex-direction:column;padding:24px;flex:1;justify-content:flex-end;">
                                {{-- Badge --}}
                                <span style="display:inline-flex;align-items:center;gap:1px;height:26px;padding:0 11px;border-radius:9999px;background:rgba(255,255,255,0.15);color:white;font-size:10px;font-weight:600;align-self:flex-start;backdrop-filter:blur(4px);margin-bottom:auto;">
                                    <i data-lucide="star" style="width:11px;height:11px;"></i>
                                    <span>القسم المميز</span>
                                </span>

                                {{-- Icon --}}
                                <div style="width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.15);backdrop-filter:blur(4px);margin-top:auto;">
                                    <i data-lucide="{{ $fgIcon }}" style="width:22px;height:22px;stroke-width:1.6;color:white;"></i>
                                </div>

                                {{-- Title --}}
                                <h3 style="margin:10px 0 0;font-size:19px;font-weight:700;color:white;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {{ $highlight['name'] ?? '' }}
                                </h3>

                                {{-- Description --}}
                                @if (!empty($highlight['short_description']))
                                    <p style="margin:6px 0 0;font-size:12px;line-height:1.75;color:rgba(255,255,255,0.85);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                        {{ $highlight['short_description'] }}
                                    </p>
                                @endif

                                {{-- Action --}}
                                <span class="dept-card-action"
                                   style="display:inline-flex;align-items:center;gap:5px;margin-top:14px;padding:0;color:rgba(255,255,255,0.9);font-size:12px;font-weight:600;text-decoration:none;transition:color 200ms;align-self:flex-start;">
                                    <span>استكشف القسم</span>
                                    <i data-lucide="chevron-left" style="width:14px;height:14px;transition:transform 200ms;"></i>
                                </span>
                            </div>
                        </a>
                    </div>
                @endif

            </div>
        @else
            {{-- Empty state --}}
            <div style="display:flex;align-items:center;justify-content:center;min-height:180px;margin-top:44px;">
                <div style="text-align:center;">
                    <i data-lucide="building-2" style="width:36px;height:36px;color:#A0CFB8;margin-bottom:10px;"></i>
                    <p style="font-size:15px;font-weight:600;color:#66756D;margin:0;">لا توجد أقسام متاحة حالياً</p>
                    <p style="font-size:12px;color:#94A3B8;margin:6px 0 0;">سيتم إضافة أقسام البلدية قريباً</p>
                </div>
            </div>
        @endif
    </div>

    @once
        @push('styles')
            <style>
                .departments-view-all-btn:hover { background:#0F4F28 !important; box-shadow:0 6px 20px rgba(23,107,50,0.3) !important; }
                .departments-view-all-btn:hover i { transform:translateX(-2px); }

                @media (min-width:1025px) {
                    .departments-header { position:relative; }
                    .departments-header-action { position:absolute; left:0; top:50%; transform:translateY(-50%); }
                }

                @media (max-width:1024px) {
                    .departments-header-action { text-align:center; margin-top:20px; }
                    .departments-layout { grid-template-columns:1fr !important; margin-top:36px !important; }
                    .departments-regular { grid-template-columns:repeat(2,1fr) !important; }
                    .departments-featured { position:static !important; }
                    .dept-featured { min-height:400px !important; }
                }

                @media (max-width:640px) {
                    .departments-regular { grid-template-columns:1fr !important; gap:14px !important; }
                    .departments-layout { gap:16px !important; margin-top:32px !important; }
                    .dept-card { height:260px !important; }
                    .dept-featured { min-height:340px !important; }
                }

                .dept-card:hover { transform:translateY(-3px); box-shadow:0 18px 40px rgba(10,50,25,0.2) !important; }
                .dept-card:hover .dept-card-action i { transform:translateX(-3px); }
                .dept-card:hover .dept-card-action { color:white !important; }

                .dept-card:focus-visible,
                .departments-view-all-btn:focus-visible { outline:2px solid #176B32; outline-offset:2px; border-radius:8px; }

                @media (prefers-reduced-motion:reduce) {
                    .dept-card,.dept-card *,.departments-view-all-btn { transition-duration:0.01ms !important; transform:none !important; }
                }
            </style>
        @endpush
    @endonce
</section>
