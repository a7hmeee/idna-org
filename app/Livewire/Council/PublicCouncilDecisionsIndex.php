<?php

declare(strict_types=1);

namespace App\Livewire\Council;

use App\Domains\Municipality\Contracts\CouncilDecisionRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilDecisionType;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\Municipality\Models\Municipality;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicCouncilDecisionsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $type = '';
    public string $year = '';
    public string $sort = 'latest';
    public bool $showFilters = true;

    protected $queryString = ['search', 'type', 'year', 'sort'];

    public function toggleFilters(): void
    {
        $this->showFilters = !$this->showFilters;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedYear(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->type = '';
        $this->year = '';
        $this->sort = 'latest';
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(CouncilDecisionRepositoryInterface::class);
        $pageKey = PageCarouselKey::CouncilDecisions->value;

        $searchValue = strlen($this->search) >= 2 ? $this->search : null;

        $decisions = $repo->paginatePublicDecisions(
            search: $searchValue,
            type: $this->type ?: null,
            year: $this->year ? (int) $this->year : null,
            sort: $this->sort,
            perPage: 12
        );

        $years = $repo->getPublicYears();
        $stats = $repo->getPublicStatistics();
        $typeOptions = CouncilDecisionType::options();

        $municipalityName = 'بلدية إذنا';
        $heroImageUrl = null;
        $slides = collect();
        $municipality = Municipality::first();
        if ($municipality) {
            $municipalityName = $municipality->name_ar ?? $municipalityName;

            $heroMedia = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                ->where('mediable_id', $municipality->getKey())
                ->where('collection', 'decisions-hero')
                ->where('is_active', true)
                ->orderBy('display_order')
                ->first();

            if ($heroMedia) {
                $heroImageUrl = asset('storage/' . $heroMedia->path);
            }

            // Fetch carousel slides using centralized Page Carousel system
            $slidesRepo = app(\App\Domains\Homepage\Contracts\HomepageRepositoryInterface::class);
            $slides = $slidesRepo->getPageSlides($pageKey);
        }

        $hasActiveFilters = $this->search !== '' || $this->type !== '' || $this->year !== '' || $this->sort !== 'latest';

        return view('livewire.council.public-council-decisions-index', [
            'decisions' => $decisions,
            'years' => $years,
            'stats' => $stats,
            'typeOptions' => $typeOptions,
            'municipalityName' => $municipalityName,
            'heroImageUrl' => $heroImageUrl,
            'carouselImages' => $slides->pluck('image_url'),
            'hasActiveFilters' => $hasActiveFilters,
        ])->layout('layouts.home', [
            'title' => 'قرارات المجلس البلدي | ' . $municipalityName,
            'metaDescription' => 'تصفح قرارات المجلس البلدي في ' . $municipalityName . '، واطلع على القرارات الإدارية والمالية والتنظيمية.',
        ]);
    }
}
