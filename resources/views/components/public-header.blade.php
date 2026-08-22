@props([
    'municipalityName' => '',
    'municipalitySubtitle' => '',
    'logoUrl' => null,
    'portalUrl' => '',
    'transparent' => false,
])

@php
    $currentRoute = request()->route()?->getName() ?? '';
    $isHome = $currentRoute === 'home';
    $isServices = str_starts_with($currentRoute, 'public.services.');
    $isAbout = str_starts_with($currentRoute, 'public.municipality.');
    $isCouncil = str_starts_with($currentRoute, 'public.council.');
    $isDepts = str_starts_with($currentRoute, 'public.departments.');
    $isFacilities = str_starts_with($currentRoute, 'public.facilities.');
    $isJobs = str_starts_with($currentRoute, 'public.jobs.');
    $isEngineering = str_starts_with($currentRoute, 'public.engineering-offices.');
    $isNews = str_starts_with($currentRoute, 'public.news.');
    $isAnnouncements = str_starts_with($currentRoute, 'public.announcements.');
    $isProjects = str_starts_with($currentRoute, 'public.projects.');
    $isTenders = str_starts_with($currentRoute, 'public.tenders.');
    $isWater = str_starts_with($currentRoute, 'public.water');
    $isOpenData = str_starts_with($currentRoute, 'public.open-data');
    $isComplaints = str_starts_with($currentRoute, 'public.complaints.');
    $isChatbot = $currentRoute === 'chatbot';

    $aboutActive = $isAbout;
    $councilActive = $isCouncil;
    $communityActive = $isNews || $isAnnouncements || $isProjects;
    $servicesActive = $isServices || $isFacilities || $isEngineering || $isWater || $isOpenData;

    $topBarPhone = null;
    $topBarEmail = null;
    try {
        $m = \App\Domains\Municipality\Models\Municipality::first();
        if ($m) {
            $topBarPhone = $m->contacts()->where('type', 'phone')->first()?->value;
            $topBarEmail = $m->contacts()->where('type', 'email')->first()?->value;
        }
    } catch (\Throwable $e) {}
@endphp

