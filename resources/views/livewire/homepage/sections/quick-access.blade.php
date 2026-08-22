@php
    $canonicalOrder = [
        'الخدمات الإلكترونية',
        'جدول توزيع المياه',
        'المكاتب الهندسية',
        'الوظائف المتاحة',
        'المرافق العامة',
        'إعلانات البلدية',
        'تواصل معنا',
    ];

    $dbItems = collect($quickLinks)->take(7);
    $currentUrl = url()->current();

    $fallbackItems = $dbItems->isNotEmpty() ? null : collect([
        ['title' => 'الخدمات الإلكترونية', 'icon' => 'monitor',     'url' => $publicServicesIndexUrl ?? '#',                               'is_external' => false],
        ['title' => 'جدول توزيع المياه', 'icon' => 'droplets',     'url' => Route::has('public.water-schedule') ? route('public.water-schedule') : '#', 'is_external' => false],
        ['title' => 'المكاتب الهندسية',  'icon' => 'building-2',   'url' => Route::has('public.engineering-offices.index') ? route('public.engineering-offices.index') : '#', 'is_external' => false],
        ['title' => 'الوظائف المتاحة',   'icon' => 'briefcase',    'url' => Route::has('public.jobs.index') ? route('public.jobs.index') : '#',      'is_external' => false],
        ['title' => 'المرافق العامة',    'icon' => 'building',     'url' => Route::has('public.facilities.index') ? route('public.facilities.index') : '#', 'is_external' => false],
        ['title' => 'إعلانات البلدية',   'icon' => 'megaphone',    'url' => Route::has('public.announcements.index') ? route('public.announcements.index') : '#announcements', 'is_external' => false],
        ['title' => 'تواصل معنا',        'icon' => 'phone',        'url' => '#contact',                                                              'is_external' => false],
    ]);

    $items = $dbItems->isNotEmpty() ? $dbItems : $fallbackItems;

    if ($dbItems->isNotEmpty()) {
        $items = $items
            ->sortBy(fn ($item) => array_search($item['title'] ?? '', $canonicalOrder) !== false ? array_search($item['title'] ?? '', $canonicalOrder) : 99)
            ->values();
    }

    $itemCount = $items->count();

    $isActive = function ($url) use ($currentUrl) {
        if ($url === '#' || $url === '#announcements' || $url === '#contact') return false;
        $path = trim(parse_url($url, PHP_URL_PATH) ?? '', '/');
        $currentPath = trim(request()->path(), '/');
        return $path !== '' && $path === $currentPath;
    };
@endphp

<section class="relative z-20 quick-access-wrapper" style="margin-top:-56px;">
    <div class="container-home">
        <div class="quick-access-card" style="background:#FFFFFF;border-radius:28px;box-shadow:0 30px 70px rgba(12,42,24,0.22);overflow:hidden;">
            <div class="quick-access-grid" style="display:grid;grid-template-columns:repeat({{ $itemCount }},1fr);gap:1px;background:#E6EBE6;">
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
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:7px;padding:20px 12px;text-decoration:none;cursor:pointer;background:{{ $active ? '#F0FAF2' : '#FFFFFF' }};transition:background 200ms ease;">
                        <span class="quick-access-icon-circle"
                              style="width:50px;height:50px;border-radius:50%;background:#eef6ef;display:flex;align-items:center;justify-content:center;transition:all 200ms ease;">
                            <i data-lucide="{{ $icon }}"
                               class="quick-access-icon"
                               style="width:23px;height:23px;stroke-width:1.8;color:#173f27;transition:transform 200ms ease,color 200ms ease;"></i>
                        </span>
                        <span class="quick-access-title"
                              style="font-size:13.5px;font-weight:800;color:{{ $active ? '#173f27' : '#111827' }};text-align:center;line-height:1.35;transition:color 200ms ease;">{{ $title }}</span>
                        <i data-lucide="arrow-left" class="quick-access-arrow"
                           style="width:15px;height:15px;color:#173f27;opacity:0.45;transition:opacity 200ms ease,transform 200ms ease;"></i>
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
            .quick-access-item:hover .quick-access-icon-circle {
                background: #173f27 !important;
            }
            .quick-access-item:hover .quick-access-icon {
                color: #FFFFFF !important;
                transform: translateY(-2px);
            }
            .quick-access-item:hover .quick-access-title {
                color: #173f27 !important;
            }
            .quick-access-item:hover .quick-access-arrow {
                opacity: 1 !important;
                transform: translateX(-3px);
            }
            .quick-access-item-active .quick-access-title {
                color: #173f27 !important;
            }
            .quick-access-item-active .quick-access-arrow {
                opacity: 1 !important;
            }

            @media (max-width: 1024px) and (min-width: 641px) {
                .quick-access-grid { grid-template-columns: repeat(4, 1fr) !important; }
            }
            @media (max-width: 640px) {
                .quick-access-wrapper { margin-top: -28px !important; }
                .quick-access-card { border-radius: 16px !important; }
                .quick-access-grid { grid-template-columns: repeat(2, 1fr) !important; }
                .quick-access-item { padding: 14px 8px !important; gap: 5px !important; }
                .quick-access-icon-circle { width: 42px !important; height: 42px !important; }
                .quick-access-icon { width: 20px !important; height: 20px !important; }
                .quick-access-title { font-size: 12px !important; }
                .quick-access-arrow { display: none !important; }
            }
        </style>
    @endpush
@endonce