@props([
    'portalUrl' => null,
    'title' => 'ابدأ معاملتك إلكترونياً',
    'description' => 'وفر وقتك وجهدك، قدم طلبك إلكترونياً عبر بوابة الخدمات الرسمية وتابع معاملتك من أي مكان وفي أي وقت.',
    'buttonText' => 'الدخول إلى بوابة الخدمات',
])

@if ($portalUrl)
    <section class="relative overflow-hidden py-14 sm:py-20 lg:py-24">
        <div class="absolute inset-0" style="background:linear-gradient(135deg,#1B5E20,#154E21,#111827);"></div>
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 flex items-center justify-center mx-auto mb-4 sm:mb-6 backdrop-blur-sm border border-white/10">
                <i data-lucide="mouse-pointer-click" class="w-7 h-7 sm:w-8 sm:h-8 text-white"></i>
            </div>
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-3 sm:mb-4">
                {{ $title }}
            </h2>
            <p class="text-sm sm:text-base lg:text-lg text-white/70 max-w-xl mx-auto mb-6 sm:mb-8 leading-relaxed">
                {{ $description }}
            </p>
            <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer"
               class="group inline-flex items-center gap-2.5 px-6 py-3 sm:px-8 sm:py-4 rounded-xl sm:rounded-2xl bg-white text-primary font-bold text-sm hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <span>{{ $buttonText }}</span>
                <i data-lucide="external-link" class="w-4 h-4 transition-transform duration-300 group-hover:-translate-x-1"></i>
            </a>
        </div>
    </section>
@endif
