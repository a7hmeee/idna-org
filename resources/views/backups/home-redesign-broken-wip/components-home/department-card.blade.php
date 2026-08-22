@props([
    'icon' => 'building-2',
    'name' => '',
    'description' => '',
    'url' => '#',
    'coverImage' => null,
    'servicesCount' => 0,
    'managerName' => '',
    'featured' => false,
])

<a href="{{ $url }}" wire:navigate
   class="group bg-white rounded-2xl border border-border overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 no-underline block {{ $featured ? 'bg-primary text-white border-primary' : '' }}">
    <div class="h-1.5 {{ $featured ? 'bg-white/30' : 'bg-primary' }} w-full"></div>
    <div class="p-6">
        <div class="flex items-start gap-4 mb-4">
            @if ($coverImage)
                <img src="{{ $coverImage }}" alt="{{ $name }}" class="w-14 h-14 rounded-xl object-cover flex-shrink-0" loading="lazy">
            @else
                <div class="w-14 h-14 rounded-xl {{ $featured ? 'bg-white/20' : 'bg-primary-light' }} flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-all duration-300">
                    <i data-lucide="{{ $icon }}" class="w-6 h-6 {{ $featured ? 'text-white' : 'text-primary' }} group-hover:text-white transition-colors"></i>
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold {{ $featured ? 'text-white' : 'text-text' }} group-hover:text-primary transition-colors">{{ $name }}</h3>
                    @if ($servicesCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $featured ? 'bg-white/20 text-white' : 'bg-primary-light text-primary' }} flex-shrink-0">
                            {{ $servicesCount }} خدمة
                        </span>
                    @endif
                </div>
                @if ($description)
                    <p class="text-sm {{ $featured ? 'text-white/70' : 'text-text-secondary' }} leading-relaxed line-clamp-2">{{ $description }}</p>
                @endif
            </div>
        </div>
        @if ($managerName)
            <div class="flex items-center gap-2 pt-3 border-t {{ $featured ? 'border-white/20' : 'border-border' }}">
                <div class="w-7 h-7 rounded-full {{ $featured ? 'bg-white/20' : 'bg-primary-light' }} flex items-center justify-center">
                    <i data-lucide="user" class="w-3.5 h-3.5 {{ $featured ? 'text-white' : 'text-primary' }}"></i>
                </div>
                <span class="text-xs {{ $featured ? 'text-white/70' : 'text-text-secondary' }}">
                    <span class="{{ $featured ? 'text-white/50' : 'text-text-muted' }}">مدير القسم:</span> {{ $managerName }}
                </span>
            </div>
        @endif
        <div class="mt-3">
            <span class="text-xs font-semibold {{ $featured ? 'text-white' : 'text-primary' }} group-hover:gap-2 transition-all inline-flex items-center gap-1">
                <span>استكشف القسم</span>
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            </span>
        </div>
    </div>
</a>
