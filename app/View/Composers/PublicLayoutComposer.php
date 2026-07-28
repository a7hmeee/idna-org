<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
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
        ]);
    }
}
