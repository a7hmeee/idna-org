<div>

    @livewire('public-page-carousel', [
        'pageKey' => 'engineering-offices',
        'fallbackTitle' => "المكاتب الهندسية",
        'fallbackDescription' => "تصفح المكاتب الهندسية المعتمدة من قبل البلدية، وتعرف على خدماتها.",
        'fallbackBadge' => 'المكاتب الهندسية',
        'fallbackIcon' => 'hard-hat',
        'fallbackImage' => $slides->isNotEmpty() ? $slides->first()->image_url : null,
        'compact' => false,
    ])

    <section id="offices-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
<input type="text" wire:model.live.debounce.400ms="search" aria-label="ابحث عن مكتب هندسي"
                               placeholder="ابحث عن مكتب..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $offices->total() }}</span> مكتب هندسي
                </p>
            </div>

            @if ($offices->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="hard-hat" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h2 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h2>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($offices as $office)
                        <a href="{{ route('public.engineering-offices.show', $office->slug) }}" wire:navigate
                           class="office-card block bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                           style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                           onmouseover="this.style.borderColor='rgba(15,106,61,0.15)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.borderColor='#F3F4F6';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:12px;">
                                <div style="width:52px;height:52px;border-radius:14px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="hard-hat" style="width:22px;height:22px;color:#0F6A3D;"></i>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h2 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 2px;">{{ $office->office_name }}</h2>
                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                        @if ($office->engineer_name)
                                            <span style="font-size:11px;color:#6B7280;">{{ $office->engineer_name }}</span>
                                        @endif
                                        @if ($office->is_featured)
                                            <span style="display:inline-flex;align-items:center;gap:2px;font-size:10px;font-weight:600;color:#D97706;background:rgba(217,119,6,0.08);padding:1px 6px;border-radius:4px;">
                                                <i data-lucide="star" style="width:10px;height:10px;"></i>
                                                مميز
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if ($office->specializations && is_array($office->specializations))
                                <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:12px;">
                                    @foreach (array_slice($office->specializations, 0, 3) as $spec)
                                        <span style="font-size:10px;font-weight:600;color:#0F6A3D;background:rgba(15,106,61,0.06);padding:2px 8px;border-radius:6px;">{{ $spec }}</span>
                                    @endforeach
                                    @if (count($office->specializations) > 3)
                                        <span style="font-size:10px;color:#9CA3AF;">+{{ count($office->specializations) - 3 }}</span>
                                    @endif
                                </div>
                            @endif

                            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
                                @if ($office->license_number)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="file-text" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        رخصة: <span dir="ltr">{{ $office->license_number }}</span>
                                    </span>
                                @endif
                                @if ($office->phone)
                                    <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;color:#6B7280;">
                                        <i data-lucide="phone" style="width:12px;height:12px;color:#9CA3AF;"></i>
                                        <span dir="ltr">{{ $office->phone }}</span>
                                    </span>
                                @endif
                            </div>

                            <div style="margin-top:12px;padding-top:12px;border-top:1px solid #F3F4F6;">
                                <span style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;">
                                    <span>عرض التفاصيل</span>
                                    <i data-lucide="arrow-left" style="width:12px;height:12px;"></i>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($offices->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$offices" />
                </div>
            @endif
        </div>
    </section>

</div>