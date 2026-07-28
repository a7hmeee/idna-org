<?php

namespace App\Livewire\OpenData;

use App\Domains\OpenData\Contracts\OpenDataRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.home')]
final class OpenDataIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';
    public string $type = 'datasets';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(OpenDataRepositoryInterface::class);
        $pageKey = PageCarouselKey::OpenData->value;

        $slidesRepo = app(\App\Domains\Homepage\Contracts\HomepageRepositoryInterface::class);
        $slides = $slidesRepo->getPageSlides($pageKey);

        $datasets = $repo->getDatasets(
            search: strlen($this->search) >= 2 ? $this->search : null,
            category: $this->category ?: null,
            type: $this->type
        );

        $categories = $repo->getCategories();
        $featuredDatasets = $repo->getFeaturedDatasets();

        return view('livewire.open-data.index', [
            'datasets' => $datasets,
            'categories' => $categories,
            'featuredDatasets' => $featuredDatasets,
            'slides' => $slides,
            'type' => $this->type,
            'hasActiveFilters' => $this->search !== '' || $this->category !== '',
        ])->layout('layouts.home', [
            'title' => 'البيانات المفتوحة',
            'metaDescription' => 'تصفح البيانات المفتوحة المتاحة من بلدية إذنا، بما في ذلك التقارير والإحصاءات والدراسات.',
        ]);
    }
}