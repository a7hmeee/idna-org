@props([
    'steps' => [],
])

@if (!empty($steps))
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 20px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="list-ordered" style="width:14px;height:14px;color:#0F6A3D;"></i>
            خطوات التقديم
        </h3>
        <div style="position:relative;">
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach ($steps as $index => $step)
                    @php
                        $stepTitle = is_string($step) ? $step : ($step['title'] ?? '');
                        $stepDesc = is_string($step) ? '' : ($step['description'] ?? '');
                        $isLast = $loop->last;
                    @endphp
                    <li wire:key="step-{{ $index }}" style="position:relative;display:flex;align-items:flex-start;gap:12px;{{ !$isLast ? 'padding-bottom:24px;' : '' }}">
                        {{-- Number + Line --}}
                        <div style="position:relative;display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            {{-- Number Circle --}}
                            <div style="width:32px;height:32px;border-radius:9999px;background:#0F6A3D;color:white;font-size:12px;font-weight:900;display:flex;align-items:center;justify-content:center;z-index:2;box-shadow:0 2px 8px rgba(15,106,61,0.25);">
                                {{ $index + 1 }}
                            </div>
                            {{-- Connecting Line --}}
                            @if (!$isLast)
                                <div style="width:2px;flex:1;background:#0F6A3D;border-radius:9999px;margin-top:6px;opacity:0.2;"></div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div style="min-width:0;flex:1;padding-top:5px;">
                            <p style="font-size:13px;font-weight:700;color:#1F2937;margin:0;">{{ $stepTitle }}</p>
                            @if (!empty($stepDesc))
                                <p style="font-size:12px;color:#6B7280;margin:5px 0 0;line-height:1.7;">{{ $stepDesc }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
