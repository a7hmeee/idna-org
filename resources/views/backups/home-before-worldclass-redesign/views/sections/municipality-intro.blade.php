@php
    $municipality = $municipality ?? null;
    $municipalityName = $municipalityName ?? '';
    $sectionTitle = $sectionTitle ?? '';
    $sectionSubtitle = $sectionSubtitle ?? '';

    $title = $sectionTitle ?: 'نبذة عن البلدية';
    $subtitle = $sectionSubtitle ?: '';

    $vision = $municipality['vision'] ?? null;
    $mission = $municipality['mission'] ?? null;
    $values = $municipality['values'] ?? null;
    $fullDesc = $municipality['full_description'] ?? $municipality['short_description'] ?? null;
    $images = $municipality['images'] ?? [];
    $imageUrl = !empty($images) ? $images[0]['url'] : ($municipality['logo_url'] ?? null);
    $imageAlt = !empty($images) ? ($images[0]['alt'] ?? $municipalityName) : $municipalityName;
@endphp

@if ($municipality && ($fullDesc || $vision || $mission))
    <section id="municipality-intro" class="py-16 sm:py-20 lg:py-24 bg-white">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                {{-- Right: Content --}}
                <div>
                    <span class="section-title-line mb-3"></span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-text mt-2 mb-4">{{ $title }}</h2>
                    @if ($subtitle)
                        <p class="text-text-secondary text-sm sm:text-base mb-2">{{ $subtitle }}</p>
                    @endif

                    @if ($fullDesc)
                        <p class="text-text-secondary leading-relaxed text-sm sm:text-base mb-6">{{ $fullDesc }}</p>
                    @endif

                    {{-- Vision, Mission, Values Cards --}}
                    <div class="grid sm:grid-cols-2 gap-3 sm:gap-4 mb-6">
                        @if ($vision)
                            <div class="bg-primary-light/50 rounded-xl p-4 sm:p-5 border border-primary/10">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                        <i data-lucide="eye" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <h4 class="font-bold text-text text-sm">رؤيتنا</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-text-secondary leading-relaxed">{{ is_array($vision) ? implode(' ', $vision) : $vision }}</p>
                            </div>
                        @endif
                        @if ($mission)
                            <div class="bg-primary-light/50 rounded-xl p-4 sm:p-5 border border-primary/10">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                        <i data-lucide="target" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <h4 class="font-bold text-text text-sm">رسالتنا</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-text-secondary leading-relaxed">{{ is_array($mission) ? implode(' ', $mission) : $mission }}</p>
                            </div>
                        @endif
                        @if ($values)
                            <div class="sm:col-span-2 bg-primary-light/50 rounded-xl p-4 sm:p-5 border border-primary/10">
                                <div class="flex items-center gap-2.5 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0">
                                        <i data-lucide="star" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <h4 class="font-bold text-text text-sm">قيمنا</h4>
                                </div>
                                <p class="text-xs sm:text-sm text-text-secondary leading-relaxed">{{ is_array($values) ? implode(' — ', $values) : $values }}</p>
                            </div>
                        @endif
                    </div>

                    <a href="#municipality-intro" class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-dark transition-colors group">
                        اقرأ المزيد عن البلدية
                        <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                </div>

                {{-- Left: Image --}}
                <div class="relative">
                    <div class="rounded-2xl overflow-hidden shadow-lg border border-border/50" style="aspect-ratio: 4/3;">
                        @if ($imageUrl)
                            <img src="{{ $imageUrl }}"
                                 alt="{{ $imageAlt }}"
                                 class="w-full h-full object-cover"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-light to-primary/5 flex items-center justify-center">
                                <div class="text-center">
                                    <i data-lucide="building-2" class="w-20 h-20 text-primary/30 mx-auto mb-3"></i>
                                    <p class="text-text-secondary text-sm font-medium">{{ $municipalityName }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    {{-- Decorative badge --}}
                    <div class="absolute -bottom-3 -left-3 sm:-bottom-4 sm:-left-4 bg-primary rounded-xl px-4 py-2.5 shadow-lg flex items-center gap-2.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                        <span class="text-white text-xs font-bold">{{ $municipality['foundation_date'] ? 'تأسست ' . \Carbon\Carbon::parse($municipality['foundation_date'])->format('Y') : 'بلدية إذنا' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
