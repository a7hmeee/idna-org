<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Facilities) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'facilities',
        'pageTitle' => $facility->name,
        'pageSubtitle' => $facility->summary ?? null,
        'pageBadge' => 'مرفق عام',
        'pageBadgeIcon' => 'building-2',
        'compact' => true,
    ])

    {{-- Facility info badges moved below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            @if ($facility->category)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="{{ $facility->category->icon ?? 'building-2' }}" style="width:12px;height:12px;"></i>
                    <span>{{ $facility->category->name }}</span>
                </span>
            @endif
            @if ($facility->phone)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="phone" style="width:12px;height:12px;"></i>
                    <span dir="ltr">{{ $facility->phone }}</span>
                </span>
            @endif
            @if ($facility->is_featured)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مرفق مميز</span>
                </span>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. FACILITY INFO --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Cover Image --}}
                    @if ($facility->cover_image_url)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                            <img src="{{ $facility->cover_image_url }}" alt="{{ $facility->name }}" class="w-full aspect-video object-cover" />
                        </div>
                    @endif

                    {{-- Gallery --}}
                    @if ($facility->gallery_urls)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="images" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>معرض الصور</span>
                            </h2>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach ($facility->gallery_urls as $image)
                                    <img src="{{ $image }}" alt="{{ $facility->name }}" class="w-full aspect-video object-cover rounded-lg border border-gray-100" loading="lazy" />
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Description --}}
                    @if ($facility->description)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="info" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>نبذة عن المرفق</span>
                            </h2>
                            <div style="font-size:14px;color:#4B5563;line-height:1.8;white-space:pre-line;">
                                {{ $facility->description }}
                            </div>
                        </div>
                    @endif

                    {{-- Services --}}
                    @if ($facility->services)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="list-checks" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>الخدمات</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($facility->services as $service)
                                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="check-circle" style="width:16px;height:16px;color:#0F6A3D;flex-shrink:0;"></i>
                                        <span>{{ $service }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Features --}}
                    @if ($facility->features)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="star" style="width:18px;height:18px;color:#D97706;"></i>
                                <span>المميزات</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($facility->features as $feature)
                                    <div style="display:flex;align-items:center;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="star" style="width:16px;height:16px;color:#D97706;flex-shrink:0;"></i>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Rules --}}
                    @if ($facility->rules)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="alert-circle" style="width:18px;height:18px;color:#D97706;"></i>
                                <span>التعليمات</span>
                            </h2>
                            <ul style="margin:0;padding:0;list-style:none;display:flex;flex-direction:column;gap:8px;">
                                @foreach ($facility->rules as $rule)
                                    <li style="display:flex;align-items:flex-start;gap:8px;font-size:13px;color:#4B5563;">
                                        <i data-lucide="alert-circle" style="width:16px;height:16px;color:#D97706;flex-shrink:0;margin-top:1px;"></i>
                                        <span>{{ $rule }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Info Card --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                        <div style="padding:24px;text-align:center;background:linear-gradient(135deg,rgba(15,106,61,0.04),rgba(15,106,61,0.08));">
                            <div style="width:64px;height:64px;border-radius:16px;background:#0F6A3D;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i data-lucide="building-2" style="width:28px;height:28px;color:white;"></i>
                            </div>
                            <h3 style="font-size:15px;font-weight:800;color:#1F2937;margin:0;">{{ $facility->name }}</h3>
                            @if ($facility->category)
                                <p style="font-size:12px;color:#6B7280;margin:4px 0 0;">{{ $facility->category->name }}</p>
                            @endif
                        </div>

                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="map-pin" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                </div>
                                <div>
                                    <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">العنوان</p>
                                    <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $facility->address }}</p>
                                </div>
                            </div>

                            @if ($facility->phone)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="phone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">الهاتف</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;" dir="ltr">{{ $facility->phone }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($facility->email)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="mail" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">البريد الإلكتروني</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $facility->email }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($facility->working_hours)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">ساعات العمل</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $facility->working_hours }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Views --}}
                    <div style="text-align:center;padding:12px 20px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:12px;color:#9CA3AF;">
                            <i data-lucide="eye" style="width:14px;height:14px;"></i>
                            <span>{{ number_format($facility->views_count) }} مشاهدة</span>
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px 24px;">
                        <h3 style="font-size:14px;font-weight:800;color:#1F2937;margin:0 0 14px;">روابط سريعة</h3>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <a href="{{ route('public.facilities.index') }}" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:transparent;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6'"
                               onmouseout="this.style.background='transparent'">
                                <i data-lucide="building-2" style="width:14px;height:14px;"></i>
                                <span>جميع المرافق</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>