<?php

declare(strict_types=1);

namespace App\Livewire\Homepage;

use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
use Livewire\Component;

final class PublicHomePage extends Component
{
    public array $homeData = [];

    public function mount(): void
    {
        $repo = app(HomepagePublicRepositoryInterface::class);
        $this->homeData = $repo->getHomePageData();
    }

    public function render()
    {
        $municipalityName = ($this->homeData['municipality']['name_ar'] ?? $this->homeData['settings']['site_title'] ?? 'بلدية إذنا');

        return view('livewire.homepage.public-home-page', [
            'data' => $this->homeData,
        ])->layout('layouts.home', [
            'title' => $municipalityName,
            'metaDescription' => ($this->homeData['municipality']['short_description'] ?? $this->homeData['settings']['site_subtitle'] ?? ''),
        ]);
    }
}
