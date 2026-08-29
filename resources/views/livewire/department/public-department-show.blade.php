<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Departments) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'departments',
        'pageTitle' => $department->name,
        'pageSubtitle' => $department->short_description ?? null,
        'pageBadge' => 'قسم بلدي',
        'pageBadgeIcon' => $department->icon ?? 'building-2',
        'compact' => true,
    ])

    {{-- Department info badges moved below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            @if ($department->manager_name)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="user" style="width:12px;height:12px;"></i>
                    <span>{{ $department->manager_name }}</span>
                </span>
            @endif
            @if ($department->phone)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="phone" style="width:12px;height:12px;"></i>
                    <span dir="ltr"><a href="tel:{{ $department->phone }}" style="color:inherit;text-decoration:none;">{{ $department->phone }}</a></span>
                </span>
            @endif
            @if ($department->email)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="mail" style="width:12px;height:12px;"></i>
                    <span><a href="mailto:{{ $department->email }}" style="color:inherit;text-decoration:none;">{{ $department->email }}</a></span>
                </span>
            @endif
            @if ($services->count() > 0)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(34,197,94,0.25);color:#86EFAC;">
                    <i data-lucide="file-text" style="width:12px;height:12px;"></i>
                    <span>{{ $services->count() }} خدمة</span>
                </span>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. DEPARTMENT INFO + SERVICES --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- About --}}
                    @if ($department->description)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="info" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>نبذة عن القسم</span>
                            </h2>
                            <div style="font-size:14px;color:#4B5563;line-height:1.8;">
                                {{ $department->description }}
                            </div>
                        </div>
                    @endif

                    {{-- Vision --}}
                    @if ($department->vision)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="eye" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>الرؤية</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $department->vision }}</p>
                        </div>
                    @endif

                    {{-- Mission --}}
                    @if ($department->mission)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="target" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>المهام</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $department->mission }}</p>
                        </div>
                    @endif

                    {{-- Responsibilities --}}
                    @if ($department->responsibilities)
                        <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:28px 24px;">
                            <h2 style="font-size:16px;font-weight:800;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="clipboard-list" style="width:18px;height:18px;color:#0F6A3D;"></i>
                                <span>المسؤوليات</span>
                            </h2>
                            <p style="font-size:14px;color:#4B5563;line-height:1.8;margin:0;">{{ $department->responsibilities }}</p>
                        </div>
                    @endif

                    {{-- Services --}}
                    @if ($services->isNotEmpty())
                        <div>
                            <h2 style="font-size:18px;font-weight:800;color:#1F2937;margin:0 0 18px;display:flex;align-items:center;gap:8px;">
                                <i data-lucide="file-text" style="width:20px;height:20px;color:#0F6A3D;"></i>
                                <span>خدمات القسم</span>
                                <span style="font-size:12px;font-weight:600;color:#6B7280;">({{ $services->count() }})</span>
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($services as $service)
                                    <a href="{{ route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $service->slug]) }}" wire:navigate
                                       class="block bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                                       style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                                       onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                                        <div style="display:flex;align-items:flex-start;gap:14px;">
                                            <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i data-lucide="{{ $service->category?->icon ?? 'file-text' }}" style="width:20px;height:20px;color:#0F6A3D;"></i>
                                            </div>
                                            <div style="min-width:0;flex:1;">
                                                <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 4px;line-height:1.4;">{{ $service->name }}</h3>
                                                @if ($service->summary)
                                                    <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $service->summary }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div style="text-align:center;padding:48px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                            <div style="width:56px;height:56px;border-radius:14px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                <i data-lucide="file-x" style="width:28px;height:28px;color:#9CA3AF;"></i>
                            </div>
                            <h3 style="font-size:15px;font-weight:700;color:#1F2937;margin:0 0 6px;">لا توجد خدمات حالياً</h3>
                            <p style="font-size:13px;color:#9CA3AF;margin:0;">سيتم إضافة خدمات هذا القسم قريباً</p>
                        </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">

                    {{-- Info Card --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;overflow:hidden;">
                        <div style="padding:24px;text-align:center;background:linear-gradient(135deg,rgba(15,106,61,0.04),rgba(15,106,61,0.08));">
                            <div style="width:64px;height:64px;border-radius:16px;background:#0F6A3D;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                                <i data-lucide="{{ $department->icon ?? 'building-2' }}" style="width:28px;height:28px;color:white;"></i>
                            </div>
                            <h3 style="font-size:15px;font-weight:800;color:#1F2937;margin:0;">{{ $department->name }}</h3>
                            @if ($department->manager_position)
                                <p style="font-size:12px;color:#6B7280;margin:4px 0 0;">{{ $department->manager_position }}</p>
                            @endif
                        </div>

                        <div style="padding:20px 24px;display:flex;flex-direction:column;gap:14px;">
                            @if ($department->manager_name)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="user" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">المدير</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $department->manager_name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->phone)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="phone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">الهاتف</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;" dir="ltr"><a href="tel:{{ $department->phone }}" style="color:inherit;text-decoration:none;">{{ $department->phone }}</a></p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->email)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="mail" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">البريد الإلكتروني</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;"><a href="mailto:{{ $department->email }}" style="color:inherit;text-decoration:none;">{{ $department->email }}</a></p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->office_location)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="map-pin" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">الموقع</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $department->office_location }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->working_hours)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="clock" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">ساعات العمل</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $department->working_hours }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->extension)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="git-branch" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">تحويلة</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;">{{ $department->extension }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($department->mobile)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="smartphone" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:11px;color:#9CA3AF;margin:0;font-weight:600;">الجوال</p>
                                        <p style="font-size:13px;color:#1F2937;margin:0;font-weight:600;" dir="ltr"><a href="tel:{{ $department->mobile }}" style="color:inherit;text-decoration:none;">{{ $department->mobile }}</a></p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Links --}}
                    <div style="background:white;border-radius:16px;border:1px solid #F3F4F6;padding:20px 24px;">
                        <h3 style="font-size:14px;font-weight:800;color:#1F2937;margin:0 0 14px;">روابط سريعة</h3>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <a href="{{ route('public.services.index') . '?department=' . $department->slug }}" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#0F6A3D;text-decoration:none;background:rgba(15,106,61,0.06);transition:all 0.2s;"
                               onmouseover="this.style.background='rgba(15,106,61,0.12)'"
                               onmouseout="this.style.background='rgba(15,106,61,0.06)'">
                                <i data-lucide="file-text" style="width:14px;height:14px;"></i>
                                <span>خدمات القسم</span>
                            </a>
                            <a href="{{ url('/departments') }}" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:transparent;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6'"
                               onmouseout="this.style.background='transparent'">
                                <i data-lucide="building-2" style="width:14px;height:14px;"></i>
                                <span>جميع الدوائر</span>
                            </a>
                            <a href="{{ route('public.services.index') }}" wire:navigate
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;background:transparent;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6'"
                               onmouseout="this.style.background='transparent'">
                                <i data-lucide="grid-3x3" style="width:14px;height:14px;"></i>
                                <span>جميع الخدمات</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

</div>
