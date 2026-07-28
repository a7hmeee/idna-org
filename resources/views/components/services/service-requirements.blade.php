@props([
    'requirements' => [],
])

@if (!empty($requirements))
    <div style="margin-bottom:20px;">
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 16px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="clipboard-list" style="width:14px;height:14px;color:#0F6A3D;"></i>
            المتطلبات
        </h3>
        <div style="position:relative;padding-right:0;">
            <ul style="list-style:none;padding:0;margin:0;position:relative;">
                @foreach ($requirements as $index => $req)
                    @php
                        $reqTitle = is_string($req) ? $req : ($req['title'] ?? '');
                        $reqRequired = is_string($req) ? true : ($req['is_required'] ?? true);
                        $reqDesc = is_string($req) ? '' : ($req['description'] ?? '');
                        $isLast = $loop->last;
                    @endphp
                    <li wire:key="req-{{ $index }}" style="position:relative;display:flex;align-items:flex-start;gap:12px;{{ !$isLast ? 'padding-bottom:20px;' : '' }}">
                        {{-- Icon + Line --}}
                        <div style="position:relative;display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            {{-- Circle --}}
                            <div style="width:28px;height:28px;border-radius:9999px;display:flex;align-items:center;justify-content:center;z-index:2;{{ $reqRequired ? 'background:#0F6A3D;color:white;' : 'background:#F3F4F6;border:2px solid #E5E7EB;color:#9CA3AF;' }}">
                                <i data-lucide="{{ $reqRequired ? 'check' : 'circle' }}" style="width:14px;height:14px;"></i>
                            </div>
                            {{-- Line --}}
                            @if (!$isLast)
                                <div style="width:2px;flex:1;background:{{ $reqRequired ? '#0F6A3D' : '#E5E7EB' }};border-radius:9999px;margin-top:4px;opacity:0.3;"></div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div style="min-width:0;flex:1;padding-top:3px;">
                            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;">{{ $reqTitle }}</p>
                                @if ($reqRequired)
                                    <span style="font-size:9px;font-weight:700;color:#DC2626;background:#FEF2F2;padding:2px 7px;border-radius:4px;">إلزامي</span>
                                @else
                                    <span style="font-size:9px;font-weight:700;color:#9CA3AF;background:#F3F4F6;padding:2px 7px;border-radius:4px;border:1px solid #E5E7EB;">اختياري</span>
                                @endif
                            </div>
                            @if (!empty($reqDesc))
                                <p style="font-size:12px;color:#9CA3AF;margin:5px 0 0;line-height:1.6;">{{ $reqDesc }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
