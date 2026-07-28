<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'tenders',
        'pageTitle' => $tender->title_ar,
        'pageSubtitle' => $tender->summary ?? null,
        'pageBadge' => 'مناقصة',
        'pageBadgeIcon' => 'scroll-text',
        'compact' => true,
    ])

    {{-- Info badges below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            @if ($tender->issuing_department)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="building-2" style="width:12px;height:12px;"></i>
                    <span>{{ $tender->issuing_department }}</span>
                </span>
            @endif
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="clock" style="width:12px;height:12px;"></i>
                <span>آخر موعد: {{ $tender->submission_deadline?->format('Y/m/d') ?? '—' }}</span>
            </span>
            @if ($tender->is_featured)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مميزة</span>
                </span>
            @endif
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;
                @if($tender->status->value === 'open') background:rgba(46,125,50,0.15);color:#2E7D32;
                @elseif($tender->status->value === 'closed') background:rgba(211,47,47,0.15);color:#D32F2F;
                @elseif($tender->status->value === 'awarded') background:rgba(21,101,192,0.15);color:#1565C0;
                @else background:rgba(158,158,158,0.15);color:#757575; @endif">
                <i data-lucide="circle" style="width:12px;height:12px;"></i>
                <span>{{ $tender->status->label() }}</span>
            </span>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. TENDER DETAIL --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Summary --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-text mb-3">نبذة عن المناقصة</h2>
                        <p class="text-text-secondary leading-relaxed">{{ $tender->summary }}</p>
                    </div>

                    {{-- Description --}}
                    @if ($tender->description)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">الوصف</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line">{{ $tender->description }}</div>
                        </div>
                    @endif

                    {{-- Eligibility Requirements --}}
                    @if ($tender->eligibility_requirements)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">شروط التأهيل</h2>
                            <ul class="space-y-2">
                                @foreach ($tender->eligibility_requirements as $req)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $req }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Application Instructions --}}
                    @if ($tender->application_instructions)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">تعليمات التقديم</h2>
                            <ul class="space-y-2">
                                @foreach ($tender->application_instructions as $inst)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="info" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $inst }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Tender Documents --}}
                    @if ($tender->tender_documents)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">مستندات المناقصة</h2>
                            <ul class="space-y-2">
                                @foreach ($tender->tender_documents as $doc)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="file-text" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $doc }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Result Documents --}}
                    @if ($tender->result_documents)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">نتائج المناقصة</h2>
                            <ul class="space-y-2">
                                @foreach ($tender->result_documents as $doc)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="award" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $doc }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Related Tenders --}}
                    @if ($relatedTenders->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">مناقصات أخرى</h2>
                            <div class="space-y-3">
                                @foreach ($relatedTenders as $related)
                                    <a href="{{ route('public.tenders.show', $related->slug) }}" wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors no-underline group">
                                        <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="scroll-text" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $related->title_ar }}</h3>
                                            <p class="text-xs text-text-tertiary">{{ $related->issuing_department }} · {{ $related->status->label() }}</p>
                                        </div>
                                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-colors"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-4">

                    {{-- Quick Info --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3">معلومات المناقصة</h3>
                        <div class="space-y-3 text-sm">
                            @if ($tender->tender_number)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">رقم المناقصة</span>
                                    <span class="text-text font-semibold">{{ $tender->tender_number }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">الجهة المصدرة</span>
                                <span class="text-text font-semibold">{{ $tender->issuing_department ?? '—' }}</span>
                            </div>
                            @if ($tender->category)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">التصنيف</span>
                                    <span class="text-text font-semibold">{{ $tender->category }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">تاريخ النشر</span>
                                <span class="text-text font-semibold">{{ $tender->publication_date?->format('Y/m/d') ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">آخر موعد</span>
                                <span class="text-text font-semibold">{{ $tender->submission_deadline?->format('Y/m/d') ?? '—' }}</span>
                            </div>
                            @if ($tender->opening_date)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">تاريخ الفتح</span>
                                    <span class="text-text font-semibold">{{ $tender->opening_date->format('Y/m/d') }}</span>
                                </div>
                            @endif
                            @if ($tender->budget)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الميزانية</span>
                                    <span class="text-text font-semibold">{{ number_format((float) $tender->budget) }} {{ $tender->budget_currency }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Deadline Countdown --}}
                    @if ($tender->submission_deadline)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="font-bold text-text mb-3">الوقت المتبقي</h3>
                            @php
                                $daysLeft = now()->diffInDays($tender->submission_deadline, false);
                            @endphp
                            @if ($daysLeft > 0)
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-primary">{{ $daysLeft }}</span>
                                    <span class="text-sm text-text-tertiary block mt-1">يوماً متبقياً</span>
                                    <div class="mt-3 w-full bg-gray-100 rounded-full h-2">
                                        @php
                                            $totalDays = max(1, now()->diffInDays($tender->publication_date ?? $tender->submission_deadline->copy()->subMonth()));
                                            $progress = max(0, min(100, ($totalDays - $daysLeft) / $totalDays * 100));
                                        @endphp
                                        <div class="bg-primary h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @elseif ($daysLeft === 0)
                                <div class="text-center">
                                    <span class="text-xl font-bold text-warning">آخر يوم اليوم</span>
                                </div>
                            @else
                                <div class="text-center">
                                    <span class="text-xl font-bold text-danger">انتهى التقديم</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Contact Info --}}
                    @if ($tender->contact_info || $tender->contact_phone || $tender->contact_email)
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="font-bold text-text mb-3">معلومات الاتصال</h3>
                            <div class="space-y-2 text-sm">
                                @if ($tender->contact_info)
                                    <p class="text-text-secondary">{{ $tender->contact_info }}</p>
                                @endif
                                @if ($tender->contact_phone)
                                    <a href="tel:{{ $tender->contact_phone }}" class="flex items-center gap-2 text-primary hover:underline no-underline">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                        <span>{{ $tender->contact_phone }}</span>
                                    </a>
                                @endif
                                @if ($tender->contact_email)
                                    <a href="mailto:{{ $tender->contact_email }}" class="flex items-center gap-2 text-primary hover:underline no-underline">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                        <span>{{ $tender->contact_email }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Views --}}
                    <div class="text-center text-xs text-text-tertiary">
                        <i data-lucide="eye" class="w-3 h-3 inline"></i>
                        {{ number_format($tender->views_count) }} مشاهدة
                    </div>

                    {{-- Quick Links --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3 text-sm">روابط سريعة</h3>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('public.tenders.index') }}" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-gray-50 hover:text-text transition-colors no-underline">
                                <i data-lucide="scroll-text" class="w-4 h-4"></i>
                                <span>جميع المناقصات</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
