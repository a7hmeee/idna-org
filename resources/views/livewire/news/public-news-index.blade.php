<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'news',
        'fallbackTitle' => "الأخبار",
        'fallbackDescription' => "استعرض جميع الأخبار والفعاليات في بلدية إذنا، واطلع على آخر المستجدات.",
        'fallbackBadge' => 'الأخبار',
        'fallbackIcon' => 'newspaper',
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. NEWS LIST --}}
    {{-- ============================================ --}}
    <section id="news-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filters --}}
            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        <button wire:click="$set('filter', 'latest')"
                                aria-pressed="{{ $filter == 'latest' }}"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $filter == 'latest' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $filter == 'latest' ? '#0F6A3D' : 'white' }};color:{{ $filter == 'latest' ? 'white' : '#6B7280' }};">
                            الأحدث
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
                        <input type="text" wire:model.live.debounce.400ms="search"
                               aria-label="ابحث عن خبر"
                               placeholder="ابحث عن خبر..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>

                {{-- Category Tabs --}}
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:6px;">
                    <button wire:click="$set('category', '')"
                            aria-pressed="{{ $category == '' }}"
                            style="padding:6px 16px;border-radius:20px;font-size:12px;font-weight:500;cursor:pointer;transition:all 0.2s;border:1px solid {{ $category == '' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $category == '' ? '#0F6A3D' : 'white' }};color:{{ $category == '' ? 'white' : '#6B7280' }};">
                        الكل
                    </button>
                    @foreach ($categories as $cat)
                        <button wire:click="$set('category', '{{ $cat->value }}')"
                                aria-pressed="{{ $category == $cat->value }}"
                                style="padding:6px 16px;border-radius:20px;font-size:12px;font-weight:500;cursor:pointer;transition:all 0.2s;border:1px solid {{ $category == $cat->value ? '#0F6A3D' : '#E5E7EB' }};background:{{ $category == $cat->value ? '#0F6A3D' : 'white' }};color:{{ $category == $cat->value ? 'white' : '#6B7280' }};">
                            {{ $cat->label() }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Results Count --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $news->total() ?? 0 }}</span> خبر
                </p>
                @if ($search || $category || $filter !== 'latest')
                    <button wire:click="clearFilters" style="font-size:12px;color:#0F6A3D;font-weight:600;cursor:pointer;background:none;border:none;padding:4px 8px;">
                        <i data-lucide="x" style="width:14px;height:14px;display:inline;"></i>
                        مسح التصفية
                    </button>
                @endif
            </div>

            {{-- Featured Row --}}
            @if ($featured->isNotEmpty() && $filter !== 'featured')
                <div style="margin-bottom:28px;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                        <i data-lucide="star" style="width:16px;height:16px;color:#D97706;"></i>
                        <h2 style="font-size:15px;font-weight:700;color:#1F2937;margin:0;">أخبار مميزة</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($featured as $item)
                            <a href="{{ route('public.news.show', $item->slug) }}" wire:navigate
                               class="block bg-white rounded-2xl border-2 border-yellow-100 p-5 transition-all duration-200"
                               style="text-decoration:none;box-shadow:0 1px 3px rgba(0,0,0,0.03);"
                               onmouseover="this.style.borderColor='#FCD34D';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)';this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.borderColor='#FEF3C7';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.03)';this.style.transform='translateY(0)'">
                                <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:10px;">
                                    <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i data-lucide="newspaper" style="width:20px;height:20px;color:#0F6A3D;"></i>
                                    </div>
                                    <div style="min-width:0;flex:1;">
                                        <h3 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 2px;">{{ $item->title_ar }}</h3>
                                        <span style="font-size:11px;color:#9CA3AF;">{{ $item->category?->label() }}</span>
                                    </div>
                                </div>
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $item->summary }}</p>
                                <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#9CA3AF;">
                                    <i data-lucide="calendar" style="width:11px;height:11px;"></i>
                                    <span>{{ $item->publish_at?->format('Y/m/d') }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- News Grid --}}
            @if ($news->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="newspaper" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد أخبار حالياً</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصفية</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($news as $item)
                        <a href="{{ route('public.news.show', $item->slug) }}" class="block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all" wire:navigate>
                            @if ($item->cover_image_url)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ $item->cover_image_url }}" alt="{{ $item->title_ar }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" loading="lazy" />
                                </div>
                            @else
                                <div class="aspect-video bg-gray-100 flex items-center justify-center">
                                    <i data-lucide="newspaper" class="w-10 h-10 text-gray-300"></i>
                                </div>
                            @endif
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-semibold">{{ $item->category?->label() }}</span>
                                    @if ($item->is_featured)
                                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold">مميز</span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-text text-sm leading-relaxed line-clamp-2">{{ $item->title_ar }}</h3>
                                @if ($item->summary)
                                    <p class="text-xs text-text-secondary mt-2 line-clamp-2">{{ $item->summary }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-3 text-xs text-text-tertiary">
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        {{ $item->publish_at?->format('Y/m/d') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        {{ number_format($item->views_count) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Pagination --}}
            @if ($news->hasPages())
                <div class="mt-10">
                    <x-ui.pagination :paginator="$news" />
                </div>
            @endif
        </div>
    </section>

</div>
