@if (!empty($partnerLogos))
    <section data-reveal class="section-py bg-white">
        <div class="container-home">
            <x-home.section-head
                eyebrow="شركاؤنا"
                eyebrowIcon="handshake"
                title="شركاؤنا في العمل"
                subtitle="نفخر بشراكتنا مع هذه المؤسسات والجهات"
            />

            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-5">
                @foreach ($partnerLogos as $logo)
                    <div class="aspect-square bg-surface-secondary rounded-xl border border-border flex items-center justify-center p-4 sm:p-5 grayscale hover:grayscale-0 transition-all duration-300 hover:shadow-md hover:border-primary/20 group">
                        @if (!empty($logo['url']))
                            <img src="{{ $logo['url'] }}"
                                 alt="{{ $logo['alt'] ?? $logo['title'] ?? 'شريك' }}"
                                 title="{{ $logo['title'] ?? '' }}"
                                 class="w-full h-full object-contain"
                                 loading="lazy" decoding="async">
                        @else
                            <i data-lucide="building-2" class="w-8 h-8" style="color:#66756D/40;"></i>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
