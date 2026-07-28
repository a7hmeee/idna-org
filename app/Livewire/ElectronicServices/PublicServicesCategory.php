<?php

declare(strict_types=1);

namespace App\Livewire\ElectronicServices;

use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicServicesCategory extends Component
{
    use WithPagination;

    public ServiceCategory $category;
    public string $search = '';
    public ?string $departmentFilter = null;
    public ?bool $requiresLoginFilter = null;
    public ?bool $featuredFilter = null;
    public string $sortField = 'sort_order';
    public string $sortDirection = 'asc';

    public function mount(ServiceCategory $category): void
    {
        abort_unless($category->is_public && $category->status === 'active', 404);

        $this->category = $category->loadCount('services');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDepartmentFilter(): void
    {
        $this->resetPage();
    }

    public function updatedRequiresLoginFilter(): void
    {
        $this->resetPage();
    }

    public function updatedFeaturedFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSortField(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(ElectronicServiceRepositoryInterface::class);

        $services = $repo->getByCategoryPaginated(
            $this->category->id,
            $this->search ?: null,
            $this->departmentFilter,
            $this->requiresLoginFilter,
            $this->featuredFilter,
            $this->sortField,
            $this->sortDirection,
            12
        );

        $municipalityName = 'بلدية إذنا';
        $portalUrl = null;
        try {
            $homeRepo = app(\App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface::class);
            $homeData = $homeRepo->getHomePageData();
            $municipalityName = ($homeData['municipality']['name_ar'] ?? $homeData['settings']['site_title'] ?? 'بلدية إذنا');
            $portalUrl = $homeData['settings']['portal_url'] ?? null;
        } catch (\Throwable $e) {
            // Fallback
        }

        return view('livewire.electronic-services.public-services-category', [
            'services' => $services,
            'portalUrl' => $portalUrl,
        ])->layout('layouts.home', [
            'title' => $this->category->name . ' | الخدمات الإلكترونية | ' . $municipalityName,
            'metaDescription' => $this->category->description ?? 'تصفح خدمات ' . $this->category->name . ' في ' . $municipalityName,
        ]);
    }
}
