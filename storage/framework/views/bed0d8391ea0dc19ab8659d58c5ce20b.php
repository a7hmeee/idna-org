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

    $isActive = fn (string $route) => request()->routeIs($route);

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

<style>
/* ================================================================
   HEADER — 3-column CSS Grid
   RTL: [Brand 260px] [Nav 1fr] [Actions auto]
   Each column is locked. No overlap possible.
   ================================================================ */

/* --- Sticky wrapper --- */
.ih-header { position: sticky; top: 0; z-index: 25; width: 100%; }

/* --- Top bar --- */
.ih-topbar { background: #17243A; height: 32px; }

/* --- Green bar scroll effect --- */
.ih-navwrap { transition: background 0.3s ease, box-shadow 0.3s ease; }

/* ================================================================
   DESKTOP ≥ 1280px — CSS Grid 3 columns
   ================================================================ */
.ih-desktop { display: none; }
@media (min-width: 1280px) {
    .ih-desktop {
        display: grid;
        grid-template-columns: 260px 1fr auto;
        align-items: center;
        min-height: 72px;
        column-gap: 0.5rem;
    }
}

/* --- COL 1: BRAND (fixed 260px, z-index above everything) --- */
.ih-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    width: 260px;
    min-width: 260px;
    max-width: 260px;
    overflow: hidden;
    text-decoration: none;
    z-index: 2;
}
.ih-brand-logo {
    width: 46px; height: 46px; min-width: 46px; min-height: 46px;
    border-radius: 12px;
    background: white;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    overflow: hidden;
}
.ih-brand-logo img { width: 36px; height: 36px; object-fit: contain; display: block; }
.ih-brand-text { min-width: 0; overflow: hidden; }
.ih-brand-name {
    font-weight: 900; color: white; font-size: 1rem; line-height: 1.25; margin: 0;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.ih-brand-sub {
    font-size: 10px; color: rgba(255,255,255,0.7); font-weight: 500;
    margin: 2px 0 0 0; line-height: 1.3;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* --- COL 2: NAVIGATION (fills middle, grid handles bounds) --- */
.ih-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 0;
}
.ih-nav-inner {
    display: flex;
    align-items: center;
    gap: 0;
    flex-wrap: nowrap;
}

/* --- Nav links --- */
.ih-nav-link {
    padding: 0.5rem 0.6rem;
    border: none;
    border-bottom: 2px solid transparent;
    font-size: 13px; font-weight: 600;
    white-space: nowrap;
    text-decoration: none;
    color: rgba(255,255,255,0.85);
    transition: color 0.2s, border-color 0.2s;
    cursor: pointer; background: none;
    display: inline-flex; align-items: center; gap: 0.2rem;
    font-family: inherit; line-height: 1.4;
}
.ih-nav-link:hover { color: white; border-bottom-color: rgba(255,255,255,0.4); }
.ih-nav-link--active { color: white; border-bottom-color: white; }

/* --- Chevron --- */
.ih-chevron { width: 11px; height: 11px; transition: transform 0.2s; flex-shrink: 0; }
.ih-chevron--open { transform: rotate(180deg); }

/* --- COL 3: ACTIONS (fixed right side) --- */
.ih-actions {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    flex-shrink: 0;
}

/* --- Login button --- */
.ih-login-btn {
    display: inline-flex; align-items: center; gap: 0.3rem;
    padding: 0.45rem 0.85rem; border-radius: 8px;
    border: 1.5px solid rgba(255,255,255,0.35); color: white;
    font-size: 12px; font-weight: 600; text-decoration: none;
    white-space: nowrap; transition: all 0.2s; flex-shrink: 0;
    background: transparent; cursor: pointer; font-family: inherit;
}
.ih-login-btn:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.6); }

/* --- Portal button --- */
.ih-portal-btn {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.5rem 1rem; border-radius: 10px;
    background: white; color: #176B32; font-size: 12px; font-weight: 700;
    text-decoration: none; white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    transition: box-shadow 0.3s; flex-shrink: 0;
}
.ih-portal-btn:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.25); }

/* ================================================================
   DROPDOWNS
   ================================================================ */
