<?php

declare(strict_types=1);

namespace App\Livewire\Jobs;

use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicJobsIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filter = 'all';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(JobRepositoryInterface::class);
        $filter = $this->filter === 'featured' ? 'featured' : null;

        $jobs = $repo->getPublished(
            search: strlen($this->search) >= 2 ? $this->search : null,
            filter: $filter,
        );
        $featured = $repo->getFeatured();

        $carouselImages = [];
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = \App\Domains\Municipality\Models\Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;
                $heroMedia = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                    ->where('mediable_id', $municipality->getKey())
                    ->where('collection', 'jobs-hero')
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->get();

                if ($heroMedia->isNotEmpty()) {
                    $carouselImages = $heroMedia->map(fn ($m) => asset('storage/' . $m->path))->toArray();
                } else {
                    $fallback = \App\Domains\SharedKernel\Models\Media::where('mediable_type', $municipality->getMorphClass())
                        ->where('mediable_id', $municipality->getKey())
                        ->where('collection', 'images')
                        ->where('is_active', true)
                        ->orderBy('display_order')
                        ->first();
                    if ($fallback) {
                        $carouselImages[] = asset('storage/' . $fallback->path);
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        return view('livewire.jobs.public-jobs-index', [
            'jobs' => $jobs,
            'featured' => $featured,
            'carouselImages' => $carouselImages,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => 'الوظائف | ' . $municipalityName,
            'metaDescription' => 'تصفح جميع الوظائف الشاغرة في ' . $municipalityName . '، وتقدم للوظائف المناسبة لك.',
        ]);
    }
}
