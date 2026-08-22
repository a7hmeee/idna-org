@php
    $dbItems = collect($quickLinks)->take(7);
    $currentUrl = url()->current();

    $fallbackItems = $dbItems->isNotEmpty() ? null : collect([
        ['title' => 'الخدمات الإلكترونية', 'icon' => 'monitor',     'url' => $publicServicesIndexUrl ?? '#',                               'is_external' => false],
        ['title' => 'جدول توزيع المياه', 'icon' => 'droplets',     'url' => Route::has('public.water-schedule') ? route('public.water-schedule') : '#', 'is_external' => false],
        ['title' => 'المكاتب الهندسية',  'icon' => 'building-2',   'url' => Route::has('public.departments.index') ? route('public.departments.index') : '#', 'is_external' => false],
        ['title' => 'الوظائف المتاحة',   'icon' => 'briefcase',    'url' => Route::has('public.jobs.index') ? route('public.jobs.index') : '#',      'is_external' => false],
        ['title' => 'المرافق العامة',    'icon' => 'building',     'url' => Route::has('public.facilities.index') ? route('public.facilities.index') : '#', 'is_external' => false],
        ['title' => 'إعلانات البلدية',   'icon' => 'megaphone',    'url' => '#announcements',                                                        'is_external' => false],
        ['title' => 'تواصل معنا',        'icon' => 'phone',        'url' => '#contact',                                                              'is_external' => false],
    ]);

    $items = $dbItems->isNotEmpty() ? $dbItems : $fallbackItems;
    $itemCount = $items->count();

    $isActive = function ($url) use ($currentUrl) {
        if ($url === '#' || $url === '#announcements' || $url === '#contact') return false;
        $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $currentPath = trim(request()->path(), '/');
        return $path !== '' && $path === $currentPath;
    };
@endphp

<section class="relative z-10 quick-access-wrapper" style="margin-top:-58px;">
    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">
        <div class="quick-access-card" style="background:#FFFFFF;border-radius:18px;border:1px solid rgba(20,70,30,0.08);box-shadow:0 18px 45px rgba(0,0,0,0.12);overflow:hidden;">
            <div class="quick-access-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;background:#E8ECE8;">
                @foreach ($items as $i => $item)
                    @php
                        $url = $item['url'] ?? '#';
                        $icon = $item['icon'] ?? 'link';
                        $title = $item['title'] ?? '';
                        $isExternal = $item['is_external'] ?? \Illuminate\Support\Str::startsWith($url, 'http');
                        $active = $isActive($url);
                    @endphp
                    <a href="{{ $url }}"
                       @if($isExternal) target="_blank" rel="noopener noreferrer" @else wire:navigate @endif
                       class="quick-access-item {{ $active ? 'quick-access-item-active' : '' }}"
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px 10px;text-decoration:none;cursor:pointer;position:relative;background:{{ $active ? '#EDF8EE' : '#FFFFFF' }};transition:background 200ms ease;">
                        {{-- Icon --}}
                        <i data-lucide="{{ $icon }}"
                           class="quick-access-icon"
                           style="width:26px;height:26px;stroke-width:1.7;margin-bottom:5px;color:{{ $active ? '#176B32' : '#176B32' }};transition:transform 200ms ease,color 200ms ease;"></i>
                        {{-- Title --}}
                        <span class="quick-access-title"
                              style="font-size:14px;font-weight:600;color:{{ $active ? '#176B32' : '#213547' }};text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;transition:color 200ms ease;">{{ $title }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

@once
    @push('styles')
        <style>
            .quick-access-item:hover {
                background: #F5FBF6 !important;
            }
            .quick-access-item:hover .quick-access-icon {
                transform: translateY(-2px);
            }
            .quick-access-item:hover .quick-access-title {
                color: #176B32 !important;
            }
            .quick-access-item-active .quick-access-title {
                color: #176B32 !important;
            }
            .quick-access-item-active .quick-access-icon {
                color: #176B32 !important;
            }

            @media (max-width: 1024px) and (min-width: 641px) {
                .quick-access-grid { display: flex !important; flex-wrap: wrap !important; justify-content: center !important; gap: 0 !important; }
                .quick-access-grid > a { width: 25% !important; flex: 0 0 25% !important; padding: 8px 8px !important; }
                .quick-access-grid > a:nth-child(n+5) { width: 33.333% !important; flex: 0 0 33.333% !important; border-top: 1px solid #E8ECE8 !important; }
                .quick-access-grid > a:nth-child(5) > span:first-child { display: none !important; }
            }
            @media (max-width: 640px) {
                .quick-access-wrapper { margin-top: -28px !important; }
                .quick-access-grid { grid-template-columns: repeat(2, 1fr) !important; }
                .quick-access-grid > a:nth-child(n+3) { border-top: 1px solid #E8ECE8 !important; }
                .quick-access-grid > a:nth-child(odd) > span:first-child { display: none !important; }
                .quick-access-item { padding: 8px 8px !important; }
                .quick-access-card { border-radius: 16px !important; }
                .quick-access-icon { width: 22px !important; height: 22px !important; margin-bottom: 4px !important; }
                .quick-access-title { font-size: 12px !important; }
                .quick-access-wrapper > div { padding-left: 16px !important; padding-right: 16px !important; }
            }
        </style>
    @endpush
@endonce
