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
    {{-- 1. PAGE CAROUSEL (inherited from Council) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'council-decisions',
        'pageTitle' => $decision->title,
        'pageSubtitle' => null,
        'pageBadge' => $typeLabel,
        'pageBadgeIcon' => 'stamp',
        'compact' => true,
    ])

    {{-- Decision info badges moved below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="calendar-days" style="width:12px;height:12px;"></i>
                <span>{{ $formatDate($decision->decision_date ?? '') }}</span>
            </span>
            @if ($decision->session_number)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="hash" style="width:12px;height:12px;"></i>
                    <span>جلسة {{ $decision->session_number }}</span>
                </span>
            @endif
            @if ($decision->published_at)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(34,197,94,0.25);color:#86EFAC;">
                    <i data-lucide="check-circle" style="width:12px;height:12px;"></i>
                    <span>نشر في {{ $formatDate($decision->published_at ?? '') }}</span>
                </span>
            @endif
            @if ($attachmentUrl && $attachmentExists)
                <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer"
                   style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:8px;font-size:11px;font-weight:700;background:white;color:#1B5E20;text-decoration:none;box-shadow:0 2px 8px rgba(0,0,0,0.1);transition:all 0.2s;"
                   onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'"
                   onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                    <i data-lucide="download" style="width:12px;height:12px;"></i>
                    <span>تحميل القرار</span>
                </a>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. MAIN CONTENT + SIDEBAR --}}
    {{-- ============================================ --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="margin-top:-30px;position:relative;z-index:20;">
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 lg:gap-8">

            {{-- === MAIN CONTENT === --}}
            <div>
                <div style="background:white;border-radius:14px;border:1px solid #F3F4F6;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.04);">

                    {{-- Summary --}}
                    @if ($decision->summary)
                        <div style="padding:28px 24px;">
                            <h2 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;display:flex;align-items:center;gap:6px;">
                                <i data-lucide="file-text" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                ملخص القرار
                            </h2>
                            <p style="font-size:13px;color:#6B7280;line-height:1.8;margin:0;">{{ $decision->summary }}</p>
                        </div>
                        @if ($decision->content)
                            <hr style="border:none;border-top:1px solid #F3F4F6;margin:0 24px;">
                        @endif
                    @endif

                    {{-- Full Content --}}
                    @if ($decision->content)
                        <div style="padding:28px 24px;">
                            <h2 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 10px;display:flex;align-items:center;gap:6px;">
                                <i data-lucide="scroll-text" style="width:14px;height:14px;color:#0F6A3D;"></i>
                                نص القرار
                            </h2>
                            <div style="font-size:13px;color:#6B7280;line-height:1.8;">{{ $decision->content }}</div>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div style="padding:20px 24px;border-top:1px solid #F3F4F6;display:flex;flex-wrap:wrap;gap:10px;">
                        <button onclick="window.print()" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:#F3F4F6;color:#374151;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">
                            <i data-lucide="printer" style="width:14px;height:14px;"></i>
                            <span>طباعة</span>
                        </button>
                        <button x-data="{ copied: false }" x-on:click="navigator.clipboard.writeText(window.location.href);copied=true;setTimeout(()=>copied=false,2000)" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:10px;background:#F3F4F6;color:#374151;font-size:12px;font-weight:600;border:none;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#E5E7EB'" onmouseout="this.style.background='#F3F4F6'">
                            <template x-if="!copied"><i data-lucide="share-2" style="width:14px;height:14px;"></i></template>
                            <template x-if="copied"><i data-lucide="check" style="width:14px;height:14px;color:#059669;"></i></template>
                            <span x-text="copied ? 'تم نسخ الرابط' : 'نسخ الرابط'"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- === SIDEBAR === --}}
            <div class="min-w-0">
                <div class="lg:sticky lg:top-28" style="display:flex;flex-direction:column;gap:12px;">

                    @if ($attachmentUrl && $attachmentExists)
                        <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:14px 20px;border-radius:12px;background:linear-gradient(135deg,#0F6A3D,#2E7D32);color:white;font-size:13px;font-weight:700;text-decoration:none;transition:all 0.3s;box-shadow:0 4px 16px rgba(15,106,61,0.3);" onmouseover="this.style.boxShadow='0 6px 24px rgba(15,106,61,0.4)'" onmouseout="this.style.boxShadow='0 4px 16px rgba(15,106,61,0.3)'">
                            <i data-lucide="download" style="width:15px;height:15px;"></i>
                            <span>تحميل القرار</span>
                        </a>
                    @endif

                    {{-- Decision Info Card --}}
                    <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                        <p style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 14px;">معلومات القرار</p>
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="hash" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">رقم القرار</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $decision->decision_number }}</p>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="calendar-days" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">تاريخ القرار</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $formatDate($decision->decision_date ?? '', 'Y-m-d') ?: '—' }}</p>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="stamp" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">نوع القرار</p>
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $typeLabel }}</p>
                                </div>
                            </div>
                            @if ($decision->session_number)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="users" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">رقم الجلسة</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">جلسة {{ $decision->session_number }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($decision->published_at)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="check-circle" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                    </span>
                                    <div style="min-width:0;">
                                        <p style="font-size:10px;color:#9CA3AF;margin:0;">تاريخ النشر</p>
                                        <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;">{{ $formatDate($decision->published_at ?? '') }}</p>
                                    </div>
                                </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="shield" style="width:13px;height:13px;color:#0F6A3D;"></i>
                                </span>
                                <div style="min-width:0;">
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">الحالة</p>
                                    <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:4px;font-size:11px;font-weight:600;margin-top:2px;background:#ECFDF5;color:#059669;">منشور</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Attachment Card --}}
                    @if ($attachmentUrl && $attachmentExists)
                        <div style="background:white;border-radius:12px;border:1px solid #F3F4F6;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.04);">
                            <p style="font-size:10px;font-weight:700;color:#9CA3AF;text-transform:uppercase;letter-spacing:0.5px;margin:0 0 14px;">الملف المرفق</p>
                            <div style="display:flex;align-items:center;gap:10px;padding:12px;border-radius:8px;background:rgba(15,106,61,0.05);border:1px solid rgba(15,106,61,0.1);">
                                <span style="width:36px;height:36px;border-radius:8px;background:rgba(15,106,61,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="file-text" style="width:16px;height:16px;color:#0F6A3D;"></i>
                                </span>
                                <div style="flex:1;min-width:0;">
                                    <p style="font-size:12px;font-weight:600;color:#1F2937;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $attachmentName ?? 'ملف القرار' }}</p>
                                    <p style="font-size:10px;color:#9CA3AF;margin:0;">PDF</p>
                                </div>
                                <a href="{{ $attachmentUrl }}" target="_blank" rel="noopener noreferrer" aria-label="تحميل مرفق القرار" style="width:32px;height:32px;border-radius:8px;background:#0F6A3D;display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;flex-shrink:0;transition:background 0.2s;" onmouseover="this.style.background='#0D5C34'" onmouseout="this.style.background='#0F6A3D'">
                                    <i data-lucide="download" style="width:14px;height:14px;"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Previous / Next Navigation --}}
        @if ($previousDecision || $nextDecision)
            <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @if ($previousDecision)
                    <a href="{{ route('public.council.decisions.show', $previousDecision['id']) }}" wire:navigate
                       class="group flex items-center gap-4 bg-white rounded-[22px] border border-gray-100 p-5 transition-all duration-300 hover:border-primary/20 hover:shadow-md">
                        <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center shrink-0 group-hover:bg-primary/10 transition-colors">
                            <i data-lucide="chevron-right" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div class="flex-1 min-w-0 text-right">
                            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">القرار السابق</p>
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $previousDecision['decision_number'] }} - {{ $previousDecision['title'] }}</p>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif

                @if ($nextDecision)
                    <a href="{{ route('public.council.decisions.show', $nextDecision['id']) }}" wire:navigate
                       class="group flex items-center gap-4 bg-white rounded-[22px] border border-gray-100 p-5 transition-all duration-300 hover:border-primary/20 hover:shadow-md">
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-[11px] font-semibold text-gray-400 mb-0.5">القرار التالي</p>
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $nextDecision['decision_number'] }} - {{ $nextDecision['title'] }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center shrink-0 group-hover:bg-primary/10 transition-colors">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-primary"></i>
                        </div>
                    </a>
                @else
                    <div></div>
                @endif
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }
                .print-area, .print-area * {
                    visibility: visible;
                }
                .print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 20px;
                }
                header, footer, .scroll-to-top, [x-cloak] {
                    display: none !important;
                }
                @page {
                    size: A4;
                    margin: 15mm;
                }
                body {
                    font-family: 'Cairo', sans-serif;
                    color: #000;
                }
            }
        </style>
    @endpush
</div>