.ih-dd { position: relative; }
.ih-dd-menu {
    position: absolute; top: calc(100% + 6px); right: 0;
    min-width: 230px;
    background: white; border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.04);
    padding: 0.4rem; z-index: 100;
    opacity: 0; transform: translateY(4px);
    pointer-events: none;
    transition: opacity 0.15s, transform 0.15s;
}
.ih-dd-menu.is-open {
    opacity: 1; transform: translateY(0); pointer-events: auto;
}
.ih-dd-item {
    display: flex; align-items: center; gap: 0.5rem;
    padding: 0.5rem 0.85rem; border-radius: 8px;
    text-decoration: none; font-size: 13px; font-weight: 600;
    color: #374151; transition: background 0.12s;
    cursor: pointer; white-space: nowrap;
}
.ih-dd-item:hover { background: #f0fdf4; }
.ih-dd-item--active { background: #f0fdf4; color: #176B32; }
.ih-dd-icon { width: 16px; height: 16px; color: #176B32; flex-shrink: 0; }
.ih-dd-sep { height: 1px; background: #e5e7eb; margin: 0.35rem 0; }
.ih-dd-heading {
    padding: 0.4rem 0.85rem 0.2rem;
    font-size: 10px; font-weight: 700;
    color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;
}
.ih-dd-subitem { padding-right: 1rem; }
.ih-dd-subitem a {
    display: block; padding: 0.5rem 0.85rem; border-radius: 6px;
    font-size: 12.5px; font-weight: 500; color: #6b7280;
    text-decoration: none; transition: background 0.12s, color 0.12s;
}
.ih-dd-subitem a:hover { background: #f0fdf4; color: #374151; }
.ih-dd-subitem a.ih-dd-item--active { color: #176B32; background: #f0fdf4; }

/* ================================================================
   TABLET 1024–1279
   ================================================================ */
.ih-tablet { display: none; }
@media (min-width: 1024px) and (max-width: 1279.98px) {
    .ih-tablet {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        min-height: 56px;
        column-gap: 0.5rem;
    }
}

/* ================================================================
   MOBILE < 1024
   ================================================================ */
.ih-mobile { display: none; }
@media (max-width: 1023.98px) {
    .ih-mobile {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 56px;
    }
}

/* --- Mobile drawer --- */
.ih-mobile-menu {
    position: fixed; top: 0; left: 0;
    width: 85%; max-width: min(360px, calc(100vw - 24px));
    height: 100vh; overflow-y: auto; z-index: 50;
    background: rgba(23, 107, 50, 0.98);
    backdrop-filter: blur(8px);
    border-left: 1px solid rgba(255,255,255,0.1);
    padding: 1.5rem; box-shadow: 0 25px 50px rgba(0,0,0,0.3);
}
.ih-mob-link {
    display: block; padding: 0.75rem 1rem; border-radius: 12px;
    font-size: 0.875rem; font-weight: 600; text-decoration: none;
    transition: background 0.15s;
}
.ih-mob-link--active { color: white; background: rgba(255,255,255,0.1); }
.ih-mob-link:not(.ih-mob-link--active) { color: rgba(255,255,255,0.9); }
.ih-mob-section-btn {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; padding: 0.75rem 1rem; border-radius: 12px;
    font-size: 0.875rem; font-weight: 600; color: rgba(255,255,255,0.9);
    background: none; border: none; cursor: pointer; font-family: inherit;
    text-align: right;
}
.ih-mob-section-btn svg { width: 16px; height: 16px; transition: transform 0.2s; flex-shrink: 0; }
.ih-mob-sub { padding-right: 1rem; }
.ih-mob-sub a {
    display: block; padding: 0.55rem 0.85rem; border-radius: 8px;
    font-size: 0.8rem; font-weight: 500; text-decoration: none;
    color: rgba(255,255,255,0.75); transition: background 0.12s;
}
.ih-mob-sub a:hover { background: rgba(255,255,255,0.08); }
.ih-mob-sub a.ih-mob-link--active { color: white; background: rgba(255,255,255,0.1); }

/* --- Overlay --- */
.ih-overlay { position: fixed; inset: 0; z-index: 40; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }

/* --- Responsive utilities --- */
@media (min-width: 640px) { .sm-flex { display: flex !important; } .sm-block { display: block !important; } }
@media (min-width: 768px) { .md-flex { display: flex !important; } .md-inline { display: inline !important; } }
</style>

<header class="ih-header"
        x-data="{ scrolled: false, mobileOpen: false, mobileSection: null }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })">

    
    <div class="ih-topbar" style="display:flex;align-items:center;">
        <div class="container-home" style="width:100%;height:100%;">
            <div style="display:flex;align-items:center;justify-content:space-between;height:100%;">
                <div style="display:flex;align-items:center;gap:0.75rem;overflow:hidden;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topBarPhone): ?>
                        <a href="tel:<?php echo e($topBarPhone); ?>" style="display:flex;align-items:center;gap:0.25rem;color:rgba(255,255,255,0.75);text-decoration:none;white-space:nowrap;padding:0.15rem 0;">
                            <i data-lucide="phone" style="width:11px;height:11px;flex-shrink:0;"></i>
                            <span style="font-size:11px;"><?php echo e($topBarPhone); ?></span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($topBarEmail): ?>
                        <a href="mailto:<?php echo e($topBarEmail); ?>" style="display:none;align-items:center;gap:0.25rem;color:rgba(255,255,255,0.75);text-decoration:none;white-space:nowrap;" class="md-flex">
                            <i data-lucide="mail" style="width:11px;height:11px;flex-shrink:0;"></i>
                            <span style="font-size:11px;"><?php echo e($topBarEmail); ?></span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div style="display:flex;align-items:center;gap:0.5rem;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" style="display:none;align-items:center;gap:0.25rem;color:rgba(255,255,255,0.75);text-decoration:none;white-space:nowrap;" class="sm-flex">
                            <i data-lucide="globe" style="width:11px;height:11px;flex-shrink:0;"></i>
                            <span style="font-size:11px;">البوابة الإلكترونية</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="ih-navwrap" :style="scrolled ? 'background:rgba(23,107,50,0.97);backdrop-filter:blur(16px);box-shadow:0 4px 30px rgba(0,0,0,0.3)' : 'background:linear-gradient(to bottom,rgba(23,107,50,0.95),rgba(23,107,50,0.85))'">
        <div class="container-home">

            
            <div class="ih-desktop">

                
                <a href="<?php echo e(route('home')); ?>" wire:navigate class="ih-brand">
                    <div class="ih-brand-logo">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" onerror="this.onerror=null;this.src='<?php echo e(asset('logo.png')); ?>';">
                    </div>
                    <div class="ih-brand-text">
                        <p class="ih-brand-name"><?php echo e($municipalityName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipalitySubtitle): ?>
                            <p class="ih-brand-sub"><?php echo e($municipalitySubtitle); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>

                
                <nav class="ih-nav" aria-label="القائمة الرئيسية">
                    <div class="ih-nav-inner">

                        <a href="<?php echo e(route('home')); ?>" wire:navigate
                           class="ih-nav-link <?php echo e($isActive('home') ? 'ih-nav-link--active' : ''); ?>">الرئيسية</a>

                        <a href="<?php echo e(route('public.municipality.about')); ?>" wire:navigate
                           class="ih-nav-link <?php echo e($isActive('public.municipality.about') ? 'ih-nav-link--active' : ''); ?>">عن البلدية</a>

                        
                        <div class="ih-dd" x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="ih-nav-link <?php echo e($isActive('public.services.*') ? 'ih-nav-link--active' : ''); ?>" @click="open = !open" :aria-expanded="open">
                                الخدمات
                                <svg class="ih-chevron" :class="open ? 'ih-chevron--open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="ih-dd-menu" :class="open ? 'is-open' : ''">
                                <a href="<?php echo e(route('public.services.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.services.*') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                    جميع الخدمات
                                </a>
                                <a href="<?php echo e(route('public.services.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.services.category') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    تصنيفات الخدمات
                                </a>
                            </div>
                        </div>

                        
                        <div class="ih-dd" x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="ih-nav-link <?php echo e($isActive('public.council.*') ? 'ih-nav-link--active' : ''); ?>" @click="open = !open" :aria-expanded="open">
                                المجلس البلدي
                                <svg class="ih-chevron" :class="open ? 'ih-chevron--open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="ih-dd-menu" :class="open ? 'is-open' : ''">
                                <a href="<?php echo e(route('public.council.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.council.index') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4-4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                                    أعضاء المجلس
                                </a>
                                <a href="<?php echo e(route('public.council.decisions.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.council.decisions.*') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    قرارات المجلس
                                </a>
                            </div>
                        </div>

                        <a href="<?php echo e(route('public.departments.index')); ?>" wire:navigate
                           class="ih-nav-link <?php echo e($isActive('public.departments.*') ? 'ih-nav-link--active' : ''); ?>">الأقسام</a>

                        <a href="<?php echo e(route('public.facilities.index')); ?>" wire:navigate
                           class="ih-nav-link <?php echo e($isActive('public.facilities.*') ? 'ih-nav-link--active' : ''); ?>">المرافق العامة</a>

                        <a href="<?php echo e(route('public.jobs.index')); ?>" wire:navigate
                           class="ih-nav-link <?php echo e($isActive('public.jobs.*') ? 'ih-nav-link--active' : ''); ?>">الوظائف</a>

                        
                        <div class="ih-dd" x-data="{ open: false }" @click.away="open = false" @keydown.escape.window="open = false" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="ih-nav-link" @click="open = !open" :aria-expanded="open">
                                المزيد
                                <svg class="ih-chevron" :class="open ? 'ih-chevron--open' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="ih-dd-menu" :class="open ? 'is-open' : ''" style="min-width:240px;">
                                <a href="<?php echo e(route('public.engineering-offices.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.engineering-offices.*') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    المكاتب الهندسية
                                </a>
                                <div class="ih-dd-sep"></div>
                                <div class="ih-dd-heading">الأخبار والإعلانات</div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.news.index')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.news.*') ? 'ih-dd-item--active' : ''); ?>">الأخبار</a>
                                </div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.announcements.index')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.announcements.*') ? 'ih-dd-item--active' : ''); ?>">الإعلانات</a>
                                </div>
                                <div class="ih-dd-sep"></div>
                                <div class="ih-dd-heading">المشاريع والمناقصات</div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.projects.index')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.projects.*') ? 'ih-dd-item--active' : ''); ?>">المشاريع</a>
                                </div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.tenders.index')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.tenders.*') ? 'ih-dd-item--active' : ''); ?>">المناقصات</a>
                                </div>
                                <div class="ih-dd-sep"></div>
                                <a href="<?php echo e(route('public.water-schedule')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.water-schedule') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    جدول المياه
                                </a>
                                <a href="<?php echo e(route('public.open-data.index')); ?>" wire:navigate @click="open = false" class="ih-dd-item <?php echo e($isActive('public.open-data.*') ? 'ih-dd-item--active' : ''); ?>">
                                    <svg class="ih-dd-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/></svg>
                                    البيانات المفتوحة
                                </a>
                                <div class="ih-dd-sep"></div>
                                <div class="ih-dd-heading">الشكاوى</div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.complaints.submit')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.complaints.submit') ? 'ih-dd-item--active' : ''); ?>">تقديم شكوى</a>
                                </div>
                                <div class="ih-dd-subitem">
                                    <a href="<?php echo e(route('public.complaints.track')); ?>" wire:navigate @click="open = false" class="<?php echo e($isActive('public.complaints.track') ? 'ih-dd-item--active' : ''); ?>">تتبع شكوى</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </nav>

                
                <div class="ih-actions">
                    <a href="<?php echo e(route('login')); ?>" wire:navigate class="ih-login-btn">
                        الدخول
                    </a>
                </div>

            </div>

            
            <div class="ih-tablet">
                
                <a href="<?php echo e(route('home')); ?>" wire:navigate style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;flex-shrink:0;">
                    <div style="width:36px;height:36px;border-radius:10px;background:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:28px;height:28px;object-fit:contain;display:block;" onerror="this.onerror=null;this.src='<?php echo e(asset('logo.png')); ?>';">
                    </div>
                    <p style="font-weight:900;color:white;font-size:0.85rem;line-height:1.25;margin:0;white-space:nowrap;"><?php echo e($municipalityName); ?></p>
                </a>

                
                <nav style="display:flex;align-items:center;gap:0.125rem;min-width:0;overflow:hidden;" aria-label="القائمة الرئيسية">
                    <?php
                        $tabletItems = [
                            ['label' => 'الرئيسية', 'route' => 'home'],
                            ['label' => 'الخدمات', 'route' => 'public.services.index'],
                            ['label' => 'الأقسام', 'route' => 'public.departments.index'],
                            ['label' => 'المرافق', 'route' => 'public.facilities.index'],
                            ['label' => 'الوظائف', 'route' => 'public.jobs.index'],
                        ];
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $tabletItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <a href="<?php echo e(route($item['route'])); ?>" wire:navigate
                           style="padding:0.375rem;border-bottom:2px solid <?php echo e($isActive($item['route']) ? 'white' : 'transparent'); ?>;font-size:11px;font-weight:600;white-space:nowrap;text-decoration:none;flex-shrink:0;color:<?php echo e($isActive($item['route']) ? 'white' : 'rgba(255,255,255,0.85)'); ?>;"><?php echo e($item['label']); ?></a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    <button @click="mobileOpen = true"
                            style="padding:0.375rem;border-bottom:2px solid transparent;font-size:11px;font-weight:600;color:rgba(255,255,255,0.85);white-space:nowrap;background:none;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:0.25rem;">
                        <span>المزيد</span>
                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </nav>

                
                <div style="display:flex;align-items:center;gap:0.5rem;flex-shrink:0;">
                    <a href="<?php echo e(route('login')); ?>" wire:navigate
                       style="padding:0.375rem 0.625rem;border-radius:8px;border:1.5px solid rgba(255,255,255,0.3);color:white;font-size:10px;font-weight:600;text-decoration:none;white-space:nowrap;flex-shrink:0;background:transparent;cursor:pointer;">
                        الدخول
                    </a>
                    <button style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;color:white;background:rgba(255,255,255,0.15);border:none;cursor:pointer;flex-shrink:0;" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="فتح القائمة">
                        <svg x-show="!mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            
            <div class="ih-mobile">
                <a href="<?php echo e(route('home')); ?>" wire:navigate style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;flex:1;min-width:0;overflow:hidden;">
                    <div style="width:38px;height:38px;border-radius:10px;background:white;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:30px;height:30px;object-fit:contain;display:block;" onerror="this.onerror=null;this.src='<?php echo e(asset('logo.png')); ?>';">
                    </div>
                    <div style="min-width:0;overflow:hidden;">
                        <p style="font-weight:900;color:white;font-size:0.82rem;line-height:1.2;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($municipalityName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($municipalitySubtitle): ?>
                            <p style="font-size:9px;color:rgba(255,255,255,0.7);font-weight:500;margin:1px 0 0 0;line-height:1.15;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php echo e($municipalitySubtitle); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </a>
                <div style="display:flex;align-items:center;gap:0.3rem;flex-shrink:0;">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                        <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer"
                           style="display:inline-flex;align-items:center;gap:0.2rem;padding:0.3rem 0.5rem;border-radius:7px;background:white;color:#176B32;font-size:9px;font-weight:700;text-decoration:none;box-shadow:0 1px 4px rgba(0,0,0,0.1);white-space:nowrap;flex-shrink:0;">
                            <i data-lucide="external-link" style="width:11px;height:11px;flex-shrink:0;"></i>
                            <span>بوابة الخدمات</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:8px;color:white;background:rgba(255,255,255,0.15);border:none;cursor:pointer;flex-shrink:0;" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="فتح القائمة">
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
                    <div style="width:40px;height:40px;border-radius:12px;background:white;display:flex;align-items:center;justify-content:center;overflow:hidden;">
                        <img src="<?php echo e($logoUrl ?? asset('logo.png')); ?>" alt="<?php echo e($municipalityName); ?>" style="width:34px;height:34px;object-fit:contain;display:block;" onerror="this.onerror=null;this.src='<?php echo e(asset('logo.png')); ?>';">
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
                <a href="<?php echo e(route('home')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('home') ? 'ih-mob-link--active' : ''); ?>">الرئيسية</a>
                <a href="<?php echo e(route('public.municipality.about')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('public.municipality.about') ? 'ih-mob-link--active' : ''); ?>">عن البلدية</a>

                <button class="ih-mob-section-btn" @click="mobileSection = mobileSection === 'services' ? null : 'services'">
                    <span>الخدمات</span>
                    <svg :style="mobileSection === 'services' ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="ih-mob-sub" x-show="mobileSection === 'services'" x-cloak x-transition>
                    <a href="<?php echo e(route('public.services.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.services.*') ? 'ih-mob-link--active' : ''); ?>">جميع الخدمات</a>
                    <a href="<?php echo e(route('public.services.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.services.category') ? 'ih-mob-link--active' : ''); ?>">تصنيفات الخدمات</a>
                </div>

                <button class="ih-mob-section-btn" @click="mobileSection = mobileSection === 'council' ? null : 'council'">
                    <span>المجلس البلدي</span>
                    <svg :style="mobileSection === 'council' ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="ih-mob-sub" x-show="mobileSection === 'council'" x-cloak x-transition>
                    <a href="<?php echo e(route('public.council.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.council.index') ? 'ih-mob-link--active' : ''); ?>">أعضاء المجلس</a>
                    <a href="<?php echo e(route('public.council.decisions.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.council.decisions.*') ? 'ih-mob-link--active' : ''); ?>">قرارات المجلس</a>
                </div>

                <a href="<?php echo e(route('public.departments.index')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('public.departments.*') ? 'ih-mob-link--active' : ''); ?>">الأقسام</a>
                <a href="<?php echo e(route('public.facilities.index')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('public.facilities.*') ? 'ih-mob-link--active' : ''); ?>">المرافق العامة</a>
                <a href="<?php echo e(route('public.jobs.index')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('public.jobs.*') ? 'ih-mob-link--active' : ''); ?>">الوظائف</a>
                <a href="<?php echo e(route('public.engineering-offices.index')); ?>" @click="mobileOpen = false" wire:navigate class="ih-mob-link <?php echo e($isActive('public.engineering-offices.*') ? 'ih-mob-link--active' : ''); ?>">المكاتب الهندسية</a>

                <button class="ih-mob-section-btn" @click="mobileSection = mobileSection === 'news' ? null : 'news'">
                    <span>الأخبار والإعلانات</span>
                    <svg :style="mobileSection === 'news' ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="ih-mob-sub" x-show="mobileSection === 'news'" x-cloak x-transition>
                    <a href="<?php echo e(route('public.news.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.news.*') ? 'ih-mob-link--active' : ''); ?>">الأخبار</a>
                    <a href="<?php echo e(route('public.announcements.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.announcements.*') ? 'ih-mob-link--active' : ''); ?>">الإعلانات</a>
                </div>

                <button class="ih-mob-section-btn" @click="mobileSection = mobileSection === 'projects' ? null : 'projects'">
                    <span>المشاريع والمناقصات</span>
                    <svg :style="mobileSection === 'projects' ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="ih-mob-sub" x-show="mobileSection === 'projects'" x-cloak x-transition>
                    <a href="<?php echo e(route('public.projects.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.projects.*') ? 'ih-mob-link--active' : ''); ?>">المشاريع</a>
                    <a href="<?php echo e(route('public.tenders.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.tenders.*') ? 'ih-mob-link--active' : ''); ?>">المناقصات</a>
                </div>

                <button class="ih-mob-section-btn" @click="mobileSection = mobileSection === 'more' ? null : 'more'">
                    <span>المزيد</span>
                    <svg :style="mobileSection === 'more' ? 'transform:rotate(180deg)' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="ih-mob-sub" x-show="mobileSection === 'more'" x-cloak x-transition>
                    <a href="<?php echo e(route('public.water-schedule')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.water-schedule') ? 'ih-mob-link--active' : ''); ?>">جدول المياه</a>
                    <a href="<?php echo e(route('public.open-data.index')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.open-data.*') ? 'ih-mob-link--active' : ''); ?>">البيانات المفتوحة</a>
                    <div style="height:1px;background:rgba(255,255,255,0.1);margin:0.25rem 0;"></div>
                    <div style="padding:0.3rem 0.85rem;font-size:10px;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.05em;">الشكاوى</div>
                    <a href="<?php echo e(route('public.complaints.submit')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.complaints.submit') ? 'ih-mob-link--active' : ''); ?>">تقديم شكوى</a>
                    <a href="<?php echo e(route('public.complaints.track')); ?>" @click="mobileOpen = false" wire:navigate class="<?php echo e($isActive('public.complaints.track') ? 'ih-mob-link--active' : ''); ?>">تتبع شكوى</a>
                </div>
            </div>

            <div style="padding-top:1.25rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;flex-direction:column;gap:0.625rem;margin-top:2rem;">
                <a href="<?php echo e(route('login')); ?>" wire:navigate @click="mobileOpen = false"
                   style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.75rem;border-radius:8px;border:1.5px solid rgba(255,255,255,0.3);color:white;font-size:0.875rem;font-weight:700;text-decoration:none;background:transparent;">
                    <i data-lucide="log-in" style="width:16px;height:16px;"></i>
                    تسجيل الدخول
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portalUrl): ?>
                    <a href="<?php echo e($portalUrl); ?>" target="_blank" rel="noopener noreferrer" @click="mobileOpen = false"
                       style="width:100%;display:inline-flex;align-items:center;justify-content:center;gap:0.5rem;padding:0.75rem;border-radius:8px;background:white;color:#176B32;font-size:0.875rem;font-weight:700;text-decoration:none;">
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