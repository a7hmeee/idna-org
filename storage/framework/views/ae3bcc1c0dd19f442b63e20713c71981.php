<?php
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
?>

<section class="relative z-10 quick-access-wrapper" style="margin-top:-58px;">
    <div style="width:100%;max-width:1280px;margin:0 auto;padding:0 clamp(16px,2.5vw,36px);">
        <div class="quick-access-card" style="background:#FFFFFF;border-radius:18px;border:1px solid rgba(20,70,30,0.08);box-shadow:0 18px 45px rgba(0,0,0,0.12);overflow:hidden;">
            <div class="quick-access-grid" style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;background:#E8ECE8;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $url = $item['url'] ?? '#';
                        $icon = $item['icon'] ?? 'link';
                        $title = $item['title'] ?? '';
                        $isExternal = $item['is_external'] ?? \Illuminate\Support\Str::startsWith($url, 'http');
                        $active = $isActive($url);
                    ?>
                    <a href="<?php echo e($url); ?>"
                       <?php if($isExternal): ?> target="_blank" rel="noopener noreferrer" <?php else: ?> wire:navigate <?php endif; ?>
                       class="quick-access-item <?php echo e($active ? 'quick-access-item-active' : ''); ?>"
                       style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:10px 10px;text-decoration:none;cursor:pointer;position:relative;background:<?php echo e($active ? '#EDF8EE' : '#FFFFFF'); ?>;transition:background 200ms ease;">
                        
                        <i data-lucide="<?php echo e($icon); ?>"
                           class="quick-access-icon"
                           style="width:26px;height:26px;stroke-width:1.7;margin-bottom:5px;color:<?php echo e($active ? '#176B32' : '#176B32'); ?>;transition:transform 200ms ease,color 200ms ease;"></i>
                        
                        <span class="quick-access-title"
                              style="font-size:14px;font-weight:600;color:<?php echo e($active ? '#176B32' : '#213547'); ?>;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100%;transition:color 200ms ease;"><?php echo e($title); ?></span>
                    </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (! $__env->hasRenderedOnce('3a42763f-ce48-414b-bd47-788dc46a60e2')): $__env->markAsRenderedOnce('3a42763f-ce48-414b-bd47-788dc46a60e2'); ?>
    <?php $__env->startPush('styles'); ?>
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
    <?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/quick-access.blade.php ENDPATH**/ ?>