<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'portalUrl' => '',
    'sectionKeys' => [],
    'settings' => [],
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'portalUrl' => '',
    'sectionKeys' => [],
    'settings' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $municipalityName = $municipalityName ?: '';
    $municipalitySubtitle = $municipalitySubtitle ?: '';
    $logoUrl = $logoUrl ?: null;
    $portalUrl = $portalUrl ?: '';
    $isHomeActive = request()->routeIs('home');
    $isAboutActive = request()->routeIs('public.municipality.about');
    $isServicesActive = request()->routeIs('public.services.*');
    $isCouncilActive = request()->routeIs('public.council.*');
    $isDepartmentsActive = request()->routeIs('public.departments.*');
    $isFacilitiesActive = request()->routeIs('public.facilities.*');
    $isJobsActive = request()->routeIs('public.jobs.*');
    $isEngineeringActive = request()->routeIs('public.engineering-offices.*');
    $isAnnouncementsActive = request()->routeIs('public.announcements.*');

    $topBarPhone = null;
    $topBarEmail = null;
    try {
        $municipalityModel = \App\Domains\Municipality\Models\Municipality::first();
        if ($municipalityModel) {
            $topBarPhone = $municipalityModel->contacts()->where('type', 'phone')->first()?->value;
            $topBarEmail = $municipalityModel->contacts()->where('type', 'email')->first()?->value;
        }
    } catch (\Throwable $e) {}
?>

<?php
    $navItemDefs = [
        ['label' => 'الرئيسية', 'route' => 'home', 'active' => $isHomeActive, 'params' => []],
        ['label' => 'عن البلدية', 'route' => 'public.municipality.about', 'active' => $isAboutActive, 'params' => []],
        ['label' => 'الخدمات', 'route' => 'public.services.index', 'active' => $isServicesActive, 'params' => []],
        ['label' => 'المجلس البلدي', 'route' => 'public.council.index', 'active' => $isCouncilActive, 'params' => []],
        ['label' => 'الأقسام', 'route' => 'public.departments.index', 'active' => $isDepartmentsActive, 'params' => []],
        ['label' => 'المرافق العامة', 'route' => 'public.facilities.index', 'active' => $isFacilitiesActive, 'params' => []],
        ['label' => 'الوظائف', 'route' => 'public.jobs.index', 'active' => $isJobsActive, 'params' => []],
        ['label' => 'المكاتب الهندسية', 'route' => 'public.engineering-offices.index', 'active' => $isEngineeringActive, 'params' => []],
        ['label' => 'الإعلانات', 'route' => 'public.announcements.index', 'active' => $isAnnouncementsActive, 'params' => []],
    ];
    $navItems = [];
    foreach ($navItemDefs as $def) {
        try {
            $url = route($def['route'], $def['params']);
            $navItems[] = ['label' => $def['label'], 'url' => $url, 'active' => $def['active'], 'route' => $def['route']];
        } catch (\Throwable $e) {
            continue;
        }
    }
?>