<header class="public-header {{ $transparent ? 'public-header--transparent' : '' }}"
        x-data="{ scrolled: false, mobileOpen: false, activeDropdown: null, mobileSection: null }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20, { passive: true })">

    {{-- Top Bar --}}
    <div class="ph-topbar">
        <div class="ph-container">
            <div class="ph-topbar-inner">
                <div class="ph-topbar-left">
                    @if ($topBarPhone)
                        <a href="tel:{{ $topBarPhone }}" class="ph-topbar-link">
                            <i data-lucide="phone" class="ph-icon-xs"></i>
                            <span>{{ $topBarPhone }}</span>
                        </a>
                    @endif
                    @if ($topBarEmail)
                        <a href="mailto:{{ $topBarEmail }}" class="ph-topbar-link ph-topbar-link--desktop">
                            <i data-lucide="mail" class="ph-icon-xs"></i>
                            <span>{{ $topBarEmail }}</span>
                        </a>
                    @endif
                </div>
                <div class="ph-topbar-right">
                    @if ($portalUrl)
                        <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="ph-topbar-link ph-topbar-link--desktop">
                            <i data-lucide="globe" class="ph-icon-xs"></i>
                            <span>البوابة الإلكترونية</span>
                        </a>
                        <span class="ph-topbar-divider ph-topbar-link--desktop"></span>
                    @endif
                    <button class="ph-topbar-btn">EN</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Nav --}}
    <div class="ph-navwrap" :class="scrolled ? 'ph-navwrap--scrolled' : ''">
        <div class="ph-container">

            {{-- ========== Desktop >= 1280px ========== --}}
            <div class="ph-desktop">
                <a href="{{ route('home') }}" wire:navigate class="ph-brand">
                    <div class="ph-brand-logo">
                        <img src="{{ $logoUrl ?? asset('logo.png') }}" alt="{{ $municipalityName }}">
                    </div>
                    <div class="ph-brand-text">
                        <span class="ph-brand-name">{{ $municipalityName }}</span>
                        @if ($municipalitySubtitle)
                            <span class="ph-brand-sub">{{ $municipalitySubtitle }}</span>
                        @endif
                    </div>
                </a>

                <nav class="ph-nav" aria-label="القائمة الرئيسية" dir="rtl">
                    <div class="ph-nav-inner">

                        {{-- الرئيسية --}}
                        <a href="{{ route('home') }}" wire:navigate class="ph-nav-link {{ $isHome ? 'ph-nav-link--active' : '' }}">الرئيسية</a>

                        {{-- الخدمات --}}
                        <div class="ph-dropdown-wrap" @mouseenter="activeDropdown = 'services'" @mouseleave="activeDropdown = null">
                            <button class="ph-nav-link {{ $servicesActive ? 'ph-nav-link--active' : '' }}" @click="activeDropdown = activeDropdown === 'services' ? null : 'services'">
                                الخدمات
                                <i data-lucide="chevron-down" class="ph-chevron" :class="activeDropdown === 'services' ? 'ph-chevron--open' : ''"></i>
                            </button>
                            <div x-show="activeDropdown === 'services'" x-cloak
                                 x-transition:enter="ph-dd-enter" x-transition:enter-start="ph-dd-enter-start" x-transition:enter-end="ph-dd-enter-end"
                                 x-transition:leave="ph-dd-leave" x-transition:leave-start="ph-dd-leave-start" x-transition:leave-end="ph-dd-leave-end"
                                 class="ph-dropdown" @click.away="activeDropdown = null">
                                <div class="ph-dropdown-inner">
                                    <a href="{{ route('public.services.index') }}" wire:navigate class="ph-dd-item {{ $isServices ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="layers" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">جميع الخدمات</span><span class="ph-dd-desc">الخدمات الإلكترونية المتاحة</span></div>
                                    </a>
                                    <a href="{{ route('public.facilities.index') }}" wire:navigate class="ph-dd-item {{ $isFacilities ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="building-2" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">المرافق العامة</span><span class="ph-dd-desc">مرافق بلدية إذنا العامة</span></div>
                                    </a>
                                    <a href="{{ route('public.engineering-offices.index') }}" wire:navigate class="ph-dd-item {{ $isEngineering ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="hard-hat" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">المكاتب الهندسية</span><span class="ph-dd-desc">المكاتب المعتمدة</span></div>
                                    </a>
                                    <a href="{{ route('public.water-schedule') }}" wire:navigate class="ph-dd-item {{ $isWater ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="droplets" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">جدول المياه</span><span class="ph-dd-desc">جدول توزيع المياه</span></div>
                                    </a>
                                    <a href="{{ route('public.departments.index') }}" wire:navigate class="ph-dd-item {{ $isDepts ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="folder-tree" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">الأقسام</span><span class="ph-dd-desc">دوائر وأقسام البلدية</span></div>
                                    </a>
                                    <div class="ph-dd-divider"></div>
                                    <a href="{{ route('public.open-data.index') }}" wire:navigate class="ph-dd-item {{ $isOpenData ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="database" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">البيانات المفتوحة</span><span class="ph-dd-desc">البيانات الرسمية</span></div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- عن البلدية --}}
                        <div class="ph-dropdown-wrap" @mouseenter="activeDropdown = 'about'" @mouseleave="activeDropdown = null">
                            <button class="ph-nav-link {{ $aboutActive || $councilActive ? 'ph-nav-link--active' : '' }}" @click="activeDropdown = activeDropdown === 'about' ? null : 'about'">
                                عن البلدية
                                <i data-lucide="chevron-down" class="ph-chevron" :class="activeDropdown === 'about' ? 'ph-chevron--open' : ''"></i>
                            </button>
                            <div x-show="activeDropdown === 'about'" x-cloak
                                 x-transition:enter="ph-dd-enter" x-transition:enter-start="ph-dd-enter-start" x-transition:enter-end="ph-dd-enter-end"
                                 x-transition:leave="ph-dd-leave" x-transition:leave-start="ph-dd-leave-start" x-transition:leave-end="ph-dd-leave-end"
                                 class="ph-dropdown" @click.away="activeDropdown = null">
                                <div class="ph-dropdown-inner">
                                    <a href="{{ route('public.municipality.about') }}" wire:navigate class="ph-dd-item {{ $aboutActive ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="info" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">نبذة عن البلدية</span><span class="ph-dd-desc">معلومات عامة</span></div>
                                    </a>
                                    <a href="{{ route('public.council.index') }}" wire:navigate class="ph-dd-item {{ $councilActive && !$isCouncil ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="users" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">أعضاء المجلس البلدي</span><span class="ph-dd-desc">╦قادة البلدية</span></div>
                                    </a>
                                    <a href="{{ route('public.council.decisions.index') }}" wire:navigate class="ph-dd-item {{ $isCouncil ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="file-text" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">قرارات المجلس</span><span class="ph-dd-desc">القرارات الرسمية</span></div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- المجتمع --}}
                        <div class="ph-dropdown-wrap" @mouseenter="activeDropdown = 'community'" @mouseleave="activeDropdown = null">
                            <button class="ph-nav-link {{ $communityActive ? 'ph-nav-link--active' : '' }}" @click="activeDropdown = activeDropdown === 'community' ? null : 'community'">
                                المجتمع
                                <i data-lucide="chevron-down" class="ph-chevron" :class="activeDropdown === 'community' ? 'ph-chevron--open' : ''"></i>
                            </button>
                            <div x-show="activeDropdown === 'community'" x-cloak
                                 x-transition:enter="ph-dd-enter" x-transition:enter-start="ph-dd-enter-start" x-transition:enter-end="ph-dd-enter-end"
                                 x-transition:leave="ph-dd-leave" x-transition:leave-start="ph-dd-leave-start" x-transition:leave-end="ph-dd-leave-end"
                                 class="ph-dropdown" @click.away="activeDropdown = null">
                                <div class="ph-dropdown-inner">
                                    <a href="{{ route('public.news.index') }}" wire:navigate class="ph-dd-item {{ $isNews ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="newspaper" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">الأخبار</span><span class="ph-dd-desc">آخر الأخبار المحلية</span></div>
                                    </a>
                                    <a href="{{ route('public.announcements.index') }}" wire:navigate class="ph-dd-item {{ $isAnnouncements ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="megaphone" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">الإعلانات</span><span class="ph-dd-desc">الإعلانات الرسمية</span></div>
                                    </a>
                                    <a href="{{ route('public.projects.index') }}" wire:navigate class="ph-dd-item {{ $isProjects ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="folder-kanban" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">المشاريع</span><span class="ph-dd-desc">المشاريع والمبادرات</span></div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- الفرص --}}
                        <div class="ph-dropdown-wrap" @mouseenter="activeDropdown = 'jobs'" @mouseleave="activeDropdown = null">
                            <button class="ph-nav-link {{ $isJobs || $isTenders ? 'ph-nav-link--active' : '' }}" @click="activeDropdown = activeDropdown === 'jobs' ? null : 'jobs'">
                                الفرص
                                <i data-lucide="chevron-down" class="ph-chevron" :class="activeDropdown === 'jobs' ? 'ph-chevron--open' : ''"></i>
                            </button>
                            <div x-show="activeDropdown === 'jobs'" x-cloak
                                 x-transition:enter="ph-dd-enter" x-transition:enter-start="ph-dd-enter-start" x-transition:enter-end="ph-dd-enter-end"
                                 x-transition:leave="ph-dd-leave" x-transition:leave-start="ph-dd-leave-start" x-transition:leave-end="ph-dd-leave-end"
                                 class="ph-dropdown" @click.away="activeDropdown = null">
                                <div class="ph-dropdown-inner">
                                    <a href="{{ route('public.jobs.index') }}" wire:navigate class="ph-dd-item {{ $isJobs ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="briefcase" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">الوظائف</span><span class="ph-dd-desc">الفرص الوظيفية المتاحة</span></div>
                                    </a>
                                    <a href="{{ route('public.tenders.index') }}" wire:navigate class="ph-dd-item {{ $isTenders ? 'ph-dd-item--active' : '' }}">
                                        <i data-lucide="scroll-text" class="ph-dd-icon"></i>
                                        <div><span class="ph-dd-label">المناقصات</span><span class="ph-dd-desc">المناقصات والمشتريات</span></div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- تواصل معنا --}}
                        <a href="{{ route('public.complaints.submit') }}" wire:navigate class="ph-nav-link {{ $isComplaints ? 'ph-nav-link--active' : '' }}">تواصل معنا</a>

                    </div>
                </nav>

                <div class="ph-actions">
                    <a href="{{ route('chatbot') }}" wire:navigate class="ph-bot-btn" aria-label="المساعد الذكي">
                        <i data-lucide="bot" class="ph-icon-sm"></i>
                    </a>
                    @if ($portalUrl)
                        <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="ph-portal-btn">
                            <i data-lucide="external-link" class="ph-icon-sm"></i>
                            <span>بوابة الخدمات</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- ========== Mobile < 1280px ========== --}}
            <div class="ph-mobile">
                <a href="{{ route('home') }}" wire:navigate class="ph-mobile-brand">
                    <div class="ph-mobile-logo">
                        <img src="{{ $logoUrl ?? asset('logo.png') }}" alt="{{ $municipalityName }}">
                    </div>
                    <div>
                        <p class="ph-mobile-name">{{ $municipalityName }}</p>
                        @if ($municipalitySubtitle)
                            <p class="ph-mobile-sub">{{ $municipalitySubtitle }}</p>
                        @endif
                    </div>
                </a>
                <div class="ph-mobile-actions">
                    @if ($portalUrl)
                        <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="ph-mobile-btn" style="width:auto;padding:0 0.625rem;display:inline-flex;align-items:center;gap:0.375rem;font-size:10px;font-weight:700;">
                            <i data-lucide="external-link" style="width:12px;height:12px;"></i>
                        </a>
                    @endif
                    <button class="ph-mobile-btn" @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen" aria-label="فتح القائمة">
                        <svg x-show="!mobileOpen" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="mobileOpen" x-cloak style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

        </div>

        {{-- Mobile Drawer --}}
        <div class="ph-drawer" x-show="mobileOpen"
             x-transition:enter="transition transform ease-out duration-250"
             x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition transform ease-in duration-200"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">
            <div class="ph-drawer-header">
                <div class="ph-drawer-brand">
                    <div class="ph-drawer-logo"><img src="{{ $logoUrl ?? asset('logo.png') }}" alt="{{ $municipalityName }}"></div>
                    <div>
                        <p class="ph-drawer-name">{{ $municipalityName }}</p>
                        <p class="ph-drawer-sub">{{ $municipalitySubtitle }}</p>
                    </div>
                </div>
                <button @click="mobileOpen = false" class="ph-drawer-close" aria-label="إغلاق">
                    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="ph-drawer-nav">
                <a href="{{ route('home') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-link {{ $isHome ? 'ph-drawer-link--active' : '' }}">الرئيسية</a>

                {{-- الخدمات --}}
                <button @click="mobileSection = mobileSection === 'services' ? null : 'services'" class="ph-drawer-section">
                    <span>الخدمات</span>
                    <i data-lucide="chevron-down" :style="mobileSection === 'services' ? 'transform:rotate(180deg)' : ''"></i>
                </button>
                <div x-show="mobileSection === 'services'" x-cloak class="ph-drawer-subnav" x-transition>
                    <a href="{{ route('public.services.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isServices ? 'ph-drawer-sublink--active' : '' }}">جميع الخدمات</a>
                    <a href="{{ route('public.facilities.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isFacilities ? 'ph-drawer-sublink--active' : '' }}">المرافق العامة</a>
                    <a href="{{ route('public.engineering-offices.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isEngineering ? 'ph-drawer-sublink--active' : '' }}">المكاتب الهندسية</a>
                    <a href="{{ route('public.water-schedule') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isWater ? 'ph-drawer-sublink--active' : '' }}">جدول المياه</a>
                    <a href="{{ route('public.departments.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isDepts ? 'ph-drawer-sublink--active' : '' }}">الأقسام</a>
                    <a href="{{ route('public.open-data.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isOpenData ? 'ph-drawer-sublink--active' : '' }}">البيانات المفتوحة</a>
                </div>

                {{-- عن البلدية --}}
                <button @click="mobileSection = mobileSection === 'about' ? null : 'about'" class="ph-drawer-section">
                    <span>عن البلدية</span>
                    <i data-lucide="chevron-down" :style="mobileSection === 'about' ? 'transform:rotate(180deg)' : ''"></i>
                </button>
                <div x-show="mobileSection === 'about'" x-cloak class="ph-drawer-subnav" x-transition>
                    <a href="{{ route('public.municipality.about') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $aboutActive ? 'ph-drawer-sublink--active' : '' }}">نبذة عن البلدية</a>
                    <a href="{{ route('public.council.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $councilActive ? 'ph-drawer-sublink--active' : '' }}">أعضاء المجلس</a>
                    <a href="{{ route('public.council.decisions.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isCouncil ? 'ph-drawer-sublink--active' : '' }}">قرارات المجلس</a>
                </div>

                {{-- المجتمع --}}
                <button @click="mobileSection = mobileSection === 'community' ? null : 'community'" class="ph-drawer-section">
                    <span>المجتمع</span>
                    <i data-lucide="chevron-down" :style="mobileSection === 'community' ? 'transform:rotate(180deg)' : ''"></i>
                </button>
                <div x-show="mobileSection === 'community'" x-cloak class="ph-drawer-subnav" x-transition>
                    <a href="{{ route('public.news.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isNews ? 'ph-drawer-sublink--active' : '' }}">الأخبار</a>
                    <a href="{{ route('public.announcements.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isAnnouncements ? 'ph-drawer-sublink--active' : '' }}">الإعلانات</a>
                    <a href="{{ route('public.projects.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isProjects ? 'ph-drawer-sublink--active' : '' }}">المشاريع</a>
                </div>

                {{-- الفرص --}}
                <button @click="mobileSection = mobileSection === 'jobs' ? null : 'jobs'" class="ph-drawer-section">
                    <span>الفرص</span>
                    <i data-lucide="chevron-down" :style="mobileSection === 'jobs' ? 'transform:rotate(180deg)' : ''"></i>
                </button>
                <div x-show="mobileSection === 'jobs'" x-cloak class="ph-drawer-subnav" x-transition>
                    <a href="{{ route('public.jobs.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isJobs ? 'ph-drawer-sublink--active' : '' }}">الوظائف</a>
                    <a href="{{ route('public.tenders.index') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-sublink {{ $isTenders ? 'ph-drawer-sublink--active' : '' }}">المناقصات</a>
                </div>

                <a href="{{ route('public.complaints.submit') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-link {{ $isComplaints ? 'ph-drawer-link--active' : '' }}">تواصل معنا</a>
            </div>

            <div class="ph-drawer-bottom">
                <a href="{{ route('chatbot') }}" @click="mobileOpen = false" wire:navigate class="ph-drawer-link" style="display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.12);justify-content:center;">
                    <i data-lucide="bot" style="width:16px;height:16px;"></i>
                    المساعد الذكي
                </a>
                <button class="ph-drawer-link" style="text-align:center;border:1px solid rgba(255,255,255,0.3);justify-content:center;">EN</button>
                @if ($portalUrl)
                    <a href="{{ $portalUrl }}" @click="mobileOpen = false" target="_blank" rel="noopener noreferrer" class="ph-drawer-link" style="background:white;color:#176B32;font-weight:700;justify-content:center;gap:0.5rem;">
                        <i data-lucide="external-link" style="width:14px;height:14px;"></i>
                        بوابة الخدمات
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Mobile overlay --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileOpen = false" class="ph-overlay"></div>
</header>
