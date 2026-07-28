<div>
    <x-slot name="title">{{ $service->name }}</x-slot>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">{{ $service->name }}</h1>
            <p class="text-sm text-text-tertiary mt-1">{{ $service->summary }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard.electronic-services.services') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-surface border border-border text-text-secondary text-sm font-semibold hover:bg-surface-secondary transition-colors">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                العودة
            </a>
            @if ($canUpdate)
            <a href="{{ route('dashboard.electronic-services.services.edit', $service) }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                تعديل
            </a>
            @endif
            @if ($canPublish && $service->status !== 'active')
            <button wire:click="publish" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-success text-white text-sm font-semibold hover:bg-success/90 transition-colors">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                نشر
            </button>
            @endif
            @if ($canPublish && $service->status === 'active')
            <button wire:click="archive" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-warning text-white text-sm font-semibold hover:bg-warning/90 transition-colors">
                <i data-lucide="archive" class="w-4 h-4"></i>
                أرشفة
            </button>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl bg-success-light border border-success/20 px-4 py-3 flex items-start gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-success mt-0.5 shrink-0"></i>
            <span class="text-sm text-success font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="info" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">معلومات الخدمة</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-text-tertiary">التصنيف</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $service->category?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">الدائرة المسؤولة</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $service->department?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">مدة الإنجاز</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $service->processing_time ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-text-tertiary">تاريخ النشر</p>
                        <p class="text-sm font-semibold text-text mt-1">{{ $service->published_at?->format('Y-m-d') ?? 'غير منشور' }}</p>
                    </div>
                </div>
                @if ($service->description)
                <div class="mt-4">
                    <p class="text-xs text-text-tertiary">الوصف</p>
                    <p class="text-sm text-text mt-1">{{ $service->description }}</p>
                </div>
                @endif
                @if ($service->eligibility)
                <div class="mt-4">
                    <p class="text-xs text-text-tertiary">شروط التقديم</p>
                    <p class="text-sm text-text mt-1">{{ $service->eligibility }}</p>
                </div>
                @endif
                @if ($service->portal_url)
                <div class="mt-4">
                    <p class="text-xs text-text-tertiary">رابط البوابة الخارجية</p>
                    <a href="{{ $service->portal_url }}" target="_blank" class="text-sm text-primary hover:underline mt-1 inline-flex items-center gap-1" dir="ltr">
                        {{ $service->portal_url }}
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
                @endif
            </div>

            @if (!empty($service->requirements))
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="list-checks" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">المتطلبات</h2>
                </div>
                <ul class="space-y-3">
                    @foreach ($service->requirements as $req)
                    @php $reqTitle = is_array($req) ? ($req['title'] ?? '') : $req; @endphp
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                        <i data-lucide="{{ is_array($req) && !($req['is_required'] ?? true) ? 'circle' : 'circle-check-big' }}" class="w-4 h-4 mt-0.5 {{ is_array($req) && !($req['is_required'] ?? true) ? 'text-text-tertiary' : 'text-primary' }}"></i>
                        <div>
                            <p class="text-sm font-semibold text-text">{{ $reqTitle }}</p>
                            @if (is_array($req) && !empty($req['description']))
                            <p class="text-xs text-text-tertiary mt-0.5">{{ $req['description'] }}</p>
                            @endif
                            @if (!is_array($req) || ($req['is_required'] ?? true))
                            <span class="text-[10px] font-semibold text-danger">مطلوب</span>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (!empty($service->documents))
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="file-text" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الوثائق المطلوبة</h2>
                </div>
                <ul class="space-y-3">
                    @foreach ($service->documents as $doc)
                    @php $docName = is_array($doc) ? ($doc['name'] ?? '') : $doc; @endphp
                    <li class="flex items-start gap-3 p-3 rounded-xl bg-surface-secondary">
                        <i data-lucide="file" class="w-4 h-4 mt-0.5 text-primary"></i>
                        <div>
                            <p class="text-sm font-semibold text-text">{{ $docName }}</p>
                            @if (is_array($doc) && !empty($doc['description']))
                            <p class="text-xs text-text-tertiary mt-0.5">{{ $doc['description'] }}</p>
                            @endif
                            @if (!is_array($doc) || ($doc['is_required'] ?? true))
                            <span class="text-[10px] font-semibold text-danger">مطلوب</span>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if (!empty($service->steps))
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="list-ordered" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">خطوات التقديم</h2>
                </div>
                <div class="space-y-0">
                    @foreach ($service->steps as $index => $step)
                    @php $stepTitle = is_array($step) ? ($step['title'] ?? '') : $step; @endphp
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold shadow-sm shrink-0">
                                {{ $index + 1 }}
                            </div>
                            @if (!$loop->last)
                            <div class="w-px flex-1 bg-primary/20 my-1"></div>
                            @endif
                        </div>
                        <div class="pb-8 pt-1.5 flex-1">
                            <p class="text-sm font-bold text-text leading-relaxed">{{ $stepTitle }}</p>
                            @if (is_array($step) && !empty($step['description']))
                            <p class="text-xs text-text-tertiary mt-1.5 leading-relaxed">{{ $step['description'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (!empty($service->fees))
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="wallet" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الرسوم</h2>
                </div>
                <div class="space-y-3">
                    @foreach ($service->fees as $fee)
                    @php
                        $feeTitle = is_array($fee) ? ($fee['title'] ?? '') : $fee;
                        $feeAmount = is_array($fee) ? (float) ($fee['amount'] ?? 0) : 0;
                        $feeCurrency = is_array($fee) ? ($fee['currency'] ?? 'ILS') : '';
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-surface-secondary">
                        <div>
                            <p class="text-sm font-semibold text-text">{{ $feeTitle }}</p>
                            @if (is_array($fee) && !empty($fee['notes']))
                            <p class="text-xs text-text-tertiary">{{ $fee['notes'] }}</p>
                            @endif
                        </div>
                        @if (is_array($fee) && isset($fee['amount']))
                        <p class="text-sm font-bold text-primary">{{ number_format($feeAmount, 2) }} {{ $feeCurrency }}</p>
                        @elseif (!is_array($fee))
                        <span class="text-xs font-bold text-text-tertiary">{{ $feeTitle }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="settings" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الحالة</h2>
                </div>
                @php
                    $statusColors = [
                        'active' => 'bg-success-light text-success',
                        'draft' => 'bg-surface-secondary text-text-muted',
                        'inactive' => 'bg-warning-light text-warning',
                        'archived' => 'bg-danger-light text-danger',
                    ];
                    $statusLabels = [
                        'active' => 'نشط',
                        'draft' => 'مسودة',
                        'inactive' => 'غير نشط',
                        'archived' => 'مؤرشف',
                    ];
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold {{ $statusColors[$service->status] ?? 'bg-surface-secondary text-text-muted' }}">
                    {{ $statusLabels[$service->status] ?? $service->status }}
                </span>
                @if ($service->is_featured)
                <p class="text-xs text-info mt-2 flex items-center gap-1">
                    <i data-lucide="star" class="w-3 h-3"></i>
                    خدمة مميزة
                </p>
                @endif
            </div>

            <div class="bg-surface rounded-xl border border-border p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary"></i>
                    </div>
                    <h2 class="text-sm font-bold text-text">الإحصائيات</h2>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-xs text-text-tertiary">المشاهدات</span>
                        <span class="text-sm font-bold text-text">{{ number_format($service->views_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-border/50">
                        <span class="text-xs text-text-tertiary">نقرات البوابة</span>
                        <span class="text-sm font-bold text-text">{{ number_format($service->portal_clicks_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-xs text-text-tertiary">نسبة التحويل</span>
                        <span class="text-sm font-bold text-{{ $conversionRate > 10 ? 'success' : ($conversionRate > 0 ? 'warning' : 'text-text-muted') }}">{{ $conversionRate }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
