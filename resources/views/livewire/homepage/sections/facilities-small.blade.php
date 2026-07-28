@php
    $f = $facility;
    $fName = $f['name'] ?? '';
    $fCategory = $f['category']['name'] ?? null;
    $fSlug = $f['slug'] ?? '';
    $fImage = $f['cover_image_url'] ?? null;
    $fAddress = $f['address'] ?? null;
    $fIcon = $resolveIcon($fCategory);
    $fUrl = $fSlug && Route::has('public.facilities.show') ? route('public.facilities.show', $fSlug) : '#';
@endphp

<a href="{{ $fUrl }}" @if($fUrl !== '#') wire:navigate @endif
   class="facility-small-card" aria-label="{{ $fName }}"
   style="display:flex;flex-direction:column;border-radius:17px;border:1px solid #E3E9E4;background:white;overflow:hidden;text-decoration:none;box-shadow:0 5px 18px rgba(20,50,30,0.05);min-height:200px;min-width:0;">

    {{-- Image top (~55-60%) --}}
    <div style="flex:0 0 58%;min-height:0;overflow:hidden;position:relative;">
        @if ($fImage)
            <img src="{{ $fImage }}"
                 alt="{{ $fName }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
                 loading="lazy" decoding="async"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;position:absolute;inset:0;background:#EAF5EE;align-items:center;justify-content:center;">
                <i data-lucide="{{ $fIcon }}" style="width:24px;height:24px;color:#176B32;opacity:0.4;"></i>
            </div>
        @else
            <div style="position:absolute;inset:0;background:#EAF5EE;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="{{ $fIcon }}" style="width:24px;height:24px;color:#176B32;opacity:0.4;"></i>
            </div>
        @endif
    </div>

    {{-- Content bottom --}}
    <div style="flex:1;padding:12px 14px;display:flex;flex-direction:column;min-width:0;">
        @if ($fCategory)
            <span style="display:inline-flex;align-items:center;gap:3px;height:20px;padding:0 7px;border-radius:9999px;background:#EAF5EE;color:#176B32;font-size:9px;font-weight:600;align-self:flex-start;white-space:nowrap;">
                <i data-lucide="{{ $fIcon }}" style="width:9px;height:9px;"></i>
                <span>{{ $fCategory }}</span>
            </span>
        @endif

        <h3 style="margin:8px 0 0;font-size:clamp(13px,1.1vw,15px);font-weight:700;color:#17243A;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
            {{ $fName }}
        </h3>

        <div style="flex:1;min-height:4px;"></div>

        @if ($fAddress)
            <div style="margin-top:6px;display:flex;align-items:center;gap:3px;">
                <i data-lucide="map-pin" style="width:10px;height:10px;color:#94A3B8;flex-shrink:0;"></i>
                <span style="font-size:10px;color:#94A3B8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $fAddress }}</span>
            </div>
        @endif
    </div>
</a>
