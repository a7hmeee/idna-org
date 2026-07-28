@props([
    'title' => '',
    'description' => '',
    'icon' => 'laptop',
    'portalUrl' => null,
    'imageUrl' => null,
])

<section class="relative overflow-hidden bg-gradient-to-br from-primary-dark via-primary to-secondary min-h-[400px] lg:min-h-[480px]">
    {{-- Background image --}}
    @if ($imageUrl)
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $imageUrl }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dark/95 via-primary/85 to-secondary/75"></div>
    @endif

    {{-- Decorative elements --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full translate-y-1/2 -translate-x-1/3"></div>
        <svg class="absolute top-10 left-10 w-64 h-64 opacity-20" viewBox="0 0 200 200" fill="white">
            <path d="M100 0 L200 100 L100 200 L0 100 Z"/>
        </svg>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16 sm:pt-32 sm:pb-20 lg:pt-36 lg:pb-24">
        <div class="max-w-3xl mx-auto text-center">
            {{-- Icon --}}
            @if ($icon)
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/15 backdrop-blur-sm border border-white/20 mb-6">
                    <i data-lucide="{{ $icon }}" class="w-8 h-8 text-white"></i>
                </div>
            @endif

            {{-- Title --}}
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white leading-tight mb-4">
                {{ $title }}
            </h1>

            {{-- Description --}}
            @if ($description)
                <p class="text-base sm:text-lg lg:text-xl text-white/85 max-w-2xl mx-auto mb-8 leading-relaxed">
                    {{ $description }}
                </p>
            @endif

            {{-- CTA Button --}}
            @if ($portalUrl)
                <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
                   class="group inline-flex items-center gap-3 px-8 py-4 rounded-xl bg-white text-primary font-bold text-base hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl hover:-translate-y-1">
                    <i data-lucide="external-link" class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                    <span>الدخول إلى بوابة الخدمات</span>
                </a>
            @endif

            {{ $slot ?? '' }}
        </div>
    </div>

    {{-- Wave separator --}}
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#F7F9F8"/>
        </svg>
    </div>
</section>
