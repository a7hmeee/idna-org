<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Council) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'council-members',
        'pageTitle' => $member->full_name,
        'pageSubtitle' => $member->bio ?? null,
        'pageBadge' => $posLabel,
        'pageBadgeIcon' => 'badge-check',
        'compact' => true,
    ])

    {{-- Member info moved below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;padding:16px 0;">
            {{-- Photo --}}
            <div style="width:100px;height:100px;border-radius:16px;overflow:hidden;border:3px solid white;flex-shrink:0;box-shadow:0 8px 30px rgba(0,0,0,0.12);">
                @if ($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#F3F4F6;">
                        <i data-lucide="user" style="width:36px;height:36px;color:#D1D5DB;"></i>
                    </div>
                @endif
            </div>
            <div style="flex:1;min-width:200px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-bottom:8px;">
                    @if ($member->committee)
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                            <i data-lucide="folder-kanban" style="width:12px;height:12px;"></i>
                            <span>{{ $member->committee }}</span>
                        </span>
                    @endif
                    @if ($member->qualification)
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                            <i data-lucide="graduation-cap" style="width:12px;height:12px;"></i>
                            <span>{{ $member->qualification }}</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. PROFILE CONTENT --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Bio / About --}}
                    @if ($member->bio)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="info" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>نبذة عن العضو</span>
                            </h2>
                            <div style="font-size:14px;color:#4B5563;line-height:1.8;">
                                {{ $member->bio }}
                            </div>
                        </div>
                    @endif

                    {{-- Profession --}}
                    @if ($member->profession)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="briefcase" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>المهنة</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $member->profession }}</p>
                        </div>
                    @endif

                    {{-- Qualification --}}
                    @if ($member->qualification)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="graduation-cap" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>المؤهل العلمي</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $member->qualification }}</p>
                        </div>
                    @endif

                    {{-- Committee --}}
                    @if ($member->committee)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="folder-kanban" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>اللجنة</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $member->committee }}</p>
                        </div>
                    @endif

                    {{-- Years of Experience --}}
                    @if ($member->years_of_experience)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="clock" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>سنوات الخبرة</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $member->years_of_experience }} سنة</p>
                        </div>
                    @endif

                    {{-- Contact --}}
                    @if ($hasContact)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="phone" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>وسائل التواصل</span>
                            </h2>
                            <div style="display:flex;flex-direction:column;gap:12px;">
                                @if ($member->phone)
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i data-lucide="phone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                        </div>
                                        <div><p style="font-size:13px;color:#4B5563;margin:0;font-weight:600;" dir="ltr">{{ $member->phone }}</p></div>
                                    </div>
                                @endif
                                @if ($member->mobile)
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i data-lucide="smartphone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                        </div>
                                        <div><p style="font-size:13px;color:#4B5563;margin:0;font-weight:600;" dir="ltr">{{ $member->mobile }}</p></div>
                                    </div>
                                @endif
                                @if ($member->email)
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i data-lucide="mail" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                        </div>
                                        <div><p style="font-size:13px;color:#4B5563;margin:0;font-weight:600;">{{ $member->email }}</p></div>
                                    </div>
                                @endif
                            </div>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;padding-top:14px;border-top:1px solid #F3F4F6;">
                                @if ($member->facebook)
                                    <a href="{{ $member->facebook }}" target="_blank" rel="noopener noreferrer"
                                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12px;font-weight:600;color:#1877F2;background:rgba(24,119,242,0.06);text-decoration:none;transition:all 0.2s;"
                                       onmouseover="this.style.background='rgba(24,119,242,0.12)'" onmouseout="this.style.background='rgba(24,119,242,0.06)'">
                                        <i data-lucide="facebook" style="width:14px;height:14px;"></i>
                                        <span>فيسبوك</span>
                                    </a>
                                @endif
                                @if ($member->twitter)
                                    <a href="{{ $member->twitter }}" target="_blank" rel="noopener noreferrer"
                                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12px;font-weight:600;color:#1DA1F2;background:rgba(29,161,242,0.06);text-decoration:none;transition:all 0.2s;"
                                       onmouseover="this.style.background='rgba(29,161,242,0.12)'" onmouseout="this.style.background='rgba(29,161,242,0.06)'">
                                        <i data-lucide="twitter" style="width:14px;height:14px;"></i>
                                        <span>تويتر</span>
                                    </a>
                                @endif
                                @if ($member->linkedin)
                                    <a href="{{ $member->linkedin }}" target="_blank" rel="noopener noreferrer"
                                       style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;border-radius:10px;font-size:12px;font-weight:600;color:#0A66C2;background:rgba(10,102,194,0.06);text-decoration:none;transition:all 0.2s;"
                                       onmouseover="this.style.background='rgba(10,102,194,0.12)'" onmouseout="this.style.background='rgba(10,102,194,0.06)'">
                                        <i data-lucide="linkedin" style="width:14px;height:14px;"></i>
                                        <span>لينكد إن</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Info Card --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                        <div style="padding:24px;text-align:center;background:linear-gradient(135deg,rgba(15,106,61,0.04),rgba(15,106,61,0.08));">
                            <div style="width:80px;height:80px;border-radius:20px;overflow:hidden;margin:0 auto 12px;border:3px solid white;box-shadow:0 4px 16px rgba(0,0,0,0.1);">
                                @if ($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#F3F4F6;">
                                        <i data-lucide="user" style="width:28px;height:28px;color:#D1D5DB;"></i>
                                    </div>
                                @endif
                            </div>
                            <h3 style="font-size:15px;font-weight:800;color:#1F2937;margin:0;">{{ $member->full_name }}</h3>
                            <p style="font-size:12px;color:#0F6A3D;font-weight:600;margin:4px 0 0;">{{ $posLabel }}</p>
                        </div>

                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
                            @if ($member->term_start)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="calendar" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">مدة العضوية</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">
                                            {{ $member->term_start->format('Y-m-d') }}
                                            @if ($member->term_end)
                                                - {{ $member->term_end->format('Y-m-d') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($member->years_of_experience)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">سنوات الخبرة</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $member->years_of_experience }} سنة</p>
                                    </div>
                                </div>
                            @endif

                            @if ($member->profession)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="briefcase" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">المهنة</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $member->profession }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($member->qualification)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="graduation-cap" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">المؤهل</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $member->qualification }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($member->committee)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="folder-kanban" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">اللجنة</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $member->committee }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px 24px;">
                        <h3 style="font-size:14px;font-weight:800;color:#1F2937;margin:0 0 14px;">روابط سريعة</h3>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <a href="{{ route('public.council.index') }}" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#0F6A3D;text-decoration:none;background:rgba(15,106,61,0.06);transition:all 0.2s;"
                               onmouseover="this.style.background='rgba(15,106,61,0.12)'"
                               onmouseout="this.style.background='rgba(15,106,61,0.06)'">
                                <i data-lucide="users" style="width:14px;height:14px;"></i>
                                <span>جميع الأعضاء</span>
                            </a>
                            <a href="{{ route('home') }}#council-members" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:transparent;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6'"
                               onmouseout="this.style.background='transparent'">
                                <i data-lucide="arrow-right" style="width:14px;height:14px;"></i>
                                <span>الصفحة الرئيسية</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Navigation between members --}}
            @if ($previous || $next)
                <div style="margin-top:40px;padding-top:28px;border-top:1px solid #F3F4F6;">
                    <div style="display:flex;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            @if ($previous)
                                <a href="{{ route('public.council.show', $previous->slug) }}" wire:navigate
                                   style="display:inline-flex;align-items:center;gap:10px;padding:12px 20px;border-radius:12px;border:1px solid #E5E7EB;color:#374151;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;background:white;"
                                   onmouseover="this.style.borderColor='#0F6A3D';this.style.color='#0F6A3D'"
                                   onmouseout="this.style.borderColor='#E5E7EB';this.style.color='#374151'">
                                    <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                                    <span style="display:flex;flex-direction:column;">
                                        <span style="font-size:10px;color:#9CA3AF;font-weight:400;">السابق</span>
                                        <span>{{ $previous->full_name }}</span>
                                    </span>
                                </a>
                            @endif
                        </div>
                        <div>
                            @if ($next)
                                <a href="{{ route('public.council.show', $next->slug) }}" wire:navigate
                                   style="display:inline-flex;align-items:center;gap:10px;padding:12px 20px;border-radius:12px;border:1px solid #E5E7EB;color:#374151;font-size:13px;font-weight:600;text-decoration:none;transition:all 0.2s;background:white;"
                                   onmouseover="this.style.borderColor='#0F6A3D';this.style.color='#0F6A3D'"
                                   onmouseout="this.style.borderColor='#E5E7EB';this.style.color='#374151'">
                                    <span style="display:flex;flex-direction:column;text-align:right;">
                                        <span style="font-size:10px;color:#9CA3AF;font-weight:400;">التالي</span>
                                        <span>{{ $next->full_name }}</span>
                                    </span>
                                    <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Return to Council --}}
            <div style="text-align:center;margin-top:32px;">
                <a href="{{ route('public.council.index') }}" wire:navigate
                   style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;border-radius:12px;background:#0F6A3D;color:white;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.3s;box-shadow:0 4px 16px rgba(15,106,61,0.25);"
                   onmouseover="this.style.boxShadow='0 8px 24px rgba(15,106,61,0.35)'"
                   onmouseout="this.style.boxShadow='0 4px 16px rgba(15,106,61,0.25)'">
                    <i data-lucide="users" style="width:16px;height:16px;"></i>
                    <span>العودة إلى المجلس البلدي</span>
                </a>
            </div>

        </div>
    </section>

</div>
