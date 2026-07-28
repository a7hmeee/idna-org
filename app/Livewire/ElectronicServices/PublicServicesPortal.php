<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicServicesPortal extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterCategoryId = null;
    public ?string $filterDepartmentSlug = null;
    public ?string $filterDepartmentName = null;
    public bool $showFilters = true;

    public function mount(): void
    {
        $departmentSlug = request()->query('department');
        if ($departmentSlug) {
            $this->filterDepartmentSlug = $departmentSlug;
            try {
                $dept = \App\Domains\Department\Models\Department::where('slug', $departmentSlug)
                    ->where('status', 'active')
                    ->where('is_public', true)
                    ->first(['name']);
                $this->filterDepartmentName = $dept?->name;
            } catch (\Throwable $e) {
                $this->filterDepartmentName = $departmentSlug;
            }
        }
    }

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterCategoryId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $categoryRepo = app(ServiceCategoryRepositoryInterface::class);
        $serviceRepo = app(ElectronicServiceRepositoryInterface::class);

        $categories = $categoryRepo->getRootPublicCategories();
        $featuredServices = $serviceRepo->getFeaturedServices();

        if (strlen($this->search) >= 2) {
            $services = $serviceRepo->searchPublicServices($this->search, $this->filterCategoryId, 12, $this->filterDepartmentSlug);
        } else {
            $services = $serviceRepo->searchPublicServices('', $this->filterCategoryId, 12, $this->filterDepartmentSlug);
        }

        $portalUrl = null;
        $heroImage = null;
        $ctaBackground = null;
        try {
            $homeRepo = app(\App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface::class);
            $homeData = $homeRepo->getHomePageData();
            $portalUrl = $homeData['settings']['portal_url'] ?? null;
            $municipalityName = ($homeData['municipality']['name_ar'] ?? $homeData['settings']['site_title'] ?? 'بلدية إذنا');
        } catch (\Throwable $e) {
            $municipalityName = 'بلدية إذنا';
        }

        try {
            $municipality = \App\Domains\Municipality\Models\Municipality::first();
            if ($municipality) {
                $heroMedia = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                    ->where('mediable_id', $municipality->getKey())
                    ->where('collection', 'images')
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->skip(1)
                    ->first();
                $heroImage = $heroMedia ? asset('storage/' . $heroMedia->path) : null;

                $ctaMedia = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                    ->where('mediable_id', $municipality->getKey())
                    ->where('collection', 'portal-cta')
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->first();
                if (!$ctaMedia) {
                    $ctaMedia = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                        ->where('mediable_id', $municipality->getKey())
                        ->where('collection', 'images')
                        ->where('is_active', true)
                        ->orderBy('display_order')
                        ->first();
                }
                $ctaBackground = $ctaMedia ? asset('storage/' . $ctaMedia->path) : null;
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return view('livewire.electronic-services.public-services-portal', [
            'categories' => $categories,
            'featuredServices' => $featuredServices,
            'services' => $services,
            'portalUrl' => $portalUrl,
            'heroImage' => $heroImage,
            'ctaBackground' => $ctaBackground,
        ])->layout('layouts.home', [
            'title' => 'بوابة الخدمات الإلكترونية | ' . $municipalityName,
            'metaDescription' => 'تصفح جميع الخدمات الإلكترونية المتاحة في ' . $municipalityName . '، واختر التصنيف المناسب لتقديم طلبك.',
        ]);
    }
}
