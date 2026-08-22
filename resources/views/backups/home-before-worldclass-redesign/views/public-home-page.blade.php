@php
    $settings = $data['settings'] ?? [];
    $slides = $data['slides'] ?? [];
    $quickLinks = $data['quickLinks'] ?? [];
    $statistics = $data['statistics'] ?? [];
    $autoStatistics = $data['autoStatistics'] ?? [];
    $statisticsBg = $data['statisticsBg'] ?? null;
    $municipality = $data['municipality'] ?? null;
    $featuredServices = $data['featuredServices'] ?? [];
    $featuredDepartments = $data['featuredDepartments'] ?? [];
    $featuredFacilities = $data['facilities'] ?? [];
    $featuredCouncilMembers = $data['featuredCouncilMembers'] ?? [];
    $latestCouncilDecisions = $data['latestCouncilDecisions'] ?? [];
    $engineeringOffices = $data['engineeringOffices'] ?? [];
    $latestJobs = $data['latestJobs'] ?? [];
    $waterSchedule = $data['waterSchedule'] ?? [];
    $waterAreas = $data['waterAreas'] ?? [];
    $partnerLogos = $data['partnerLogos'] ?? [];
    $latestNews = $data['latestNews'] ?? [];
    $latestProjects = $data['latestProjects'] ?? [];
    $latestAnnouncements = $data['latestAnnouncements'] ?? [];
    $latestTenders = $data['latestTenders'] ?? [];
    $sections = $data['sections'] ?? [];
    $sectionKeys = $data['enabledSections'] ?? [];

    $municipalityName = $municipality['name_ar'] ?? $settings['site_title'] ?? 'بلدية إذنا';
    $municipalitySubtitle = $municipality['short_description'] ?? $settings['site_subtitle'] ?? '';
    $portalUrl = $settings['portal_url'] ?? 'https://portal.example.com';
    $primaryBtn = $settings['primary_button_text'] ?? 'الدخول إلى البوابة';
    $secondaryBtn = $settings['secondary_button_text'] ?? 'تعرف على البلدية';
    $secondaryBtnUrl = $settings['secondary_button_url'] ?? '#municipality-intro';

    $logoUrl = $municipality['logo_url'] ?? null;
    $contacts = $municipality['contacts'] ?? [];
    $socialPlatforms = $municipality['social_platforms'] ?? [];
    $externalPlatforms = $municipality['external_platforms'] ?? [];
    $businessHours = $municipality['business_hours'] ?? [];

    $publicServicesIndexUrl = Route::has('public.services.index') ? route('public.services.index') : $portalUrl;

    $mayor = $data['mayor'] ?? null;

    $sectionTitle = fn ($key) => collect($sections)->firstWhere('key', $key)['title'] ?? null;
    $sectionSubtitle = fn ($key) => collect($sections)->firstWhere('key', $key)['subtitle'] ?? null;
    $sectionSettings = fn ($key) => collect($sections)->firstWhere('key', $key)['settings'] ?? [];

    $formatDate = function ($date, string $format = 'Y-m-d'): string {
        if (empty($date)) return '';
        if (is_string($date)) {
            try { return \Carbon\Carbon::parse($date)->format($format); } catch (\Throwable) { return ''; }
        }
        if (is_object($date) && method_exists($date, 'format')) {
            try { return $date->format($format); } catch (\Throwable) { return ''; }
        }
        return '';
    };
@endphp

