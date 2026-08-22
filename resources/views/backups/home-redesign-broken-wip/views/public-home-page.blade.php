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
    @includeWhen(in_array('hero', $sectionKeys), 'components.home.hero', [
        'slides' => $slides,
        'settings' => $settings,
        'municipalityName' => $municipalityName,
        'municipality' => $municipality,
        'portalUrl' => $portalUrl,
        'primaryBtn' => $primaryBtn,
        'secondaryBtn' => $secondaryBtn,
        'secondaryBtnUrl' => $secondaryBtnUrl,
    ])

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
        'sectionTitle' => 'جدول توزيع المياه',
        'sectionSubtitle' => null,
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
    @includeWhen(in_array('statistics', $sectionKeys) && (!empty($statistics) || !empty($autoStatistics)), 'components.home.statistics', [
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
