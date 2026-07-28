<div>

    {{-- ============================================ --}}
    {{-- 1. HERO SECTION (Page Carousel) --}}
    {{-- ============================================ --}}
    @livewire('public-page-carousel', [
        'pageKey' => 'announcements',
        'fallbackTitle' => 'الإعلانات',
        'fallbackDescription' => 'تصفح جميع الإعلانات الرسمية الصادرة عن بلدية إذنا، واطلع على آخر المستجدات والتنبيهات.',
        'fallbackBadge' => 'الإعلانات',
        'fallbackIcon' => 'megaphone',
        'compact' => false,
    ])

    {{-- ============================================ --}}
    {{-- 2. ANNOUNCEMENTS LIST --}}
    {{-- ============================================ --}}
    <section id="announcements-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Search + Filters --}}
            <div class="flex flex-col gap-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <button wire:click="$set('sort', 'latest')"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 border"
                                style="border-color:{{ $sort == 'latest' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $sort == 'latest' ? '#0F6A3D' : 'white' }};color:{{ $sort == 'latest' ? 'white' : '#6B7280' }};">
                            الأحدث
                        </button>
                        <button wire:click="$set('sort', 'oldest')"
                                class="px-4 py-1.5 rounded-full text-xs font-semibold cursor-pointer transition-all duration-200 border"
                                style="border-color:{{ $sort == 'oldest' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $sort == 'oldest' ? '#0F6A3D' : 'white' }};color:{{ $sort == 'oldest' ? 'white' : '#6B7280' }};">
                            الأقدم
                        </button>
                    </div>
                    <div class="relative w-full max-w-xs">
                        <i data-lucide="search" class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-tertiary pointer-events-none"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث في الإعلانات..."
                               class="w-full bg-surface-secondary border border-border rounded-lg py-2.5 pr-10 pl-4 text-sm text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all" />
                    </div>
                </div>

                {{-- Filter Pills --}}
                <div class="flex flex-wrap items-center gap-2">
                    <select wire:model.live="type" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-xs text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع الأنواع</option>
                        @foreach ($types as $t)
                            <option value="{{ $t->value }}">{{ $t->label() }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="priority" class="bg-surface-secondary border border-border rounded-lg px-3 py-1.5 text-xs text-text focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none transition-all">
                        <option value="">جميع الأولويات</option>
                        @foreach ($priorities as $p)
                            <option value="{{ $p->value }}">{{ $p->label() }}</option>
                        @endforeach
                    </select>
                    @if ($search || $type || $priority)
                        <button wire:click="clearFilters" class="text-xs text-danger font-semibold hover:underline px-2">مسح الكل</button>
                    @endif
                </div>
            </div>

            {{-- Results Count --}}
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-text-tertiary">
                    يوجد <span class="font-bold text-text">{{ $announcements->total() ?? 0 }}</span> إعلان
                </p>
            </div>

            {{-- Featured Row --}}
            @if ($featured->isNotEmpty())
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <i data-lucide="star" class="w-4 h-4 text-yellow-500"></i>
                        <h2 class="text-sm font-bold text-text">إعلانات مميزة</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($featured as $announcement)
                            <a href="{{ route('public.announcements.show', $announcement->slug) }}" wire:navigate
                               class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 no-underline">
                                @if ($announcement->desktop_image_path)
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ asset('storage/' . $announcement->desktop_image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                    </div>
                                @endif
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded
                                            @if($announcement->priority->value === 'urgent') bg-danger/10 text-danger
                                            @elseif($announcement->priority->value === 'important') bg-warning/10 text-warning
                                            @else bg-info/10 text-info @endif">
                                            {{ $announcement->priority->label() }}
                                        </span>
                                        <span class="text-[10px] text-text-tertiary">{{ $announcement->type->label() }}</span>
                                    </div>
                                    <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors leading-snug">{{ $announcement->title }}</h3>
                                    <p class="text-xs text-text-tertiary mt-1 line-clamp-2">{{ $announcement->short_description }}</p>
                                    <p class="text-[10px] text-text-tertiary mt-2">{{ $announcement->published_at?->format('Y/m/d') }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Main List --}}
            @if ($announcements->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($announcements as $announcement)
                        <a href="{{ route('public.announcements.show', $announcement->slug) }}" wire:navigate
                           class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 no-underline">
                            @if ($announcement->desktop_image_path)
                                <div class="aspect-video overflow-hidden">
                                    <img src="{{ asset('storage/' . $announcement->desktop_image_path) }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" />
                                </div>
                            @endif
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded
                                        @if($announcement->priority->value === 'urgent') bg-danger/10 text-danger
                                        @elseif($announcement->priority->value === 'important') bg-warning/10 text-warning
                                        @else bg-info/10 text-info @endif">
                                        {{ $announcement->priority->label() }}
                                    </span>
                                    <span class="text-[10px] text-text-tertiary">{{ $announcement->type->label() }}</span>
                                </div>
                                <h3 class="text-sm font-bold text-text group-hover:text-primary transition-colors leading-snug">{{ $announcement->title }}</h3>
                                <p class="text-xs text-text-tertiary mt-1 line-clamp-2">{{ $announcement->short_description }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-[10px] text-text-tertiary">{{ $announcement->published_at?->format('Y/m/d') }}</p>
                                    <span class="text-[10px] text-text-tertiary flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                        {{ number_format($announcement->views) }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if ($announcements->hasPages())
                    <div class="mt-8">
                        {{ $announcements->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <i data-lucide="megaphone" class="w-12 h-12 text-text-tertiary mx-auto mb-4"></i>
                    <p class="text-text-tertiary">لا توجد إعلانات حالياً</p>
                </div>
            @endif
        </div>
    </section>
</div>