<div style="min-height:100vh;background:white;width:100%;max-width:100%;">

    {{-- ============================================ --}}
    {{-- HERO SECTION --}}
    {{-- ============================================ --}}
    @if (in_array('hero', $sectionKeys))
        @php
            $heroImg = !empty($slides) ? (!empty($slides[0]['image_path']) ? asset('storage/' . $slides[0]['image_path']) : (!empty($slides[0]['image_url']) ? $slides[0]['image_url'] : null)) : null;
        @endphp
        <section id="hero" class="relative overflow-hidden" style="min-height:500px;isolation:isolate;">
            {{-- Background image — z-index:0 --}}
            <div class="absolute inset-0" style="z-index:0;">
                @if ($heroImg)
                    <img src="{{ $heroImg }}" alt="منظر بانورامي لمدينة إدنا" class="w-full h-full object-cover" style="object-position:60% center;" fetchpriority="high">
                @else
                    <div class="w-full h-full" style="background:linear-gradient(135deg,#17243A,#1E2D4A,#2B3A5C);"></div>
                @endif
            </div>

            {{-- Gradient overlay — z-index:1 --}}
            <div class="absolute inset-0" style="z-index:1;background:linear-gradient(90deg,rgba(8,20,25,0.25),rgba(8,20,25,0.42),rgba(8,20,25,0.70));"></div>

            {{-- Decorative green curved shape — z-index:2, reduced width --}}
            <div class="hidden lg:block" style="position:absolute;right:-120px;top:-40px;width:280px;height:calc(100% + 80px);border-radius:50% 0 0 50%/45% 0 0 55%;background:linear-gradient(180deg,#1A7A3E 0%,#176B32 40%,#0F4F28 100%);opacity:0.85;pointer-events:none;z-index:2;"></div>

            {{-- Content safe area — z-index:3, padding-bottom for quick access overlap --}}
            <div class="relative w-full h-full flex items-center" style="z-index:3;padding-top:36px;padding-bottom:105px;">
                <div class="w-full">
                    <div style="width:100%;max-width:1280px;margin-left:auto;margin-right:auto;padding-left:clamp(16px,2.5vw,36px);padding-right:clamp(16px,2.5vw,36px);">
                        <div class="max-w-[500px] text-right" style="padding-inline:32px;">
                            {{-- Pill badge --}}
                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold text-white mb-5 shadow-lg" style="background:rgba(23,107,50,0.9);backdrop-filter:blur(8px);box-shadow:0 4px 16px rgba(23,107,50,0.35);">
                                <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                                <span>الموقع الرسمي</span>
                            </span>

                            {{-- Heading — reduced size --}}
                            <h1 class="text-white font-black leading-[1.15] mb-4" style="font-size:clamp(30px,4vw,52px);max-width:500px;text-shadow:0 2px 20px rgba(0,0,0,0.35);">
                                مرحباً بكم في
                                <br>
                                <span style="color:#A5D6A7;">{{ $municipalityName }}</span>
                            </h1>

                            {{-- Subtitle --}}
                            <p class="text-white/80 max-w-md mb-7 leading-relaxed" style="font-size:clamp(13px,1.3vw,15px);text-shadow:0 1px 8px rgba(0,0,0,0.2);">
                                {{ $municipality['short_description'] ?? 'نسعى لتقديم أفضل الخدمات البلدية بثقافة وشفافية من أجل مجتمع أفضل' }}
                            </p>

                            {{-- CTA Buttons — safe from quick access overlap --}}
                            <div class="flex flex-wrap items-center gap-3 mt-6">
                                @if ($portalUrl)
                                    <a href="{{ $portalUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 h-[48px] rounded-xl bg-white text-primary text-sm font-bold no-underline transition-all duration-300 shadow-lg hover:shadow-xl">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                        <span>{{ $primaryBtn }}</span>
                                    </a>
                                @endif
                                <a href="#contact" class="inline-flex items-center gap-2 px-6 py-3 h-[48px] rounded-xl text-white text-sm font-bold no-underline transition-all duration-300 border border-white/30" style="background:rgba(255,255,255,0.12);backdrop-filter:blur(8px);">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                                    <span>تواصل معنا</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                @media (max-width: 1024px) {
                    #hero .decorative-shape { width:200px !important; right:-80px !important; }
                }
                @media (max-width: 640px) {
                    #hero .hero-content-inner { padding-inline:16px !important; }
                }
                @media (prefers-reduced-motion:reduce) {
                    #hero * { transition-duration:0.01ms !important; }
                }
            </style>
        </section>
    @endif

    {{-- ============================================ --}}
    {{-- 1. QUICK ACCESS - متداخل مع الـHero --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('quick_links', $sectionKeys), 'livewire.homepage.sections.quick-access', [
        'quickLinks' => $quickLinks,
        'publicServicesIndexUrl' => $publicServicesIndexUrl,
        'portalUrl' => $portalUrl,
    ])

    {{-- ============================================ --}}
    {{-- 2. ELECTRONIC SERVICES --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('services', $sectionKeys), 'livewire.homepage.sections.services', [
        'featuredServices' => $featuredServices,
        'sectionTitle' => $sectionTitle('services'),
        'sectionSubtitle' => $sectionSubtitle('services'),
        'publicServicesIndexUrl' => $publicServicesIndexUrl,
        'portalUrl' => $portalUrl,
    ])

    {{-- ============================================ --}}
    {{-- 3. DEPARTMENTS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('departments', $sectionKeys), 'livewire.homepage.sections.departments', [
        'featuredDepartments' => $featuredDepartments,
        'sectionTitle' => $sectionTitle('departments'),
        'sectionSubtitle' => $sectionSubtitle('departments'),
    ])

    {{-- ============================================ --}}
    {{-- 4. PUBLIC FACILITIES --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('facilities', $sectionKeys), 'livewire.homepage.sections.facilities', [
        'featuredFacilities' => $featuredFacilities,
        'sectionTitle' => $sectionTitle('facilities'),
        'sectionSubtitle' => $sectionSubtitle('facilities'),
    ])

    {{-- ============================================ --}}
    {{-- 5. MUNICIPALITY STORY --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('municipality_intro', $sectionKeys) && !empty($municipality), 'livewire.homepage.sections.municipality-story', [
        'municipality' => $municipality,
        'municipalityName' => $municipalityName,
        'sectionTitle' => $sectionTitle('municipality_intro'),
        'sectionSubtitle' => $sectionSubtitle('municipality_intro'),
        'formatDate' => $formatDate,
    ])

    {{-- ============================================ --}}
    {{-- 6. COUNCIL MEMBERS + MAYOR --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('council_members', $sectionKeys), 'livewire.homepage.sections.council-members', [
        'featuredCouncilMembers' => $featuredCouncilMembers,
        'mayor' => $mayor,
        'municipalityName' => $municipalityName,
        'sectionTitle' => $sectionTitle('council_members'),
        'sectionSubtitle' => $sectionSubtitle('council_members'),
    ])

    {{-- ============================================ --}}
    {{-- 7. COUNCIL DECISIONS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('council_decisions', $sectionKeys), 'livewire.homepage.sections.council-decisions', [
        'latestCouncilDecisions' => $latestCouncilDecisions,
        'sectionTitle' => $sectionTitle('council_decisions'),
        'sectionSubtitle' => $sectionSubtitle('council_decisions'),
        'formatDate' => $formatDate,
    ])

    {{-- ============================================ --}}
    {{-- 8. WATER SCHEDULE --}}
    {{-- ============================================ --}}
    @includeWhen(!empty($waterSchedule), 'livewire.homepage.sections.water-status', [
        'waterSchedule' => $waterSchedule,
        'waterAreas' => $waterAreas,
    ])

    {{-- ============================================ --}}
    {{-- 9. JOBS + ENGINEERING OFFICES (عمودين) --}}
    {{-- ============================================ --}}
    @includeWhen(!empty($latestJobs) || (!empty($engineeringOffices) && in_array('engineering_offices', $sectionKeys)), 'livewire.homepage.sections.jobs', [
        'latestJobs' => $latestJobs,
        'engineeringOffices' => $engineeringOffices,
        'sectionTitle' => $sectionTitle('jobs'),
        'sectionSubtitle' => $sectionSubtitle('jobs'),
    ])

    {{-- ============================================ --}}
    {{-- 10. LATEST NEWS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('latest_news', $sectionKeys), 'livewire.homepage.sections.news', [
        'latestNews' => $latestNews,
        'latestAnnouncements' => $latestAnnouncements,
        'sectionTitle' => $sectionTitle('latest_news'),
        'sectionSubtitle' => $sectionSubtitle('latest_news'),
    ])

    {{-- ============================================ --}}
    {{-- 11. PROJECTS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('projects', $sectionKeys), 'livewire.homepage.sections.projects', [
        'latestProjects' => $latestProjects,
        'sectionTitle' => $sectionTitle('projects'),
        'sectionSubtitle' => $sectionSubtitle('projects'),
    ])

    {{-- ============================================ --}}
    {{-- 12. TENDERS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('tenders', $sectionKeys), 'livewire.homepage.sections.tenders', [
        'latestTenders' => $latestTenders,
        'sectionTitle' => $sectionTitle('tenders'),
        'sectionSubtitle' => $sectionSubtitle('tenders'),
    ])

    {{-- ============================================ --}}
    {{-- FACEBOOK FEED — آخر أخبار البلدية من فيسبوك --}}
    {{-- ============================================ --}}
    @include('livewire.homepage.sections.facebook-feed')

    {{-- ============================================ --}}
    {{-- 13. STATISTICS --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('statistics', $sectionKeys) && (!empty($statistics) || !empty($autoStatistics)), 'livewire.homepage.sections.statistics', [
        'statistics' => $statistics,
        'autoStatistics' => $autoStatistics,
        'statisticsBg' => $statisticsBg,
        'sectionTitle' => $sectionTitle('statistics'),
        'sectionSubtitle' => $sectionSubtitle('statistics'),
    ])

    {{-- ============================================ --}}
    {{-- PARTNERS --}}
    {{-- ============================================ --}}
    @includeWhen(!empty($partnerLogos), 'livewire.homepage.sections.partners', [
        'partnerLogos' => $partnerLogos,
    ])

    {{-- ============================================ --}}
    {{-- 14. CONTACT CTA --}}
    {{-- ============================================ --}}
    @includeWhen(in_array('contact_cta', $sectionKeys), 'livewire.homepage.sections.contact-cta', [
        'settings' => $settings,
        'portalUrl' => $portalUrl,
        'primaryBtn' => $primaryBtn,
        'contacts' => $contacts,
        'businessHours' => $businessHours,
        'municipality' => $municipality,
    ])

</div>
