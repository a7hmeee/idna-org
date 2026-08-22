@props([
    'eyebrow' => null,
    'eyebrowIcon' => null,
    'title' => null,
    'subtitle' => null,
    'align' => 'center',
    'actionUrl' => null,
    'actionLabel' => null,
    'actionExternal' => false,
])

@php
    $alignStart = $align === 'start';
@endphp

<div class="mb-8 lg:mb-12 {{ $alignStart ? 'text-right' : 'text-center' }}">
    <div class="{{ $alignStart ? '' : 'flex flex-col items-center' }}">
        @if ($eyebrow)
            <span class="eyebrow-pill">
                @if ($eyebrowIcon)
                    <i data-lucide="{{ $eyebrowIcon }}" class="w-3.5 h-3.5"></i>
                @else
                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#C8A85A;box-shadow:0 0 0 4px rgba(200,168,90,0.2);"></span>
                @endif
                <span>{{ $eyebrow }}</span>
            </span>
        @endif

        @if ($title)
            <h2 class="display-heading mt-4" style="color:var(--color-ink);font-size:clamp(26px,3.2vw,40px);">
                {{ $title }}
            </h2>
        @endif

        @if ($subtitle)
            <p class="mt-3 text-sm sm:text-base leading-relaxed" style="color:var(--color-ink-soft);max-width:620px;{{ $alignStart ? '' : 'margin-inline:auto;' }}">
                {{ $subtitle }}
            </p>
        @endif

        @if ($title)
            <div class="mt-6 h-[3px] rounded-full" style="width:88px;background:linear-gradient(90deg, rgba(200,168,90,0.9), rgba(229,214,139,0.35));"></div>
        @endif
    </div>

    @if ($actionUrl && $actionLabel)
        <div class="{{ $alignStart ? 'mt-6' : 'mt-6' }}">
            <a href="{{ $actionUrl }}" @if($actionExternal) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif
               class="inline-flex items-center gap-2 text-sm font-bold no-underline transition-colors px-5 py-2.5 rounded-xl border"
               style="color:#0F4F28;border-color:rgba(15,79,40,0.25);background:#F4FAF5;">
                <span>{{ $actionLabel }}</span>
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
        </div>
    @endif
</div>