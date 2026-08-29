<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'departments',
        'fallbackTitle' => "الدوائر البلدية",
        'fallbackDescription' => "استعرض جميع الدوائر والأقسام في البلدية، وتعرف على خدماتها ومعلومات الاتصال بها.",
        'fallbackBadge' => 'دوائر البلدية',
        'fallbackIcon' => 'building-2',
        'fallbackImage' => !empty($carouselImages) ? $carouselImages[0] : null,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. DEPARTMENTS LIST --}}
    {{-- ============================================ --}}
    <section id="departments-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filter --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <button wire:click="$set('filter', 'all')"
                                aria-pressed="{{ $filter == 'all' }}"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filter == 'all' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filter == 'all' ? '#0F6A3D' : 'white' }};color:{{ $filter == 'all' ? 'white' : '#6B7280' }};">
                            الكل
                        </button>
                        <button wire:click="$set('filter', 'featured')"
                                aria-pressed="{{ $filter == 'featured' }}"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filter == 'featured' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filter == 'featured' ? '#0F6A3D' : 'white' }};color:{{ $filter == 'featured' ? 'white' : '#6B7280' }};">
                            <i data-lucide="star" style="width:12px;height:12px;"></i>
                            المميزة
                        </button>
                    </div>

                    <div style="position:relative;width:100%;max-width:340px;">
                        <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                        <span class="sr-only" role="status" wire:loading wire:target="search">جاري تحديث النتائج…</span>
<input type="text" wire:model.live.debounce.400ms="search" aria-label="ابحث عن قسم"
                               placeholder="ابحث عن قسم..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            {{-- Results Count --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $departments->total() ?? 0 }}</span> قسم
                </p>
            </div>

            {{-- Cards Grid --}}
            @if ($departments->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="building-2" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h2>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($departments as $dept)
                        @php $svcCount = (int) ($serviceCounts[$dept->id] ?? 0); @endphp
                        <a href="{{ route('public.departments.show', $dept->slug) }}" wire:navigate
                           class="dept-card block bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                            {{-- Header: Icon + Name + Badges --}}
                            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:12px;">
                                <div class="dept-icon" style="width:52px;height:52px;border-radius:14px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;">
                                    @if ($dept->cover_image_url)
                                        <img src="{{ $dept->cover_image_url }}" alt="{{ $dept->name }}" class="w-full h-full rounded-xl object-cover">
                                    @else
                                        <i data-lucide="{{ $dept->icon ?? 'building-2' }}" class="dept-i" style="width:22px;height:22px;color:#0F6A3D;transition:color 0.2s;"></i>
                                    @endif
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h2 class="dept-title" style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 2px;transition:color 0.2s;">{{ $dept->name }}</h2>
                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                        <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;color:#0F6A3D;font-weight:600;background:rgba(15,106,61,0.06);padding:2px 8px;border-radius:6px;">
                                            <i data-lucide="laptop" style="width:12px;height:12px;"></i>
                                            {{ $svcCount }} خدمة
                                        </span>
                                        @if ($dept->is_featured)
                                            <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;color:#D97706;font-weight:600;background:rgba(217,119,6,0.08);padding:2px 8px;border-radius:6px;">
                                                <i data-lucide="star" style="width:12px;height:12px;"></i>
                                                مميزة
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Description --}}
                            @if ($dept->short_description || $dept->description)
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.7;margin:0 0 12px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                    {{ $dept->short_description ?? $dept->description }}
                                </p>
                            @endif

                            {{-- Contact Info Row --}}
                            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
                                @if ($dept->manager_name)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="user" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        {{ $dept->manager_name }}
                                    </span>
                                @endif
                                @if ($dept->phone)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="phone" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        <span dir="ltr">{{ $dept->phone }}</span>
                                    </span>
                                @endif
                                @if ($dept->email)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="mail" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        {{ $dept->email }}
                                    </span>
                                @endif
                                @if ($dept->office_location)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="map-pin" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        {{ $dept->office_location }}
                                    </span>
                                @endif
                                @if ($dept->working_hours)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="clock" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        {{ $dept->working_hours }}
                                    </span>
                                @endif
                            </div>

                            {{-- View Link --}}
                            @if ($svcCount > 0)
                                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #F3F4F6;">
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;">
                                        <span>عرض التفاصيل</span>
                                        <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                    </span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($departments->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$departments" />
                </div>
            @endif
        </div>
    </section>

    {{-- Card Hover CSS --}}
    <style>
        .dept-card { cursor: default; }
        .dept-card:hover .dept-icon { background: #0F6A3D !important; }
        .dept-card:hover .dept-i { color: white !important; }
        .dept-card:hover .dept-title { color: #0F6A3D !important; }
    </style>

</div>
