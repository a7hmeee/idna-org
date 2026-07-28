<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use Livewire\Component;

final class PublicPageCarousel extends Component
{
    public string $pageKey;
    public bool $compact = false;

    public ?string $fallbackTitle = null;
    public ?string $fallbackDescription = null;
    public ?string $fallbackBadge = null;
    public ?string $fallbackIcon = null;
    public ?string $fallbackImage = null;

    public array $breadcrumb = [];
    public ?string $pageTitle = null;
    public ?string $pageSubtitle = null;
    public ?string $pageBadge = null;
    public ?string $pageBadgeIcon = null;

    /** Extra breadcrumb levels (e.g. category for services detail) */
    public ?array $breadcrumbExtra = null;

    public function render()
    {
        $slides = app(HomepageRepositoryInterface::class)->getPageSlides($this->pageKey);

        if (empty($this->breadcrumb)) {
            $this->breadcrumb = $this->generateBreadcrumb();
        }

        return view('livewire.public-page-carousel', [
            'slides' => $slides,
            'hasMultiple' => $slides->count() > 1,
            'hasSingle' => $slides->count() === 1,
        ]);
    }

    private function generateBreadcrumb(): array
    {
        $parent = $this->getParentInfo();

        $breadcrumb = [];

        if ($parent && $this->pageTitle) {
            // Detail page: Home > Parent > [Extra] > Current Page
            $breadcrumb[] = ['label' => 'الرئيسية', 'url' => route('home')];
            $breadcrumb[] = $parent;

            if ($this->breadcrumbExtra) {
                foreach ($this->breadcrumbExtra as $item) {
                    $breadcrumb[] = $item;
                }
            }

            $breadcrumb[] = ['label' => $this->pageTitle];
        } elseif ($parent) {
            // Index page: Home > Parent (current)
            $breadcrumb[] = ['label' => 'الرئيسية', 'url' => route('home')];
            $breadcrumb[] = ['label' => $parent['label']];
        }

        return $breadcrumb;
    }

    private function getParentInfo(): ?array
    {
        return match ($this->pageKey) {
            'services' => ['label' => 'الخدمات الإلكترونية', 'url' => route('public.services.index')],
            'departments' => ['label' => 'الدوائر والأقسام', 'url' => url('/departments')],
            'facilities' => ['label' => 'المرافق العامة', 'url' => route('public.facilities.index')],
            'jobs' => ['label' => 'الوظائف', 'url' => route('public.jobs.index')],
            'council-members' => ['label' => 'المجلس البلدي', 'url' => route('public.council.index')],
            'council-decisions' => ['label' => 'قرارات المجلس البلدي', 'url' => route('public.council.decisions.index')],
            'engineering-offices' => ['label' => 'المكاتب الهندسية', 'url' => route('public.engineering-offices.index')],
            'announcements' => ['label' => 'الإعلانات', 'url' => route('public.announcements.index')],
            default => null,
        };
    }
}