<style>
    /****************************************************************************
     *  RESPONSIVE LAYOUT – CSS only, no Tailwind dependency for structure
     ****************************************************************************/
    .ih-header { position: sticky; top: 0; z-index: 20; transition: all 0.3s ease; width: 100%; }
    .ih-topbar { background: #17243A; height: 34px; }
    .ih-navwrap { transition: all 0.3s ease; }

    /* --- Desktop layout (>= 1280px) --- */
    .ih-desktop { display: none; }
    @media (min-width: 1280px) {
        .ih-desktop {
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            height: 72px;
            gap: 1.25rem;
        }
    }

    /* --- Tablet layout (1024px – 1279px) --- */
    .ih-tablet { display: none; }
    @media (min-width: 1024px) and (max-width: 1279.98px) {
        .ih-tablet {
            display: flex;
            align-items: center;
            height: 56px;
            gap: 0.5rem;
        }
    }

    /* --- Mobile layout (< 1024px) --- */
    .ih-mobile { display: flex; align-items: center; justify-content: space-between; }
    @media (min-width: 1024px) {
        .ih-mobile { display: none; }
    }

    /* --- "المزيد" width-based visibility --- */
    @media (min-width: 1400px) {
        .ih-nav-wide { display: flex !important; }
        .ih-more-desktop { display: none !important; }
    }
    @media (max-width: 1399.98px) {
        .ih-nav-wide { display: none !important; }
        .ih-more-desktop { display: inline-flex !important; }
    }

    /* --- Brand --- */
    .ih-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-shrink: 0;
        min-width: 200px;
        text-decoration: none;
    }
    .ih-brand-logo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .ih-brand-logo img {
        width: 30px;
        height: 30px;
        object-fit: contain;
    }

    /* --- Nav links --- */
    .ih-nav {
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .ih-nav-inner {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-wrap: nowrap;
    }
    .ih-nav-link {
        padding: 0.5rem 0.625rem;
        border-bottom-width: 2px;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
        text-decoration: none;
        flex-shrink: 0;
        transition: color 0.2s ease, border-bottom-color 0.2s ease;
        user-select: none;
    }
    .ih-nav-link:hover { color: white !important; }
    .ih-nav-link:focus-visible {
        outline: 2px solid white;
        outline-offset: -2px;
        border-radius: 2px;
    }

    /* --- Portal button --- */
    .ih-portal-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 12px;
        background: white;
        color: #176B32;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: box-shadow 0.3s ease;
        flex-shrink: 0;
    }
    .ih-portal-btn:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.3); }

    /* --- Mobile menu --- */
    .ih-mobile-menu {
        position: fixed;
        top: 0;
        left: 0;
        width: 85%;
        max-width: min(360px, calc(100vw - 24px));
        height: 100vh;
        overflow-y: auto;
        z-index: 50;
        background: rgba(23, 107, 50, 0.98);
        backdrop-filter: blur(8px);
        border-left: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    }

    /* --- More dropdown --- */
    .ih-more-dd {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.25rem;
        min-width: 180px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        border: 1px solid #f0f0f0;
        padding: 0.5rem 0;
        z-index: 50;
    }
    .ih-more-dd a {
        display: block;
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        transition: background 0.15s;
    }
    .ih-more-dd a:hover { background: #f9fafb; }

    /* --- Mobile overlay --- */
    .ih-overlay {
        position: fixed;
        inset: 0;
        z-index: 40;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
    }

    /* --- Responsive utility classes (no Tailwind dependency) --- */
    @media (min-width: 640px) {
        .sm-flex { display: flex !important; }
        .sm-block { display: block !important; }
    }
    @media (min-width: 768px) {
        .md-flex { display: flex !important; }
        .md-inline { display: inline !important; }
    }

    /* --- "المزيد" arrow rotation --- */
    .rotated { transform: rotate(180deg); }
</style>

<header class="ih-header" x-data="{ scrolled: false, mobileOpen: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })">

    
    <div class="ih-topbar" style="display:flex;align-items:center;">
        <div class="container-home" style="width:100%;height:100%;">
            <div style="display:flex;align-items:center;justify-content:space-between;height:100%;">
                <div style="display:flex;align-items:center;gap:1rem;overflow:hidden;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topBarPhone): ?>
                        <a href="tel:<?php echo e($topBarPhone); ?>" style="display:flex;align-items:center;gap:0.375rem;color:rgba(255,255,255,0.7);text-decoration:none;white-space:nowrap;">
                            <i data-lucide="phone" style="width:12px;height:12px;flex-shrink:0;"></i>
                            <span style="font-size:11px;"><?php echo e($topBarPhone); ?></span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topBarEmail): ?>
                        <a href="mailto:<?php echo e($topBarEmail); ?>" style="display:none;align-items:center;gap:0.375rem;color:rgba(255,255,255,0.7);text-decoration:none;white-space:nowrap;" class="md-flex">
                            <i data-lucide="mail" style="width:12px;height:12px;flex-shrink:0;"></i>
                            <span style="font-size:11px;"><?php echo e($topBarEmail); ?></span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" style="display:none;align-items:center;gap:0.375rem;color:rgba(255,255,255,0.7);text-decoration:none;white-space:nowrap;" class="sm-flex">
                            <i data-lucide="globe" style="width:12px;height:12px;flex-shrink:0;"></i>
                            <span style="font-size:11px;">البوابة الإلكترونية</span>
                        </a>
                        <span style="display:none;width:1px;height:12px;background:rgba(255,255,255,0.12);" class="sm-block"></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button style="color:rgba(255,255,255,0.6);font-size:11px;font-weight:600;padding:0.125rem 0.375rem;border-radius:4px;cursor:pointer;background:none;border:none;">EN</button>
                </div>
            </div>
        </div>
    </div>

    
    <div class="ih-navwrap" :style="scrolled ? 'background:rgba(23,107,50,0.97);backdrop-filter:blur(16px);box-shadow:0 4px 30px rgba(0,0,0,0.3)' : 'background:linear-gradient(to bottom,rgba(23,107,50,0.95),rgba(23,107,50,0.85))'">
        <div class="container-home">

            
            <div class="ih-desktop">

                
                <a href="<?php echo e(route('home')); ?>" wire:navigate class="ih-brand">
                    <div class="ih-brand-logo">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>">
                    </div>
                    <div style="min-width:0;">
                        <p style="font-weight:900;color:white;font-size:1rem;line-height:1.25;margin:0;"><?php echo e($municipalityName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipalitySubtitle): ?>
                            <p style="font-size:11px;color:rgba(255,255,255,0.7);font-weight:500;margin:2px 0 0 0;line-height:1.3;max-width:240px;"><?php echo e($municipalitySubtitle); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>

                
                <nav class="ih-nav" aria-label="القائمة الرئيسية">
                    <div class="ih-nav-inner">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <?php $isWideItem = $loop->index >= 7; ?>
                            <a href="<?php echo e($item['url']); ?>" wire:navigate
                               class="ih-nav-link <?php echo e($isWideItem ? 'ih-nav-wide' : ''); ?>"
                               style="border-bottom-color: <?php echo e($item['active'] ? 'white' : 'transparent'); ?>; color: <?php echo e($item['active'] ? 'white' : 'rgba(255,255,255,0.85)'); ?>;"
                               onmouseover="this.style.borderBottomColor='rgba(255,255,255,0.5)'"
                               onmouseout="this.style.borderBottomColor='<?php echo e($item['active'] ? 'white' : 'transparent'); ?>'"><?php echo e($item['label']); ?></a>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

                        
                        <div class="ih-more-desktop" style="position:relative;flex-shrink:0;" x-data="{ moreOpen: false }" @mouseenter="moreOpen = true" @mouseleave="moreOpen = false" @click.away="moreOpen = false">
                            <button class="ih-nav-link" style="border-bottom-color:transparent;color:rgba(255,255,255,0.85);cursor:pointer;background:none;display:inline-flex;align-items:center;gap:0.25rem;"
                                    :aria-expanded="moreOpen">
                                <span>المزيد</span>
                                <svg style="width:12px;height:12px;transition:transform 0.2s;" :class="moreOpen ? 'rotated' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="moreOpen" x-cloak
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="ih-more-dd">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_slice($navItems, 7); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                                    <a href="<?php echo e($subItem['url']); ?>" wire:navigate @click="moreOpen = false" style="<?php echo e($subItem['active'] ? 'color:#176B32;background:#f9fafb;' : ''); ?>"><?php echo e($subItem['label']); ?></a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </nav>

                
                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" class="ih-portal-btn">
                            <i data-lucide="external-link" style="width:14px;height:14px;flex-shrink:0;"></i>
                            <span>بوابة الخدمات</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="ih-tablet">

                <a href="<?php echo e(route('home')); ?>" wire:navigate style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;min-width:140px;text-decoration:none;">
                    <div style="width:36px;height:36px;border-radius:12px;background:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:22px;height:22px;object-fit:contain;">
                    </div>
                    <div>
                        <p style="font-weight:900;color:white;font-size:0.875rem;line-height:1.25;margin:0;"><?php echo e($municipalityName); ?></p>
                    </div>
                </a>

                <nav style="display:flex;align-items:center;gap:0.125rem;flex:1;min-width:0;overflow:hidden;" aria-label="القائمة الرئيسية">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = array_slice($navItems, 0, 5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e($item['url']); ?>" wire:navigate
                           style="padding:0.375rem;border-bottom:2px solid <?php echo e($item['active'] ? 'white' : 'transparent'); ?>;font-size:10.5px;font-weight:600;white-space:nowrap;text-decoration:none;flex-shrink:0;color:<?php echo e($item['active'] ? 'white' : 'rgba(255,255,255,0.85)'); ?>;"><?php echo e($item['label']); ?></a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <button @click="mobileOpen = true"
                            style="padding:0.375rem;border-bottom:2px solid transparent;font-size:10.5px;font-weight:600;color:rgba(255,255,255,0.85);white-space:nowrap;text-decoration:none;flex-shrink:0;background:none;cursor:pointer;border-top:none;border-left:none;border-right:none;display:inline-flex;align-items:center;gap:0.25rem;">
                        <span>المزيد</span>
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </nav>

                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border-radius:8px;background:white;color:#176B32;font-size:10px;font-weight:700;text-decoration:none;box-shadow:0 1px 4px rgba(0,0,0,0.1);white-space:nowrap;flex-shrink:0;">
                            <i data-lucide="external-link" style="width:12px;height:12px;flex-shrink:0;"></i>
                            <span class="md-inline" style="display:none;">بوابة الخدمات</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;color:white;background:rgba(255,255,255,0.15);border:none;cursor:pointer;flex-shrink:0;" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="فتح القائمة" aria-controls="mobile-menu">
                        <svg x-show="!mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            
            <div class="ih-mobile" style="height:56px;">

                <a href="<?php echo e(route('home')); ?>" wire:navigate style="display:flex;align-items:center;gap:0.625rem;text-decoration:none;min-width:0;">
                    <div style="width:36px;height:36px;border-radius:12px;background:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:24px;height:24px;object-fit:contain;">
                    </div>
                    <div style="min-width:0;">
                        <p style="font-weight:900;color:white;font-size:0.875rem;line-height:1.25;margin:0;"><?php echo e($municipalityName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipalitySubtitle): ?>
                            <p style="font-size:10px;color:rgba(255,255,255,0.7);font-weight:500;margin:2px 0 0 0;line-height:1.2;"><?php echo e($municipalitySubtitle); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>

                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.375rem 0.625rem;border-radius:8px;background:white;color:#176B32;font-size:10px;font-weight:700;text-decoration:none;box-shadow:0 1px 4px rgba(0,0,0,0.1);white-space:nowrap;flex-shrink:0;">
                            <i data-lucide="external-link" style="width:12px;height:12px;flex-shrink:0;"></i>
                            <span style="display:none;" class="md-inline">بوابة الخدمات</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;color:white;background:rgba(255,255,255,0.15);border:none;cursor:pointer;flex-shrink:0;" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="فتح القائمة" aria-controls="mobile-menu">
                        <svg x-show="!mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

        </div>

        
        <div id="mobile-menu" class="ih-mobile-menu" role="dialog" aria-modal="true" aria-label="القائمة الرئيسية"
             x-show="mobileOpen"
             x-transition:enter="transition transform ease-out duration-250"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition transform ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;">
                <div style="display:flex;align-items:center;gap:0.625rem;">
                    <div style="width:40px;height:40px;border-radius:12px;background:white;display:flex;align-items:center;justify-content:center;">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:28px;height:28px;object-fit:contain;">
                    </div>
                    <div>
                        <p style="font-weight:900;color:white;font-size:0.875rem;margin:0;"><?php echo e($municipalityName); ?></p>
                        <p style="font-size:10px;color:rgba(255,255,255,0.6);margin:0;"><?php echo e($municipalitySubtitle); ?></p>
                    </div>
                </div>
                <button @click="mobileOpen = false" style="width:36px;height:36px;border-radius:8px;color:white;background:rgba(255,255,255,0.12);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;" aria-label="إغلاق القائمة">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.25rem;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <a href="<?php echo e($item['url']); ?>" @click="mobileOpen = false" wire:navigate
                       style="display:block;padding:0.75rem 1rem;border-radius:12px;font-size:0.875rem;font-weight:600;text-decoration:none;<?php echo e($item['active'] ? 'color:white;background:rgba(255,255,255,0.1);' : 'color:rgba(255,255,255,0.9);'); ?>"><?php echo e($item['label']); ?></a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                <a href="<?php echo e(route('home') . '#contact'); ?>" @click="mobileOpen = false" wire:navigate style="display:block;padding:0.75rem 1rem;border-radius:12px;font-size:0.875rem;font-weight:600;color:rgba(255,255,255,0.9);text-decoration:none;">اتصل بنا</a>
            </div>

            <div style="padding-top:1.25rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;flex-direction:column;gap:0.625rem;margin-top:2rem;">
                <button style="width:100%;padding:0.75rem;border-radius:8px;font-size:0.875rem;font-weight:700;color:rgba(255,255,255,0.85);background:none;border:1px solid rgba(255,255,255,0.3);cursor:pointer;">EN</button>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                    <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.75rem;border-radius:8px;background:white;color:#176B32;font-size:0.875rem;font-weight:700;text-decoration:none;">
                        <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                        بوابة الخدمات
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="ih-overlay"></div>
</header>
<?php /**PATH C:\Users\ahmed\idna-org\resources\views/livewire/homepage/sections/header.blade.php ENDPATH**/ ?>