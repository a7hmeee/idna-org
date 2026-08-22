@if (!empty($partnerLogos))
    <section class="section-py bg-white">
        <div class="container-home">
            <div class="text-center mb-10">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold mb-3" style="background:rgba(15,106,61,0.08);color:#0F6A3D;">
                    <i data-lucide="handshake" class="w-3.5 h-3.5"></i>
                    شركاؤنا
                </span>
                <h2 class="text-3xl lg:text-[34px] font-black text-text">شركاؤنا في العمل</h2>
                <p class="text-text-secondary mt-2 text-sm">نفخر بشراكتنا مع هذه المؤسسات والجهات</p>
            </div>

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
