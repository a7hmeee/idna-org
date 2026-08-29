<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL (inherited from Jobs) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'jobs',
        'pageTitle' => $job->title,
        'pageSubtitle' => $job->summary ?? null,
        'pageBadge' => 'وظيفة',
        'pageBadgeIcon' => 'briefcase',
        'compact' => true,
    ])

    {{-- Job info badges moved below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            @if ($job->department)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="building-2" style="width:12px;height:12px;"></i>
                    <span>{{ $job->department->name }}</span>
                </span>
            @endif
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="clock" style="width:12px;height:12px;"></i>
                <span>آخر موعد: {{ $job->closing_at->format('Y/m/d') }}</span>
            </span>
            @if ($job->is_featured)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مميزة</span>
                </span>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. JOB DETAIL --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Summary --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-text mb-3">نبذة عن الوظيفة</h2>
                        <p class="text-text-secondary leading-relaxed">{{ $job->summary }}</p>
                    </div>

                    {{-- Description --}}
                    @if ($job->description)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">الوصف</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line">{{ $job->description }}</div>
                        </div>
                    @endif

                    {{-- Requirements --}}
                    @if ($job->requirements)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">المتطلبات</h2>
                            <ul class="space-y-2">
                                @foreach ($job->requirements as $req)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="check-circle" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $req }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Responsibilities --}}
                    @if ($job->responsibilities)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">المهام الوظيفية</h2>
                            <ul class="space-y-2">
                                @foreach ($job->responsibilities as $resp)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="arrow-left-circle" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $resp }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Required Documents --}}
                    @if ($job->required_documents)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">المستندات المطلوبة</h2>
                            <ul class="space-y-2">
                                @foreach ($job->required_documents as $doc)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="file-text" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $doc }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Benefits --}}
                    @if ($job->benefits)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">المزايا</h2>
                            <ul class="space-y-2">
                                @foreach ($job->benefits as $benefit)
                                    <li class="flex items-start gap-2 text-text-secondary">
                                        <i data-lucide="gift" class="w-4 h-4 text-primary mt-0.5 shrink-0"></i>
                                        <span>{{ $benefit }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Related Jobs --}}
                    @if ($relatedJobs->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">وظائف أخرى</h2>
                            <div class="space-y-3">
                                @foreach ($relatedJobs as $related)
                                    <a href="{{ route('public.jobs.show', $related->slug) }}" wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors no-underline group">
                                        <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                            <i data-lucide="briefcase" class="w-5 h-5 text-primary"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors">{{ $related->title }}</h3>
                                            <p class="text-xs text-text-tertiary">{{ $related->employment_type->label() }} · {{ $related->location }}</p>
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
                        <h3 class="font-bold text-text mb-3">معلومات الوظيفة</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">الدائرة</span>
                                <span class="text-text font-semibold">{{ $job->department?->name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">نوع الوظيفة</span>
                                <span class="text-text font-semibold">{{ $job->employment_type->label() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">الموقع</span>
                                <span class="text-text font-semibold">{{ $job->location }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">عدد الشواغر</span>
                                <span class="text-text font-semibold">{{ $job->vacancies }}</span>
                            </div>
                            @if ($job->salary)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">الراتب</span>
                                    <span class="text-text font-semibold">{{ $job->salary }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">تاريخ النشر</span>
                                <span class="text-text font-semibold">{{ $job->publish_at->format('Y/m/d') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">آخر موعد</span>
                                <span class="text-text font-semibold">{{ $job->closing_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Download Attachment --}}
                    @if ($job->attachment_url)
                        <a href="{{ $job->attachment_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-primary/10 text-primary text-sm font-semibold hover:bg-primary/20 transition-colors">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>تحميل إعلان الوظيفة</span>
                        </a>
                    @endif

                    {{-- Apply Button --}}
                    @if ($job->application_method->value === 'external_link' && $job->application_url)
                        <a href="{{ $job->application_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="external-link" class="w-4 h-4"></i>
                            <span>تقديم على الوظيفة</span>
                        </a>
                    @elseif ($job->application_method->value === 'email' && $job->application_email)
                        <a href="mailto:{{ $job->application_email }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span>إرسال عبر البريد: {{ $job->application_email }}</span>
                        </a>
                    @elseif ($job->application_method->value === 'phone' && $job->application_phone)
                        <a href="tel:{{ $job->application_phone }}" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span>الاتصال: {{ $job->application_phone }}</span>
                        </a>
                    @elseif ($job->application_method->value === 'office')
                        <div class="w-full px-4 py-4 rounded-xl bg-blue-50 border border-blue-200 text-sm text-blue-700 font-medium text-center">
                            <i data-lucide="building-2" class="w-4 h-4 inline mb-1"></i>
                            <p>يرجى مراجعة مبنى بلدية إذنا لتقديم الطلب</p>
                        </div>
                    @elseif ($job->application_method->value === 'download_form' && $job->attachment_url)
                        <a href="{{ $job->attachment_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition-colors">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span>تحميل استمارة التقديم</span>
                        </a>
                    @endif

                    {{-- Views --}}
                    <div class="text-center text-xs text-text-tertiary">
                        <i data-lucide="eye" class="w-3 h-3 inline"></i>
                        {{ number_format($job->views_count) }} مشاهدة
                    </div>

                    {{-- Quick Links --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3 text-sm">روابط سريعة</h3>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('public.jobs.index') }}" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-gray-50 hover:text-text transition-colors no-underline">
                                <i data-lucide="briefcase" class="w-4 h-4"></i>
                                <span>جميع الوظائف</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
