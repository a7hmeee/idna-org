<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'tenders',
        'fallbackTitle' => "المناقصات والعطاءات",
        'fallbackDescription' => "استعرض جميع المناقصات والعطاءات في بلدية إذنا، واطلع على شروط التقديم والمواعيد النهائية.",
        'fallbackBadge' => 'المناقصات',
        'fallbackIcon' => 'scroll-text',
        'fallbackImage' => !empty($carouselImages) ? $carouselImages[0] : null,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. TENDERS LIST --}}
    {{-- ============================================ --}}
    <section id="tenders-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filter --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <button wire:click="$set('filter', 'all')"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filter == 'all' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filter == 'all' ? '#0F6A3D' : 'white' }};color:{{ $filter == 'all' ? 'white' : '#6B7280' }};">
                            الكل
                        </button>
                        <button wire:click="$set('filter', 'featured')"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filter == 'featured' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filter == 'featured' ? '#0F6A3D' : 'white' }};color:{{ $filter == 'featured' ? 'white' : '#6B7280' }};">
                            <i data-lucide="star" style="width:12px;height:12px;"></i>
                            المميزة
                        </button>
                    </div>

                    <div style="position:relative;width:100%;max-width:340px;">
                        <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث عن مناقصة..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            {{-- Results Count --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $tenders->total() ?? 0 }}</span> مناقصة
                </p>
            </div>

            {{-- Featured Row --}}
            @if ($featured->isNotEmpty() && $filter !== 'featured')
                <div style="margin-bottom:28px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                        <i data-lucide="star" style="width:16px;height:16px;color:#D97706;"></i>
                        <h2 style="font-size:15px;font-weight:700;color:#1F2937;margin:0;">مناقصات مميزة</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($featured as $tender)
                            <a href="{{ route('public.tenders.show', $tender->slug) }}" wire:navigate
                               class="block bg-white rounded-2xl border-2 border-yellow-100 p-5 transition-all duration-200"
                               style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                               onmouseover="this.style.borderColor='#FCD34D';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.borderColor='#FEF3C7';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                                <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:10px;">
                                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(217,119,6,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="scroll-text" style="width:20px;height:20px;color:#D97706;"></i>
                                    </div>
                                    <div style="min-width:0;flex:1;">
                                        <h3 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 2px;">{{ $tender->title_ar }}</h3>
                                        @if ($tender->tender_number)
                                            <span style="font-size:11px;color:#9CA3AF;">{{ $tender->tender_number }}</span>
                                        @endif
                                    </div>
                                </div>
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $tender->summary }}</p>
                                <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9CA3AF;">
                                    <i data-lucide="building-2" style="width:11px;height:11px;"></i>
                                    <span class="truncate">{{ $tender->issuing_department }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Tenders List --}}
            @if ($tenders->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="scroll-text" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد مناقصات حالياً</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($tenders as $tender)
                        <a href="{{ route('public.tenders.show', $tender->slug) }}" class="block bg-white rounded-xl border border-gray-200 p-5 hover:shadow-sm transition-shadow" wire:navigate>
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-text">{{ $tender->title_ar }}</h3>
                                    <p class="text-sm text-text-secondary mt-1 line-clamp-2">{{ $tender->summary }}</p>
                                    <div class="flex items-center gap-4 mt-3 text-xs text-text-tertiary flex-wrap">
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="building-2" class="w-3 h-3"></i>
                                            {{ $tender->issuing_department ?? 'بلدية إذنا' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1">
                                            <i data-lucide="calendar" class="w-3 h-3"></i>
                                            آخر موعد: {{ $tender->submission_deadline?->format('Y/m/d') ?? '—' }}
                                        </span>
                                        @if ($tender->budget)
                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="wallet" class="w-3 h-3"></i>
                                                {{ number_format((float) $tender->budget) }} {{ $tender->budget_currency }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 flex flex-col items-end gap-2">
                                    <span class="text-xs px-2.5 py-1 rounded-full font-semibold
                                        @if($tender->status->value === 'open') bg-success/10 text-success
                                        @elseif($tender->status->value === 'closed') bg-danger/10 text-danger
                                        @elseif($tender->status->value === 'awarded') bg-info/10 text-info
                                        @else bg-municipal-50 text-text-tertiary @endif">
                                        {{ $tender->status->label() }}
                                    </span>
                                    @if ($tender->is_featured)
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-full font-semibold">مميز</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($tenders->hasPages())
                <div class="mt-10">
                    {{ $tenders->links() }}
                </div>
            @endif
        </div>
    </section>

</div>
