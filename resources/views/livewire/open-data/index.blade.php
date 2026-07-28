<div>

    @livewire('public-page-carousel', [
        'pageKey' => 'open-data',
        'fallbackTitle' => "البيانات المفتوحة",
        'fallbackDescription' => "تصفح البيانات المفتوحة المتاحة من بلدية إذنا، بما في ذلك التقارير والإحصاءات والدراسات.",
        'fallbackBadge' => 'البيانات المفتوحة',
        'fallbackIcon' => 'file-text',
        'fallbackImage' => $slides->isNotEmpty() ? $slides->first()->image_url : null,
        'compact' => false,
    ])

    <section id="datasets-list" class="py-12 sm:py-16 lg:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div style="display:flex;flex-direction:column;gap:16px;margin-bottom:28px;">
                <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;">
                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                        <button wire:click="$set('type', 'datasets')"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $type == 'datasets' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $type == 'datasets' ? '#0F6A3D' : 'white' }};color:{{ $type == 'datasets' ? 'white' : '#6B7280' }};">
                            مجموعات البيانات
                        </button>
                        <button wire:click="$set('type', 'reports')"
                                style="padding:7px 18px;border-radius:20px;font-size:12px;font-weight:600;cursor:pointer;transition:all 0.2s;border:1px solid {{ $type == 'reports' ? '#0F6A3D' : '#E5E7EB' }};background:{{ $type == 'reports' ? '#0F6A3D' : 'white' }};color:{{ $type == 'reports' ? 'white' : '#6B7280' }};">
                            التقارير
                        </button>
                    </div>
                    <div style="position:relative;width:100%;max-width:340px;">
                        <i data-lucide="search" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:#9CA3AF;pointer-events:none;"></i>
                        <input type="text" wire:model.live.debounce.400ms="search"
                               placeholder="ابحث في البيانات..."
                               style="width:100%;background:#F9FAFB;border:1px solid #E5E7EB;border-radius:10px;padding:11px 44px 11px 16px;font-size:13px;color:#1F2937;outline:none;transition:all 0.2s;"
                               onfocus="this.style.borderColor='#0F6A3D';this.style.boxShadow='0 0 0 3px rgba(15,106,61,0.1)'"
                               onblur="this.style.borderColor='#E5E7EB';this.style.boxShadow='none'">
                    </div>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <p style="font-size:13px;color:#6B7280;margin:0;">
                    يوجد <span style="font-weight:700;color:#1F2937;">{{ $datasets->total() }}</span> مجموعة بيانات
                </p>
            </div>

            @if ($datasets->isEmpty() && !$hasActiveFilters && $categories->isEmpty())
                <div style="text-align:center;padding:80px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:80px;height:80px;border-radius:20px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                        <i data-lucide="file-text" style="width:40px;height:40px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:20px;font-weight:700;color:#1F2937;margin:0 0 8px;">البيانات المفتوحة</h3>
                    <p style="font-size:14px;color:#9CA3AF;max-width:400px;margin:0 auto;line-height:1.7;">
                        سيتم نشر البيانات المفتوحة والتقارير قريباً. تابعونا للاطلاع على أحدث الإحصاءات والدراسات.
                    </p>
                </div>
            @elseif ($datasets->isEmpty())
                <div style="text-align:center;padding:64px 24px;background:white;border-radius:16px;border:1px solid #F3F4F6;">
                    <div style="width:64px;height:64px;border-radius:16px;background:rgba(15,106,61,0.06);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i data-lucide="search-x" style="width:32px;height:32px;color:#9CA3AF;"></i>
                    </div>
                    <h3 style="font-size:16px;font-weight:700;color:#1F2937;margin:0 0 8px;">لا توجد نتائج</h3>
                    <p style="font-size:13px;color:#9CA3AF;margin:0;">جرّب البحث بكلمات مختلفة أو غيّر التصنيف</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($datasets as $dataset)
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 transition-all duration-200"
                             style="box-shadow:0 1px 3px rgba(0,0,0,0.03);">
                            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:12px;">
                                <div style="width:48px;height:48px;border-radius:12px;background:rgba(15,106,61,0.08);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i data-lucide="file-text" style="width:20px;height:20px;color:#0F6A3D;"></i>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <h3 style="font-size:14px;font-weight:700;color:#1F2937;margin:0 0 4px;">{{ $dataset->title }}</h3>
                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                        @if ($dataset->category)
                                            <span style="font-size:11px;color:#0F6A3D;font-weight:600;background:rgba(15,106,61,0.06);padding:2px 8px;border-radius:6px;">{{ $dataset->category }}</span>
                                        @endif
                                        @if ($dataset->format)
                                            <span style="font-size:10px;color:#6B7280;font-weight:600;">{{ $dataset->format }}</span>
                                        @endif
                                        @if ($dataset->file_size)
                                            <span style="font-size:10px;color:#9CA3AF;">{{ $dataset->file_size }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($dataset->description)
                                <p style="font-size:12px;color:#9CA3AF;line-height:1.6;margin:0 0 12px;">{{ $dataset->description }}</p>
                            @endif
                            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:12px;border-top:1px solid #F3F4F6;">
                                <span style="font-size:11px;color:#9CA3AF;">{{ $dataset->published_at ? \Carbon\Carbon::parse($dataset->published_at)->format('Y-m-d') : '' }}</span>
                                @if ($dataset->download_url)
                                    <a href="{{ $dataset->download_url }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;color:#0F6A3D;text-decoration:none;">
                                        <i data-lucide="download" style="width:12px;height:12px;"></i>
                                        <span>تحميل</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($datasets->hasPages())
                <div class="mt-10">
                    {{ $datasets->links() }}
                </div>
            @endif
        </div>
    </section>

</div>