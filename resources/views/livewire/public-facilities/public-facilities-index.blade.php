<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'facilities',
        'fallbackTitle' => "المرافق العامة",
        'fallbackDescription' => "استعرض جميع المرافق العامة التي تديرها البلدية، وتعرف على الخدمات التي تقدمها.",
        'fallbackBadge' => 'المرافق العامة',
        'fallbackIcon' => 'building-2',
        'fallbackImage' => !empty($carouselImages) ? $carouselImages[0] : null,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. FACILITIES LIST --}}
    {{-- ============================================ --}}
    <section id="facilities-list" class="py-12 sm:py-16 lg:py-20">
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
<input type="text" wire:model.live.debounce.400ms="search" aria-label="ابحث عن مرفق"
                               placeholder="ابحث عن مرفق..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            {{-- Results Count + Featured Section --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $facilities->total() ?? 0 }}</span> مرفق
                </p>
            </div>

            {{-- Featured Row --}}
            @if ($featured->isNotEmpty() && $filter !== 'featured')
                <div style="margin-bottom:28px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                        <i data-lucide="star" style="width:16px;height:16px;color:#D97706;"></i>
                        <h2 style="font-size:15px;font-weight:700;color:#1F2937;margin:0;">مرافق مميزة</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($featured as $facility)
                            <a href="{{ route('public.facilities.show', $facility->slug) }}" wire:navigate
                               class="block bg-white rounded-2xl border-2 border-yellow-100 p-5 transition-all duration-200"
                               style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                               onmouseover="this.style.borderColor='#FCD34D';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.borderColor='#FEF3C7';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                                <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:10px;">
                                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(217,119,6,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="star" style="width:20px;height:20px;color:#D97706;"></i>
                                    </div>
                                    <div style="min-width:0;flex:1;">
                                        <h2 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 2px;">{{ $facility->name }}</h2>
                                        @if ($facility->category)
                                            <span style="font-size:11px;color:#9CA3AF;">{{ $facility->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $facility->summary }}</p>
                                <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9CA3AF;">
                                    <i data-lucide="map-pin" style="width:11px;height:11px;"></i>
                                    <span class="truncate">{{ $facility->address }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Cards Grid --}}
            @if ($facilities->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="building-2" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h2>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($facilities as $facility)
                        <a href="{{ route('public.facilities.show', $facility->slug) }}" wire:navigate
                           class="facility-card block bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px')"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                            {{-- Image --}}
                            @if ($facility->cover_image_url)
                                <div class="aspect-video bg-gray-100 overflow-hidden">
                                    <img src="{{ $facility->cover_image_url }}" alt="{{ $facility->name }}"
                                         class="w-full h-full object-cover transition-transform duration-300"
                                         style="transition:transform 0.3s;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'"
                                         loading="lazy" />
                                </div>
                            @else
                                <div class="aspect-video" style="background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;">
                                    <i data-lucide="building-2" style="width:36px;height:36px;color:#9CA3AF;"></i>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div style="padding:16px;">
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
                                    @if ($facility->category?->icon)
                                        <i data-lucide="{{ $facility->category->icon }}" style="width:12px;height:12px;color:#0F6A3D;"></i>
                                    @endif
                                    <span style="font-size:11px;font-weight:600;color:#0F6A3D;">{{ $facility->category?->name ?? 'مرفق عام' }}</span>
                                    @if ($facility->is_featured)
                                        <span style="display:inline-flex;align-items:center;gap:2px;font-size:10px;font-weight:600;color:#D97706;background:rgba(217,119,6,0.08);padding:1px 6px;border-radius:4px;">
                                            <i data-lucide="star" style="width:10px;height:10px;"></i>
                                            مميز
                                        </span>
                                    @endif
                                </div>
                                <h2 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 6px;line-height:1.4;">{{ $facility->name }}</h2>
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $facility->summary }}</p>
                                <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9CA3AF;">
                                    <i data-lucide="map-pin" style="width:11px;height:11px;flex-shrink:0;"></i>
                                    <span class="truncate">{{ $facility->address }}</span>
                                </div>
                                <div style="margin-top:10px;display:flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;">
                                    <span>عرض التفاصيل</span>
                                    <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($facilities->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$facilities" />
                </div>
            @endif
        </div>
    </section>

    <style>
        .facility-card { cursor: default; }
    </style>

</div>