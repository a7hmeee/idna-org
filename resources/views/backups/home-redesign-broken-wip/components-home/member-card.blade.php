@props([
    'fullName' => '',
    'position' => '',
    'positionLabel' => '',
    'photoUrl' => null,
    'url' => '#',
    'committee' => '',
    'isMayor' => false,
])

<a href="{{ $url }}" wire:navigate
   class="group bg-white rounded-2xl border border-border overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 no-underline">
    <div class="aspect-[4/3] bg-gradient-to-br from-primary-light to-surface-secondary relative overflow-hidden">
        @if ($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="w-full h-full object-cover" loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <div class="w-16 h-16 rounded-full bg-white/80 flex items-center justify-center shadow-sm">
                    <i data-lucide="user" class="w-8 h-8 text-primary/40"></i>
                </div>
            </div>
        @endif
    </div>
    <div class="p-4">
        <h3 class="font-bold text-sm text-text group-hover:text-primary transition-colors">{{ $fullName }}</h3>
        <p class="text-xs text-text-secondary mt-0.5">{{ $positionLabel ?: $position }}</p>
        @if ($committee)
            <span class="inline-block mt-2 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-primary-light text-primary">
                {{ $committee }}
            </span>
        @endif
    </div>
</a>
