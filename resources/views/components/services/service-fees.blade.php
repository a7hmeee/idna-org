@props([
    'fees' => [],
])

@if (!empty($fees))
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="wallet" style="width:14px;height:14px;color:#0F6A3D;"></i>
            الرسوم
        </h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach ($fees as $index => $fee)
                @php
                    $feeTitle = is_string($fee) ? $fee : ($fee['title'] ?? '');
                    $feeNotes = is_string($fee) ? '' : ($fee['notes'] ?? '');
                    $feeAmount = is_string($fee) ? 0 : (float) ($fee['amount'] ?? 0);
                    $feeCurrency = is_string($fee) ? 'ILS' : ($fee['currency'] ?? 'ILS');
                @endphp
                <div wire:key="fee-{{ $index }}" style="display:flex;align-items:center;justify-content:space-between;padding:12px 14px;border-radius:10px;background:#F9FAFB;border:1px solid #F3F4F6;">
                    <div style="min-width:0;flex:1;">
                        <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;">{{ $feeTitle }}</p>
                        @if (!empty($feeNotes))
                            <p style="font-size:12px;color:#9CA3AF;margin:4px 0 0;">{{ $feeNotes }}</p>
                        @endif
                    </div>
                    <div style="text-align:left;flex-shrink:0;margin-right:12px;">
                        @if ($feeAmount > 0)
                            <p style="font-size:13px;font-weight:700;color:#0F6A3D;margin:0;">{{ number_format($feeAmount, 2) }} <span style="font-size:10px;font-weight:500;color:#9CA3AF;">{{ $feeCurrency }}</span></p>
                        @else
                            <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;background:#ECFDF5;color:#059669;">
                                <i data-lucide="check" style="width:11px;height:11px;"></i>
                                مجانية
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
