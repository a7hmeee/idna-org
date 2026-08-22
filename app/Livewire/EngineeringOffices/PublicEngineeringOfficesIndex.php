<?php

namespace App\Livewire\EngineeringOffices;

use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.home')]
final class PublicEngineeringOfficesIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(EngineeringOfficeRepositoryInterface::class);
        $pageKey = PageCarouselKey::EngineeringOffices->value;

        // Get public offices
        $offices = $repo->getPublicOffices(
            search: strlen($this->search) >= 3 ? $this->search : null,
            filter: $this->filter === 'featured' ? 'featured' : null
        );

        // Get carousel slides using centralized Page Carousel system
        $slidesRepo = app(HomepageRepositoryInterface::class);
        $slides = $slidesRepo->getPageSlides($pageKey);

        // Get featured offices for sidebar
        $featuredOffices = $repo->getFeaturedPublicOffices();

        return view('livewire.engineering-offices.public-engineering-offices-index', [
            'offices' => $offices,
            'slides' => $slides,
            'featuredOffices' => $featuredOffices,
            'hasActiveFilters' => $this->search !== '' || $this->filter !== 'all',
        ])->layout('layouts.home', [
            'title' => 'المكاتب الهندسية',
            'metaDescription' => 'تصفح المكاتب الهندسية المعتمدة من قبل البلدية، وتعرف على خدماتها ومواعيد عملها.',
        ]);
    }
}
