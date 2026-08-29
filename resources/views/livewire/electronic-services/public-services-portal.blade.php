<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'services',
        'fallbackTitle' => "كل الخدمات البلدية",
        'fallbackDescription' => "استعرض جميع الخدمات الإلكترونية التي تقدمها البلدية، واختر الخدمة المناسبة لتقديم طلبك إلكترونياً بكل سهولة ويسر.",
        'fallbackBadge' => 'بوابة الخدمات الإلكترونية',
        'fallbackIcon' => 'laptop',
        'fallbackImage' => $heroImage,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. SERVICES SECTION (Search + Tags + Cards) --}}
    {{-- ============================================ --}}
    <section id="services" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Top Bar: Filters + Search --}}
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    {{-- Filter Button --}}
                    <button style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:#0F6A3D;color:white;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s;"
                            wire:click="toggleFilters" :aria-expanded="$wire.showFilters" aria-controls="portal-filters"
                            onmouseover="this.style.background='#0D5C34'"
                            onmouseout="this.style.background='#0F6A3D'">
                        <i data-lucide="filter" style="width:14px;height:14px;"></i>
                        <span>تصفية</span>
                        <i data-lucide="chevron-up" style="width:12px;height:12px;" x-show="$wire.showFilters" x-cloak></i>
                        <i data-lucide="chevron-down" style="width:12px;height:12px;" x-show="!$wire.showFilters" x-cloak></i>
                    </button>
                </div>

                {{-- Search Input --}}
                <div style="flex:1;max-width:400px;position:relative;">
                    <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                    <input type="text" wire:model.live.debounce.400ms="search" id="services-search"
                           aria-label="ابحث عن خدمة"
                           placeholder="ابحث عن خدمة..."
                           style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:12px 44px 12px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                           onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                           onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                </div>
            </div>

            {{-- Category Tags --}}
            <div id="portal-filters" x-data x-show="$wire.showFilters" x-transition x-cloak style="margin-bottom:24px;">
                <p style="font-size:13px;font-weight:600;color:#4B5563;margin:0 0 10px;">القائمة</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    <button wire:click="$set('filterCategoryId', '')"
                            aria-pressed="{{ $filterCategoryId == '' ? 'true' : 'false' }}"
                            style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filterCategoryId == '' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filterCategoryId == '' ? '#0F6A3D' : 'white' }};color:{{ $filterCategoryId == '' ? 'white' : '#6B7280' }};">
                        الكل
                    </button>
                    @foreach ($categories as $cat)
                        <button wire:click="$set('filterCategoryId', '{{ $cat->id }}')"
                                aria-pressed="{{ $filterCategoryId == $cat->id ? 'true' : 'false' }}"
                                style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filterCategoryId == $cat->id ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filterCategoryId == $cat->id ? '#0F6A3D' : 'white' }};color:{{ $filterCategoryId == $cat->id ? 'white' : '#6B7280' }};">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Results Count --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $services->total() ?? 0 }}</span> خدمة
                    <span class="sr-only" role="status" wire:loading wire:target="search, filterCategoryId">جاري تحديث النتائج…</span>
                </p>
                @if ($filterDepartmentSlug)
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:12px;color:#6B7280;">تصفية حسب القسم:</span>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:20px;background:#0F6A3D;color:white;font-size:12px;font-weight:600;">
                            {{ $filterDepartmentName ?? $filterDepartmentSlug }}
                            <button wire:click="$set('filterDepartmentSlug', null)" style="background:none;border:none;color:white;cursor:pointer;padding:0;display:flex;">
                                <i data-lucide="x" style="width:12px;height:12px;"></i>
                            </button>
                        </span>
                    </div>
                @endif
            </div>

            {{-- ============================================ --}}
            {{-- SERVICES GRID --}}
            {{-- ============================================ --}}
            @if ($services->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="search-x" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 transition-opacity duration-200" wire:loading.class="opacity-50" wire:loading.attr="aria-busy" wire:target="search, filterCategoryId">
                    @foreach ($services as $service)
                        <a href="{{ route('public.services.show', ['category' => $service->category?->slug ?? 'general', 'service' => $service->slug]) }}" wire:navigate
                           class="svc-card block bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                            <div style="display:flex;align-items:flex-start;gap:14px;">
                                <div class="svc-icon" style="width:52px;height:52px;border-radius:14px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s;">
                                    <i data-lucide="{{ $service->category?->icon ?? 'file-text' }}" class="svc-i" style="width:22px;height:22px;color:#0F6A3D;transition:color 0.2s;"></i>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h3 class="svc-title" style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 4px;transition:color 0.2s;line-height:1.4;">{{ $service->name }}</h3>
                                    @if ($service->description)
                                        <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $service->description }}</p>
                                    @endif
                                    <div style="margin-top:10px;">
                                        <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;">
                                            اعرف المزيد
                                            <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($services->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$services" />
                </div>
            @endif
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 3. HOW TO APPLY --}}
    {{-- ============================================ --}}
    <section class="py-16 sm:py-20 lg:py-28" style="background:#F9FAFB;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:9999px;font-size:12px;font-weight:700;background:rgba(15,106,61,0.06);color:#0F6A3D;margin-bottom:16px;">
                    <i data-lucide="list-checks" style="width:14px;height:14px;"></i>
                    خطوات مبسطة
                </span>
                <h2 style="font-size:clamp(22px,3vw,40px);font-weight:900;color:#1F2937;margin:0 0 8px;">كيف تحصل على الخدمة؟</h2>
                <p style="font-size:clamp(13px,1.2vw,15px);color:#6B7280;max-width:500px;margin:0 auto;">اتبع هذه الخطوات البسيطة لتقديم طلبك إلكترونياً</p>
            </div>

            @php
                $steps = [
                    ['title' => 'اختر تصنيف الخدمة', 'description' => 'تصفح التصنيفات المتاحة واختر التصنيف المناسب لطلبك', 'icon' => 'folder-tree', 'number' => '01'],
                    ['title' => 'اطلع على المتطلبات', 'description' => 'تعرف على الوثائق والمتطلبات اللازمة لتقديم الطلب', 'icon' => 'clipboard-list', 'number' => '02'],
                    ['title' => 'انتقل إلى البوابة', 'description' => 'اضغط على زر التقديم لتنتقل إلى بوابة الخدمات الرسمية', 'icon' => 'external-link', 'number' => '03'],
                    ['title' => 'تابع معاملتك', 'description' => 'قدم طلبك وتابع حالة المعاملة عبر البوابة الإلكترونية', 'icon' => 'message-circle', 'number' => '04'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                @foreach ($steps as $step)
                    <div style="position:relative;background:white;border-radius:20px;border:1px solid #F3F4F6;padding:32px 24px;text-align:center;transition:all 0.3s;"
                         onmouseover="this.style.borderColor='rgba(15,106,61,0.2)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'"
                         onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='none'">
                        <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);width:32px;height:32px;border-radius:50%;background:#0F6A3D;color:white;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(15,106,61,0.3);">
                            {{ $step['number'] }}
                        </div>
                        <div style="width:56px;height:56px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                            <i data-lucide="{{ $step['icon'] }}" style="width:24px;height:24px;color:#0F6A3D;"></i>
                        </div>
                        <h3 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 8px;">{{ $step['title'] }}</h3>
                        <p style="font-size:12px;color:#6B7280;line-height:1.7;margin:0;">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 4. PORTAL CTA --}}
    {{-- ============================================ --}}
    @if ($portalUrl)
        <section class="py-16 sm:py-20 lg:py-28 relative overflow-hidden" style="background:linear-gradient(135deg,#0F6A3D,#2E7D32,#1B5E20);">
            @if ($ctaBackground)
                <div style="position:absolute;inset:0;">
                    <img src="{{ $ctaBackground }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(15,106,61,0.85),rgba(46,125,50,0.80),rgba(27,94,32,0.85));"></div>
            @else
                <div style="position:absolute;inset:0;opacity:0.10;">
                    <div style="position:absolute;top:20px;right:20px;width:200px;height:200px;background:white;border-radius:50%;filter:blur(60px);"></div>
                    <div style="position:absolute;bottom:20px;left:20px;width:300px;height:300px;background:white;border-radius:50%;filter:blur(80px);"></div>
                </div>
            @endif
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" style="position:relative;z-index:10;">
                <div style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,0.15);">
                    <i data-lucide="external-link" style="width:32px;height:32px;color:white;"></i>
                </div>
                <h2 style="font-size:clamp(22px,3vw,40px);font-weight:900;color:white;margin:0 0 12px;">هل تريد تقديم طلبك الآن؟</h2>
                <p style="font-size:clamp(14px,1.3vw,17px);color:rgba(255,255,255,0.85);max-width:500px;margin:0 auto 28px;">انتقل إلى بوابة الخدمات الرسمية لتقديم طلبك إلكترونياً</p>
                <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border-radius:14px;background:white;color:#0F6A3D;font-size:14px;font-weight:700;text-decoration:none;transition:all 0.3s;box-shadow:0 4px 20px rgba(0,0,0,0.2);"
                   onmouseover="this.style.boxShadow='0 8px 30px rgba(0,0,0,0.3)';this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.2)';this.style.transform='translateY(0)'">
                    <span>الدخول إلى بوابة الخدمات</span>
                    <i data-lucide="external-link" style="width:16px;height:16px;"></i>
                </a>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- Card Hover CSS --}}
    {{-- ============================================ --}}
    <style>
        .svc-card { cursor: pointer; }
        .svc-card:hover .svc-icon { background: #0F6A3D !important; }
        .svc-card:hover .svc-i { color: white !important; }
        .svc-card:hover .svc-title { color: #0F6A3D !important; }
    </style>

</div>
