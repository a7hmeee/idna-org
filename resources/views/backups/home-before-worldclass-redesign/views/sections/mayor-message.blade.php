@props([
    'settings' => [],
    'municipality' => null,
    'municipalityName' => '',
    'featuredCouncilMembers' => [],
    'mayor' => null,
])

@php
    $showMayor = $settings['show_mayor_message'] ?? false;
    $mayorTitle = $settings['mayor_message_title'] ?? 'كلمة رئيس البلدية';
    $mayorMessage = $settings['mayor_message'] ?? null;
    $mayorImagePath = $settings['mayor_image_path'] ?? null;
    $mayorImageUrl = $mayorImagePath ? asset('storage/' . $mayorImagePath) : ($municipality['mayor_image_url'] ?? null);

    // Try to get mayor from dedicated mayor data, then from council members, then fallback
    $mayorMember = $mayor ?? collect($featuredCouncilMembers)->first(function ($m) {
        $pos = $m['position'] ?? '';
        return str_contains($pos, 'رئيس') || $pos === 'mayor';
    });

    $mayorName = $mayorMember['full_name'] ?? ($settings['site_title'] ?? $municipalityName);
    $mayorPosition = $mayorMember['position_label'] ?? ($mayorMember['position'] ?? 'رئيس بلدية '.$municipalityName);
@endphp

@if ($showMayor && $mayorMessage)
    <section id="mayor-message" class="py-16 sm:py-20 lg:py-24 bg-primary-light/40">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-border/50 overflow-hidden" style="box-shadow: 0 4px 24px rgba(0,0,0,0.04);">
                <div class="grid lg:grid-cols-5 gap-0">
                    {{-- Mayor Image --}}
                    <div class="lg:col-span-2 relative min-h-[320px] bg-gradient-to-br from-primary/5 to-primary-light/30">
                        @if ($mayorImageUrl)
                            <img src="{{ $mayorImageUrl }}"
                                 alt="{{ $mayorName }}"
                                 class="w-full h-full object-cover object-center absolute inset-0"
                                 loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-24 h-24 rounded-full bg-primary-light flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="user" class="w-12 h-12 text-primary/50"></i>
                                    </div>
                                    <p class="text-text-secondary text-sm font-medium">صورة رئيس البلدية</p>
                                </div>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent lg:hidden"></div>
                        <div class="absolute bottom-4 right-4 lg:hidden">
                            <p class="text-white font-bold text-lg">{{ $mayorName }}</p>
                            <p class="text-white/80 text-sm">{{ $mayorPosition }}</p>
                        </div>
                    </div>

                    {{-- Message Content --}}
                    <div class="lg:col-span-3 p-6 sm:p-8 lg:p-10 flex flex-col justify-center">
                        <span class="section-title-line mb-3"></span>
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-black text-text mb-1">{{ $mayorTitle }}</h2>

                        {{-- Desktop Name --}}
                        <div class="hidden lg:flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-full bg-primary-light flex items-center justify-center">
                                <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <p class="font-bold text-text text-sm">{{ $mayorName }}</p>
                                <p class="text-xs text-text-secondary">{{ $mayorPosition }}</p>
                            </div>
                        </div>

                        <div class="text-text-secondary leading-relaxed text-sm sm:text-base mb-6 line-clamp-6 lg:line-clamp-none" style="max-height: 200px; overflow-y: auto;">
                            {{ $mayorMessage }}
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ url('/council') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-dark transition-all shadow-sm hover:shadow-md">
                                <i data-lucide="users" class="w-4 h-4"></i>
                                <span>أعضاء المجلس البلدي</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
