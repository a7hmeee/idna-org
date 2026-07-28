<div>

    {{-- ============================================ --}}
    {{-- 1. PAGE CAROUSEL --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'news',
        'pageTitle' => $news->title_ar,
        'pageSubtitle' => $news->summary ?? null,
        'pageBadge' => 'خبر',
        'pageBadgeIcon' => 'newspaper',
        'compact' => true,
    ])

    {{-- Info badges below carousel --}}
    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8" style="margin-top:-8px;position:relative;z-index:15;">
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:8px;padding:12px 0;">
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="tag" style="width:12px;height:12px;"></i>
                <span>{{ $news->category->label() }}</span>
            </span>
            <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                <i data-lucide="calendar" style="width:12px;height:12px;"></i>
                <span>{{ $news->publish_at?->format('Y/m/d') }}</span>
            </span>
            @if ($news->author)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(46,125,50,0.9);color:rgba(255,255,255,0.9);">
                    <i data-lucide="user" style="width:12px;height:12px;"></i>
                    <span>{{ $news->author }}</span>
                </span>
            @endif
            @if ($news->is_featured)
                <span style="display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;background:rgba(217,119,6,0.2);color:#FCD34D;">
                    <i data-lucide="star" style="width:12px;height:12px;"></i>
                    <span>مميز</span>
                </span>
            @endif
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- 2. NEWS DETAIL --}}
    {{-- ============================================ --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-10">

                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Cover Image --}}
                    @if ($news->cover_image_url)
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title_ar }}" class="w-full object-cover" loading="lazy" />
                        </div>
                    @endif

                    {{-- Summary --}}
                    @if ($news->summary)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-3">نبذة</h2>
                            <p class="text-text-secondary leading-relaxed">{{ $news->summary }}</p>
                        </div>
                    @endif

                    {{-- Content --}}
                    @if ($news->content)
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <div class="text-text-secondary leading-relaxed whitespace-pre-line prose prose-sm max-w-none">
                                {{ $news->content }}
                            </div>
                        </div>
                    @endif

                    {{-- Share Buttons --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="font-bold text-text mb-4 text-sm">مشاركة الخبر</h3>
                        <div class="flex items-center gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors">
                                <i data-lucide="facebook" class="w-5 h-5"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($news->title_ar) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center hover:bg-sky-200 transition-colors">
                                <i data-lucide="twitter" class="w-5 h-5"></i>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($news->title_ar . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer"
                               class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center hover:bg-green-200 transition-colors">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                            </a>
                            <button onclick="copyToClipboard('{{ url()->current() }}')"
                                    class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center hover:bg-gray-200 transition-colors">
                                <i data-lucide="link" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>

                    <script>
                        function copyToClipboard(text) {
                            navigator.clipboard.writeText(text).then(function() {
                                alert('تم نسخ الرابط');
                            });
                        }
                    </script>

                    {{-- Related News --}}
                    @if ($relatedNews->isNotEmpty())
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h2 class="text-lg font-bold text-text mb-4">أخبار أخرى</h2>
                            <div class="space-y-3">
                                @foreach ($relatedNews as $related)
                                    <a href="{{ route('public.news.show', $related->slug) }}" wire:navigate
                                       class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors no-underline group">
                                        @if ($related->cover_image_url)
                                            <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0">
                                                <img src="{{ $related->cover_image_url }}" alt="{{ $related->title_ar }}" class="w-full h-full object-cover" loading="lazy" />
                                            </div>
                                        @else
                                            <div class="w-14 h-14 rounded-lg bg-primary-light flex items-center justify-center flex-shrink-0">
                                                <i data-lucide="newspaper" class="w-6 h-6 text-primary"></i>
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors line-clamp-2">{{ $related->title_ar }}</h3>
                                            <p class="text-xs text-text-tertiary">{{ $related->category->label() }} · {{ $related->publish_at?->format('Y/m/d') }}</p>
                                        </div>
                                        <i data-lucide="chevron-left" class="w-4 h-4 text-gray-300 group-hover:text-primary transition-colors shrink-0"></i>
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
                        <h3 class="font-bold text-text mb-3">معلومات الخبر</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">التصنيف</span>
                                <span class="text-text font-semibold">{{ $news->category->label() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">تاريخ النشر</span>
                                <span class="text-text font-semibold">{{ $news->publish_at?->format('Y/m/d') }}</span>
                            </div>
                            @if ($news->author)
                                <div class="flex items-center justify-between">
                                    <span class="text-text-tertiary">المؤلف</span>
                                    <span class="text-text font-semibold">{{ $news->author }}</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-text-tertiary">المشاهدات</span>
                                <span class="text-text font-semibold">{{ number_format($news->views_count) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Image Gallery --}}
                    @if ($news->cover_image_url)
                        <div class="rounded-xl overflow-hidden border border-gray-200">
                            <img src="{{ $news->cover_image_url }}" alt="{{ $news->title_ar }}" class="w-full object-cover" loading="lazy" />
                        </div>
                    @endif

                    {{-- Quick Links --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="font-bold text-text mb-3 text-sm">روابط سريعة</h3>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('public.news.index') }}" wire:navigate
                               class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm font-medium text-text-secondary hover:bg-gray-50 hover:text-text transition-colors no-underline">
                                <i data-lucide="newspaper" class="w-4 h-4"></i>
                                <span>جميع الأخبار</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
