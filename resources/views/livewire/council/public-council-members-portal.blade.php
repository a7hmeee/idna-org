<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'council-members',
        'fallbackTitle' => "أعضاء المجلس البلدي",
        'fallbackDescription' => "نقدم لكم أعضاء المجلس البلدي في بلدية إذنا، ونستعرض ملفاتهم الشخصية وخبراتهم.",
        'fallbackBadge' => 'المجلس البلدي',
        'fallbackIcon' => 'users',
        'fallbackImage' => !empty($carouselImages) ? $carouselImages[0] : null,
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. STATS ROW (if data exists) --}}
    {{-- ============================================ --}}
    @if (!empty($stats['total']) || !empty($stats['committees']))
        <section class="py-8 sm:py-10 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10 lg:gap-16">
                    @if (!empty($stats['total']))
                        <div style="text-align:center;">
                            <p style="font-size:28px;font-weight:900;color:#0F6A3D;margin:0;line-height:1;">{{ $stats['total'] }}</p>
                            <p style="font-size:12px;color:#6B7280;margin:4px 0 0;font-weight:600;">عضو مجلس</p>
                        </div>
                    @endif
                    @if (!empty($stats['committees']))
                        <div style="text-align:center;">
                            <p style="font-size:28px;font-weight:900;color:#0F6A3D;margin:0;line-height:1;">{{ $stats['committees'] }}</p>
                            <p style="font-size:12px;color:#6B7280;margin:4px 0 0;font-weight:600;">لجنة</p>
                        </div>
                    @endif
                    @if (!empty($stats['since']))
                        <div style="text-align:center;">
                            <p style="font-size:28px;font-weight:900;color:#0F6A3D;margin:0;line-height:1;">{{ $stats['since'] }}</p>
                            <p style="font-size:12px;color:#6B7280;margin:4px 0 0;font-weight:600;">منذ</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- 3. MEMBERS LIST --}}
    {{-- ============================================ --}}
    <section id="members-list" class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filters --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:12px;">

                    {{-- Position Filter --}}
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        <button wire:click="$set('position', '')"
                                aria-pressed="{{ $position == '' }}"
                                style="padding:7px 16px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $position == '' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $position == '' ? '#0F6A3D' : 'white' }};color:{{ $position == '' ? 'white' : '#6B7280' }};">
                            الكل
                        </button>
                        @foreach ($positionOptions as $value => $label)
                            <button wire:click="$set('position', '{{ $value }}')"
                                    aria-pressed="{{ $position == $value }}"
                                    style="padding:7px 16px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $position == $value ? '#0F6A3D' : '#E5E7EB' }};background:{{ $position == $value ? '#0F6A3D' : 'white' }};color:{{ $position == $value ? 'white' : '#6B7280' }};">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Search --}}
                    <div style="position:relative;width:100%;max-width:340px;margin-right:auto;">
                        <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                        <span class="sr-only" role="status" wire:loading wire:target="search">جاري تحديث النتائج…</span>
<input type="text" wire:model.live.debounce.400ms="search" aria-label="ابحث عن عضو"
                               placeholder="ابحث عن عضو..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            {{-- Results Count --}}
            <div style="margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $members->total() }}</span> عضو
                </p>
            </div>

            {{-- Members Grid --}}
            @if ($members->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="users" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h2>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach ($members as $member)
                        <a href="{{ route('public.council.show', $member->slug) }}" wire:navigate
                           class="group block bg-white rounded-2xl border border-gray-100 overflow-hidden transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-3px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">

                            {{-- Photo --}}
                            <div style="position:relative;aspect-ratio:1;background:linear-gradient(135deg,#F9FAFB,#F3F4F6);overflow:hidden;">
                                @if ($member->photo_url)
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}"
                                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.5s;"
                                         class="group-hover:scale-105"
                                         loading="lazy">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <i data-lucide="user" style="width:48px;height:48px;color:#D1D5DB;"></i>
                                    </div>
                                @endif
                                <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.5),transparent 50%);opacity:0;transition:opacity 0.3s;" class="group-hover:opacity-100"></div>
                            </div>

                            {{-- Content --}}
                            <div style="padding:16px 18px 18px;">
                                <h2 style="font-size:14px;font-weight:800;color:#1F2937;margin:0 0 3px;transition:color 0.2s;" class="group-hover:text-primary">
                                    {{ $member->full_name }}
                                </h2>

                                @php
                                    $posLabel = '';
                                    try { $posLabel = \App\Domains\Municipality\Enums\CouncilMemberPosition::tryFrom($member->position)?->label() ?? $member->position; } catch (\Throwable $e) { $posLabel = $member->position; }
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(15,106,61,0.07);color:#0F6A3D;margin-bottom:8px;">
                                    <i data-lucide="badge-check" style="width:11px;height:11px;"></i>
                                    {{ $posLabel }}
                                </span>

                                @if ($member->bio)
                                    <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 12px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                        {{ $member->bio }}
                                    </p>
                                @elseif ($member->committee)
                                    <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 12px;">
                                        <i data-lucide="folder-kanban" style="width:11px;height:11px;display:inline;"></i>
                                        {{ $member->committee }}
                                    </p>
                                @endif

                                @if ($member->term_start)
                                    <p style="font-size:11px;color:#9CA3AF;margin:0 0 12px;">
                                        <i data-lucide="calendar" style="width:11px;height:11px;display:inline;"></i>
                                        @if ($member->term_end)
                                            {{ $member->term_start->format('Y') }} - {{ $member->term_end->format('Y') }}
                                        @else
                                            من {{ $member->term_start->format('Y') }}
                                        @endif
                                    </p>
                                @endif

                                <div style="margin-top:auto;padding-top:12px;border-top:1px solid #F3F4F6;">
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;transition:gap 0.2s;" class="group-hover:gap-2">
                                        <span>عرض الملف الشخصي</span>
                                        <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($members->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$members" />
                </div>
            @endif
        </div>
    </section>

</div>
