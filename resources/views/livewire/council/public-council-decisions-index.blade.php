<div>
@php
    $formatDate = function ($date, string $format = 'Y-m-d'): string {
        if (empty($date)) return '';
        try {
            if ($date instanceof \DateTimeInterface) return $date->format($format);
            if (is_string($date)) return \Carbon\Carbon::parse($date)->format($format);
        } catch (\Throwable) {}
        return '';
    };
@endphp

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'council-decisions',
        'fallbackTitle' => "قرارات المجلس البلدي",
        'fallbackDescription' => "استعرض قرارات المجلس البلدي في {{ $municipalityName }}, وتصفح القرارات الإدارية والمالية والتنظيمية والصادرة عن المجلس.",
        'fallbackBadge' => 'المجلس البلدي',
        'fallbackIcon' => 'file-text',
        'fallbackImage' => $heroImageUrl,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. STATISTICS ROW --}}
    {{-- ============================================ --}}
    @if (!empty($stats['total']))
        <section class="py-8 sm:py-10 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10 lg:gap-16">
                    <div class="text-center">
                        <p class="text-[28px] font-black text-primary leading-none">{{ $stats['total'] }}</p>
                        <p class="text-xs text-gray-500 mt-1 font-semibold">قرار منشور</p>
                    </div>
                    @if (!empty($stats['this_year']))
                        <div class="text-center">
                            <p class="text-[28px] font-black text-primary leading-none">{{ $stats['this_year'] }}</p>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">قرار هذا العام</p>
                        </div>
                    @endif
                    @if (!empty($stats['type_count']))
                        <div class="text-center">
                            <p class="text-[28px] font-black text-primary leading-none">{{ $stats['type_count'] }}</p>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">نوع من القرارات</p>
                        </div>
                    @endif
                    @if (!empty($stats['latest_date']))
                        <div class="text-center">
                            <p class="text-lg font-black text-primary leading-none">{{ $formatDate($stats['latest_date'] ?? '') }}</p>
                            <p class="text-xs text-gray-500 mt-1 font-semibold">آخر قرار</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- 3. SEARCH & FILTERS --}}
    {{-- ============================================ --}}
    <section id="decisions-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Top Bar: Search + View Toggle --}}
            <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:24px;">
                <div style="display:flex;align-items:center;gap:8px;">
                    {{-- View Toggle --}}
                    <div style="display:flex;align-items:center;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;overflow:hidden;">
                        <button style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#F3F4F6;color:#6B7280;border:none;cursor:pointer;transition:all 0.2s;">
                            <i data-lucide="list" style="width:16px;height:16px;"></i>
                        </button>
                        <button style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;background:#0F6A3D;color:white;border:none;cursor:pointer;">
                            <i data-lucide="grid-3x2" style="width:16px;height:16px;"></i>
                        </button>
                    </div>

                    {{-- Filter Button --}}
                    <button style="display:flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:#0F6A3D;color:white;font-size:13px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s;"
                            wire:click="toggleFilters"
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
                    <input type="text" wire:model.live.debounce.400ms="search"
                           placeholder="ابحث عن قرار..."
                           style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:12px 44px 12px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                           onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                           onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                </div>
            </div>

            {{-- Filter Panel --}}
            <div x-data x-show="$wire.showFilters" x-transition x-cloak style="margin-bottom:24px;">
                {{-- Year Chips --}}
                @if (!empty($years))
                    <p style="font-size:13px;font-weight:600;color:#4B5563;margin:0 0 10px;">السنة</p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
                        <button wire:click="$set('year', '')"
                                style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $year == '' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $year == '' ? '#0F6A3D' : 'white' }};color:{{ $year == '' ? 'white' : '#6B7280' }};">
                            الكل
                        </button>
                        @foreach ($years as $y)
                            <button wire:click="$set('year', '{{ $y }}')"
                                    style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $year == $y ? '#0F6A3D' : '#E5E7EB' }};background:{{ $year == $y ? '#0F6A3D' : 'white' }};color:{{ $year == $y ? 'white' : '#6B7280' }};">
                                {{ $y }}
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Type Chips --}}
                <p style="font-size:13px;font-weight:600;color:#4B5563;margin:0 0 10px;">النوع</p>
                <div style="display:flex;flex-wrap:wrap;gap:8px;">
                    <button wire:click="$set('type', '')"
                            style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $type == '' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $type == '' ? '#0F6A3D' : 'white' }};color:{{ $type == '' ? 'white' : '#6B7280' }};">
                        الكل
                    </button>
                    @foreach ($typeOptions as $value => $label)
                        <button wire:click="$set('type', '{{ $value }}')"
                                style="padding:8px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $type == $value ? '#0F6A3D' : '#E5E7EB' }};background:{{ $type == $value ? '#0F6A3D' : 'white' }};color:{{ $type == $value ? 'white' : '#6B7280' }};">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Results count --}}
            <div style="margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $decisions->total() }}</span> قرار
                    @if ($year)
                        لعام <span style="font-weight:700;color:#0F6A3D;">{{ $year }}</span>
                    @endif
                </p>
            </div>

            {{-- Decisions Grid --}}
            @if ($decisions->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="file-x" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد قرارات مطابقة</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر الفلاتر</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach ($decisions as $decision)
                        <a href="{{ route('public.council.decisions.show', $decision->id) }}" wire:navigate
                           class="group block bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-300"
                           style="box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-3px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">

                            <div style="padding:20px 22px;">
                                {{-- Top: Type badge + Number --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:12px;">
                                    @php
                                        $typeLabel = \App\Domains\Municipality\Enums\CouncilDecisionType::tryFrom($decision->type)?->label() ?? $decision->type;
                                    @endphp
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:6px;font-size:11px;font-weight:700;background:rgba(15,106,61,0.07);color:#0F6A3D;">
                                        <i data-lucide="stamp" style="width:12px;height:12px;"></i>
                                        {{ $typeLabel }}
                                    </span>
                                    <span style="font-size:12px;font-weight:700;color:#0F6A3D;">{{ $decision->decision_number }}</span>
                                </div>

                                {{-- Date --}}
                                <div style="display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                                    <i data-lucide="calendar-days" style="width:14px;height:14px;color:#9CA3AF;"></i>
                                    <span style="font-size:12px;color:#6B7280;">{{ $formatDate($decision->decision_date ?? '') }}</span>
                                    @if ($decision->session_number)
                                        <span style="font-size:12px;color:#D1D5DB;margin:0 4px;">·</span>
                                        <span style="font-size:12px;color:#6B7280;">جلسة {{ $decision->session_number }}</span>
                                    @endif
                                </div>

                                {{-- Title --}}
                                <h3 style="font-weight:700;font-size:14px;color:#1F2937;margin:0 0 6px;transition:color 0.2s;" class="group-hover:text-primary">
                                    {{ $decision->title }}
                                </h3>

                                {{-- Summary --}}
                                @if ($decision->summary)
                                    <p style="font-size:12px;color:#9CA3AF;line-height:1.7;margin:0 0 14px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                        {{ $decision->summary }}
                                    </p>
                                @endif

                                {{-- Bottom --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #F3F4F6;">
                                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#0F6A3D;transition:gap 0.2s;" class="group-hover:gap-2">
                                        <span>عرض التفاصيل</span>
                                        <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                    </span>
                                    @if ($decision->attachment_path)
                                        @php
                                            $fileUrl = asset('storage/' . $decision->attachment_path);
                                            $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($decision->attachment_path);
                                        @endphp
                                        @if ($fileExists)
                                            <a href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer"
                                               onclick="event.stopPropagation();"
                                               style="display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:6px;background:rgba(15,106,61,0.07);color:#0F6A3D;font-size:11px;font-weight:600;text-decoration:none;transition:background 0.2s;">
                                                <i data-lucide="file-text" style="width:11px;height:11px;"></i>
                                                <span>PDF</span>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($decisions->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$decisions" />
                </div>
            @endif
        </div>
    </section>
</div>
