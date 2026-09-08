<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Domains\Footer\Models\FooterItem;
use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
use App\Domains\Navigation\Models\NavigationItem;
use App\Domains\Seo\Models\SeoSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

final class PublicLayoutComposer
{
    public function compose(View $view): void
    {
        $repo = app(HomepagePublicRepositoryInterface::class);
        $data = $repo->getHomePageData();

        $settings = $data['settings'] ?? [];
        $municipality = $data['municipality'] ?? [];

        $municipalityName = $municipality['name_ar'] ?? $settings['site_title'] ?? 'بلدية إذنا';
        $portalUrl = $settings['portal_url'] ?? null;

        // Navigation items (cached)
        $navigationItems = Cache::remember('navigation.main', 600, function (): array {
            return NavigationItem::where('is_active', true)
                ->where('section', 'main')
                ->whereNull('parent_id')
                ->with(['activeChildren' => function ($query) {
                    $query->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get()
                ->toArray();
        });

        // Footer items (cached)
        $footerItems = Cache::remember('footer.items', 600, function (): array {
            return FooterItem::where('is_active', true)
                ->orderBy('column_key')
                ->orderBy('sort_order')
                ->get()
                ->toArray();
        });

        // SEO settings for current page
        $currentPageKey = $this->getCurrentPageKey();
        $seo = SeoSetting::getForPageWithFallback($currentPageKey);

        $view->with([
            'municipalityName' => $municipalityName,
            'municipalitySubtitle' => $municipality['short_description'] ?? $settings['site_subtitle'] ?? '',
            'municipality' => $municipality,
            'logoUrl' => $municipality['logo_url'] ?? null,
            'portalUrl' => $portalUrl,
            'settings' => $settings,
            'sectionKeys' => $data['enabledSections'] ?? [],
            'contacts' => $municipality['contacts'] ?? [],
            'socialPlatforms' => $municipality['social_platforms'] ?? [],
            'externalPlatforms' => $municipality['external_platforms'] ?? [],
            'businessHours' => $municipality['business_hours'] ?? [],
            'navigationItems' => $navigationItems,
            'footerItems' => $footerItems,
            'seo' => $seo,
        ]);
    }

    private function getCurrentPageKey(): string
    {
        $routeName = request()->route()?->getName() ?? '';

        return match (true) {
            str_contains($routeName, 'home') => 'home',
            str_contains($routeName, 'about') => 'about',
            str_contains($routeName, 'services') => 'services',
            str_contains($routeName, 'news') => 'news',
            str_contains($routeName, 'jobs') => 'jobs',
            str_contains($routeName, 'tenders') => 'tenders',
            str_contains($routeName, 'projects') => 'projects',
            str_contains($routeName, 'announcements') => 'announcements',
            str_contains($routeName, 'departments') => 'departments',
            str_contains($routeName, 'facilities') => 'facilities',
            str_contains($routeName, 'council') => 'council',
            str_contains($routeName, 'complaints') => 'complaints',
            str_contains($routeName, 'water') => 'water',
            str_contains($routeName, 'engineering') => 'engineering',
            str_contains($routeName, 'open-data') => 'open-data',
            default => 'global',
        };
    }
}
