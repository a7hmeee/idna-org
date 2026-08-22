@props([
    'contacts' => [],
    'socialPlatforms' => [],
    'businessHours' => [],
    'emergencyContacts' => [],
])

@php
    $phone = collect($contacts)->firstWhere('type', 'phone');
    $email = collect($contacts)->firstWhere('type', 'email');
    $hours = collect($businessHours)->first();
    $emergency = collect($emergencyContacts)->first();
@endphp

<div class="bg-[#073A25] text-white/80 text-[11px] leading-tight">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-9 sm:h-10">
            {{-- Right: Contact Info --}}
            <div class="flex items-center gap-3 sm:gap-5 overflow-hidden">
                @if ($phone && !empty($phone['value']))
                    <a href="tel:{{ $phone['value'] }}" class="flex items-center gap-1.5 text-white/70 hover:text-white transition-colors whitespace-nowrap" aria-label="اتصل بنا: {{ $phone['value'] }}">
                        <i data-lucide="phone" class="w-3 h-3 shrink-0"></i>
                        <span class="hidden sm:inline">{{ $phone['value'] }}</span>
                        <span class="sm:hidden">{{ $phone['value'] }}</span>
                    </a>
                @endif
                @if ($email && !empty($email['value']))
                    <a href="mailto:{{ $email['value'] }}" class="items-center gap-1.5 text-white/70 hover:text-white transition-colors whitespace-nowrap hidden md:flex" aria-label="البريد الإلكتروني: {{ $email['value'] }}">
                        <i data-lucide="mail" class="w-3 h-3 shrink-0"></i>
                        <span>{{ $email['value'] }}</span>
                    </a>
                @endif
                @if ($hours && !empty($hours['opening_time']))
                    <span class="items-center gap-1.5 text-white/60 hidden lg:flex">
                        <i data-lucide="clock" class="w-3 h-3 shrink-0"></i>
                        <span>{{ $hours['opening_time'] }} - {{ $hours['closing_time'] ?? '' }}</span>
                    </span>
                @endif
            </div>

            {{-- Left: Social + Language + Emergency --}}
            <div class="flex items-center gap-2 sm:gap-3">
                @if ($emergency && !empty($emergency['value']))
                    <a href="tel:{{ $emergency['value'] }}" class="flex items-center gap-1.5 px-2 py-0.5 rounded-md bg-red-600/20 text-red-300 hover:bg-red-600/30 hover:text-red-200 transition-colors" aria-label="طوارئ: {{ $emergency['value'] }}">
                        <i data-lucide="alert-triangle" class="w-3 h-3 shrink-0"></i>
                        <span class="hidden sm:inline font-semibold">{{ $emergency['label'] ?? 'طوارئ' }}: {{ $emergency['value'] }}</span>
                        <span class="sm:hidden font-semibold">{{ $emergency['value'] }}</span>
                    </a>
                @endif

                @if (!empty($socialPlatforms))
                    <div class="items-center gap-1.5 hidden sm:flex">
                        @foreach ($socialPlatforms as $platform)
                            @php $url = $platform['url'] ?? $platform['platform_url'] ?? null; @endphp
                            @if ($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   class="w-6 h-6 rounded-md bg-white/5 hover:bg-white/15 flex items-center justify-center transition-colors text-white/60 hover:text-white"
                                   aria-label="{{ $platform['name'] ?? 'تواصل اجتماعي' }}">
                                    <i data-lucide="{{ $platform['icon'] ?? 'globe' }}" class="w-3 h-3"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif

                <span class="w-px h-3 bg-white/10 hidden sm:block"></span>

                <button class="text-white/60 hover:text-white transition-colors text-[11px] font-semibold px-1.5 py-0.5 rounded hover:bg-white/5" aria-label="English">
                    EN
                </button>
            </div>
        </div>
    </div>
</div>
