<?php

declare(strict_types=1);

namespace App\Livewire\PublicFacilities;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\SharedKernel\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicFacilitiesIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filter = 'all';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(FacilityRepositoryInterface::class);
        $filter = $this->filter === 'featured' ? 'featured' : null;

        $facilities = $repo->getPublished(
            search: strlen($this->search) >= 2 ? $this->search : null,
            filter: $filter,
        );
        $featured = $repo->getFeatured();

        $carouselImages = [];
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
                $heroMedia = Media::where('mediable_type', $municipality->getMorphClass())
                    ->where('mediable_id', $municipality->getKey())
                    ->where('collection', 'facilities-hero')
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->get();

                if ($heroMedia->isNotEmpty()) {
                    $carouselImages = $heroMedia->map(fn ($m) => asset('storage/'.$m->path))->toArray();
                } else {
                    $fallback = Media::where('mediable_type', $municipality->getMorphClass())
                        ->where('mediable_id', $municipality->getKey())
                        ->where('collection', 'images')
                        ->where('is_active', true)
                        ->orderBy('display_order')
                        ->first();
                    if ($fallback) {
                        $carouselImages[] = asset('storage/'.$fallback->path);
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return view('livewire.public-facilities.public-facilities-index', [
            'facilities' => $facilities,
            'featured' => $featured,
            'carouselImages' => $carouselImages,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => 'المرافق العامة | '.$municipalityName,
            'metaDescription' => 'تصفح جميع المرافق العامة في '.$municipalityName.'، وتعرف على الخدمات التي تقدمها.',
        ]);
    }
}
