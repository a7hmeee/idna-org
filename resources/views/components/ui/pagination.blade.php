@props(['paginator'])

@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $window = 2;
        $elements = [];
        $showInfo = true;

        if ($current - $window > 1) {
            $elements[] = [$paginator->url(1) => 1];
        }
        if ($current - $window > 2) {
            $elements[] = '...';
        }
        for ($i = max(1, $current - $window); $i <= min($last, $current + $window); $i++) {
            $elements[] = [$paginator->url($i) => $i];
        }
        if ($current + $window < $last - 1) {
            $elements[] = '...';
        }
        if ($current + $window < $last) {
            $elements[] = [$paginator->url($last) => $last];
        }
    @endphp

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4" dir="rtl">
        {{-- Info text --}}
        <p style="font-size:12px;color:#9CA3AF;margin:0;">
            عرض
            <span style="font-weight:600;color:#4B5563;">{{ $paginator->firstItem() }}</span>
            إلى
            <span style="font-weight:600;color:#4B5563;">{{ $paginator->lastItem() }}</span>
            من أصل
            <span style="font-weight:600;color:#4B5563;">{{ $paginator->total() }}</span>
            نتيجة
        </p>

        {{-- Pagination buttons --}}
        <div class="flex flex-wrap items-center gap-1 justify-center">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#D1D5DB;cursor:default;">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="الصفحة السابقة"
                   style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                   onmouseover="this.style.background='#F3F4F6';this.style.borderColor='#E5E7EB';this.style.color='#0F6A3D'"
                   onmouseout="this.style.background='#F9FAFB';this.style.borderColor='#F3F4F6';this.style.color='#6B7280'">
                    <i data-lucide="chevron-right" style="width:16px;height:16px;"></i>
                </a>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#D1D5DB;cursor:default;">{{ $element }}</span>
                @elseif (is_array($element))
                    @foreach ($element as $url => $page)
                        @if ($page == $paginator->currentPage())
                            <span style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;background:#0F6A3D;color:white;box-shadow:0 2px 8px rgba(15,106,61,0.2);cursor:default;" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                               onmouseover="this.style.background='#F3F4F6';this.style.color='#0F6A3D'"
                               onmouseout="this.style.background='transparent';this.style.color='#6B7280'">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="الصفحة التالية"
                   style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#6B7280;text-decoration:none;transition:all 0.2s;"
                   onmouseover="this.style.background='#F3F4F6';this.style.borderColor='#E5E7EB';this.style.color='#0F6A3D'"
                   onmouseout="this.style.background='#F9FAFB';this.style.borderColor='#F3F4F6';this.style.color='#6B7280'">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </a>
            @else
                <span style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#F9FAFB;border:1px solid #F3F4F6;color:#D1D5DB;cursor:default;">
                    <i data-lucide="chevron-left" style="width:16px;height:16px;"></i>
                </span>
            @endif
        </div>
    </div>
@endif
