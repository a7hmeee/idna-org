<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'announcements',
        'pageTitle' => $announcement->title,
        'pageSubtitle' => $announcement->short_description ?? null,
        'pageBadge' => 'إعلان',
        'pageBadgeIcon' => 'megaphone',
        'compact' => true,
    ])

    {{-- Info badges below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div class="flex flex-wrap items-center gap-2 py-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold
                @if($announcement->priority->value === 'urgent') bg-danger/10 text-danger
                @elseif($announcement->priority->value === 'important') bg-warning/10 text-warning
                @else bg-info/10 text-info @endif">
                <i data-lucide="{{ $announcement->priority->value === 'urgent' ? 'alert-triangle' : ($announcement->priority->value === 'important' ? 'alert-circle' : 'info') }}" class="w-3 h-3"></i>
                {{ $announcement->priority->label() }}
            </span>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                {{ $announcement->type->label() }}
            </span>
            @if ($announcement->published_at)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                    <i data-lucide="calendar" class="w-3 h-3"></i>
                    {{ $announcement->published_at->format('Y/m/d') }}
                </span>
            @endif
            @if ($announcement->is_featured)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-yellow-50 text-yellow-600">
                    <i data-lucide="star" class="w-3 h-3"></i>
                    مميز
                </span>
            @endif
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-municipal-50 text-text-tertiary">
                <i data-lucide="eye" class="w-3 h-3"></i>
                {{ number_format($announcement->views) }} مشاهدة
            </span>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. ANNOUNCEMENT DETAIL --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Image --}}
                    @if ($announcement->desktop_image_path)
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ asset('storage/' . $announcement->desktop_image_path) }}" alt="{{ $announcement->title }}" class="w-full object-cover" />
                        </div>
                    @endif

                    {{-- Short Description --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-lg font-bold text-text mb-3">نبذة عن الإعلان</h2>
                        <p class="text-text-secondary leading-relaxed">{{ $announcement->short_description }}</p>
                    </div>

                    {{-- Content --}}
                    @if ($announcement->content)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">التفاصيل</h2>
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line">{{ $announcement->content }}</div>
                        </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-4">

                    {{-- Related Announcements --}}
                    @if ($relatedAnnouncements->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <h3 class="text-sm font-bold text-text mb-3">إعلانات مشابهة</h3>
                            <div class="space-y-3">
                                @foreach ($relatedAnnouncements as $related)
                                    <a href="{{ route('public.announcements.show', $related->slug) }}" wire:navigate
                                       class="flex items-start gap-3 no-underline group">
                                        @if ($related->desktop_image_path)
                                            <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0">
                                                <img src="{{ asset('storage/' . $related->desktop_image_path) }}" alt="{{ $related->title }}" class="w-full h-full object-cover" loading="lazy" />
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-semibold text-text group-hover:text-primary transition-colors line-clamp-2">{{ $related->title }}</p>
                                            <p class="text-[10px] text-text-tertiary mt-0.5">{{ $related->published_at?->format('Y/m/d') }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>
</div>
