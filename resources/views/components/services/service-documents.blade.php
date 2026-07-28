@props([
    'documents' => [],
])

@if (!empty($documents))
    <div>
        <h3 style="font-size:13px;font-weight:700;color:#1F2937;margin:0 0 14px;display:flex;align-items:center;gap:6px;">
            <i data-lucide="file-text" style="width:14px;height:14px;color:#0F6A3D;"></i>
            الوثائق المطلوبة
        </h3>
        <div style="display:grid;grid-template-columns:1fr;gap:8px;">
            @foreach ($documents as $index => $doc)
                @php
                    $docName = is_string($doc) ? $doc : ($doc['name'] ?? '');
                    $docDesc = is_string($doc) ? '' : ($doc['description'] ?? '');
                    $docRequired = is_string($doc) ? true : ($doc['is_required'] ?? true);
                @endphp
                <div wire:key="doc-{{ $index }}" style="display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-radius:10px;background:#F9FAFB;border:1px solid #F3F4F6;">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="file" style="width:13px;height:13px;color:#0F6A3D;"></i>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <p style="font-size:13px;font-weight:600;color:#1F2937;margin:0;">{{ $docName }}</p>
                        @if (!empty($docDesc))
                            <p style="font-size:12px;color:#9CA3AF;margin:4px 0 0;">{{ $docDesc }}</p>
                        @endif
                        <span style="font-size:9px;font-weight:700;margin-top:4px;display:inline-block;{{ $docRequired ? 'color:#DC2626;' : 'color:#9CA3AF;' }}">
                            {{ $docRequired ? 'إلزامي' : 'اختياري' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
