@props([
    'fullName' => '',
    'position' => '',
    'positionLabel' => '',
    'photoUrl' => null,
    'url' => '#',
    'bio' => '',
    'qualification' => '',
    'committee' => '',
])

<a href="{{ $url }}" wire:navigate
   class="group block bg-white rounded-2xl border border-primary overflow-hidden hover:shadow-xl transition-all duration-300 no-underline">
    <div class="p-6 sm:p-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-20 h-20 rounded-2xl bg-primary-light flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if ($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="w-full h-full object-cover" loading="lazy">
                @else
                    <i data-lucide="user" class="w-10 h-10 text-primary/40"></i>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-accent/15 text-accent mb-1.5">
                    <i data-lucide="crown" class="w-3 h-3"></i>
                    رئيس المجلس البلدي
                </span>
                <h3 class="text-lg sm:text-xl font-black text-text">{{ $fullName }}</h3>
                <p class="text-sm text-text-secondary">{{ $positionLabel ?: $position }}</p>
            </div>
        </div>
        @if ($bio)
            <p class="text-sm text-text-secondary leading-relaxed line-clamp-3 mb-4">{{ $bio }}</p>
        @endif
        <div class="flex flex-wrap items-center gap-3">
            @if ($qualification)
                <span class="text-xs text-text-secondary flex items-center gap-1.5">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-primary"></i>
                    {{ $qualification }}
                </span>
            @endif
            @if ($committee)
                <span class="text-xs text-text-secondary flex items-center gap-1.5">
                    <i data-lucide="layers" class="w-3.5 h-3.5 text-primary"></i>
                    {{ $committee }}
                </span>
            @endif
            <span class="text-xs font-semibold text-primary group-hover:gap-2 transition-all inline-flex items-center gap-1 mr-auto">
                <span>عرض الملف الكامل</span>
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            </span>
        </div>
    </div>
</a>
