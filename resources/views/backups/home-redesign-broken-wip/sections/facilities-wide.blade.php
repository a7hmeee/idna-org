@php
    $f = $facility;
    $fName = $f['name'] ?? '';
    $fSummary = $f['summary'] ?? '';
    $fCategory = $f['category']['name'] ?? null;
    $fSlug = $f['slug'] ?? '';
    $fImage = $f['cover_image_url'] ?? null;
    $fAddress = $f['address'] ?? null;
    $fWorkingHours = $f['working_hours'] ?? null;
    $fIcon = $resolveIcon($fCategory);
    $fUrl = $fSlug && Route::has('public.facilities.show') ? route('public.facilities.show', $fSlug) : '#';
@endphp

<a href="{{ $fUrl }}" @if($fUrl !== '#') wire:navigate @endif
   class="facility-wide-card" aria-label="{{ $fName }}"
   style="display:flex;border-radius:18px;border:1px solid #E3E9E4;background:white;overflow:hidden;text-decoration:none;box-shadow:0 5px 18px rgba(20,50,30,0.05);min-height:190px;min-width:0;">

    {{-- Image side (~42%) --}}
    <div style="flex:0 0 42%;min-width:0;overflow:hidden;position:relative;">
        @if ($fImage)
            <img src="{{ $fImage }}"
                 alt="{{ $fName }}"
                 style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center;"
                 loading="lazy" decoding="async"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div style="display:none;position:absolute;inset:0;background:#EAF5EE;align-items:center;justify-content:center;">
                <i data-lucide="{{ $fIcon }}" style="width:28px;height:28px;color:#176B32;opacity:0.4;"></i>
            </div>
        @else
            <div style="position:absolute;inset:0;background:#EAF5EE;display:flex;align-items:center;justify-content:center;">
                <i data-lucide="{{ $fIcon }}" style="width:28px;height:28px;color:#176B32;opacity:0.4;"></i>
            </div>
        @endif
    </div>

    {{-- Content side (~58%) --}}
    <div style="flex:1;padding:16px 18px;display:flex;flex-direction:column;min-width:0;">
        @if ($fCategory)
            <span style="display:inline-flex;align-items:center;gap:3px;height:22px;padding:0 8px;border-radius:9999px;background:#EAF5EE;color:#176B32;font-size:10px;font-weight:600;align-self:flex-start;white-space:nowrap;">
                <i data-lucide="{{ $fIcon }}" style="width:10px;height:10px;"></i>
                <span>{{ $fCategory }}</span>
            </span>
        @endif

        <h3 style="margin:10px 0 0;font-size:clamp(15px,1.3vw,18px);font-weight:700;color:#17243A;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
            {{ $fName }}
        </h3>

        @if ($fSummary)
            <p style="margin:6px 0 0;font-size:12px;line-height:1.6;color:#66756D;overflow:hidden;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;">
                {{ $fSummary }}
            </p>
        @endif

        <div style="flex:1;min-height:4px;"></div>

        @if ($fAddress || $fWorkingHours)
            <div style="margin-top:8px;display:flex;align-items:center;gap:6px;">
                @if ($fAddress)
                    <span style="display:inline-flex;align-items:center;gap:3px;font-size:11px;color:#94A3B8;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                        <i data-lucide="map-pin" style="width:11px;height:11px;flex-shrink:0;"></i>
                        <span class="truncate">{{ $fAddress }}</span>
                    </span>
                @endif
            </div>
        @endif

        <div style="margin-top:10px;display:flex;align-items:center;gap:4px;color:#94A3B8;font-size:12px;font-weight:500;transition:color 200ms;">
            <span>التفاصيل</span>
            <i data-lucide="chevron-left" class="wide-card-arrow" style="width:13px;height:13px;transition:transform 200ms,color 200ms;"></i>
        </div>
    </div>
</a>
