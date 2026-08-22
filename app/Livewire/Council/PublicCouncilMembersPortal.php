<?php

declare(strict_types=1);

namespace App\Livewire\Council;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Enums\PageCarouselKey;
use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Models\Media;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

final class PublicCouncilMembersPortal extends Component
{
    use WithPagination;

    public string $search = '';

    public string $position = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPosition(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $repo = app(CouncilMemberRepositoryInterface::class);
        $pageKey = PageCarouselKey::CouncilMembers->value;

        $members = $repo->paginatePublicMembers(
            search: strlen($this->search) >= 2 ? $this->search : null,
            position: $this->position ?: null,
            perPage: 12
        );

        $committees = collect();
        try {
            $committees = CouncilMember::where('is_public', true)
                ->where('status', 'active')
                ->whereNotNull('committee')
                ->selectRaw('DISTINCT committee')
                ->pluck('committee');
        } catch (\Throwable $e) {
            // Fail silently
        }

        $stats = [];
        try {
            $stats['total'] = $members->total();
            $stats['committees'] = $committees->count();
            $years = CouncilMember::where('is_public', true)
                ->where('status', 'active')
                ->min('term_start');
            if ($years) {
                $stats['since'] = Carbon::parse($years)->format('Y');
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        $positionOptions = CouncilMemberPosition::options();

        $carouselImages = [];
        $municipalityName = 'بلدية إذنا';
        try {
            $municipality = Municipality::first();
            if ($municipality) {
                $municipalityName = $municipality->name_ar ?? $municipalityName;

                // Fetch carousel slides using centralized Page Carousel system
                $slidesRepo = app(HomepageRepositoryInterface::class);
                $slides = $slidesRepo->getPageSlides($pageKey);

                if ($slides->isNotEmpty()) {
                    $carouselImages = $slides->pluck('image_url')->toArray();
                } else {
                    $heroMedia = Media::where('mediable_type', $municipality->getMorphClass())
                        ->where('mediable_id', $municipality->getKey())
                        ->where('collection', 'council-hero')
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
            }
        } catch (\Throwable $e) {
            // Fail silently
        }

        return view('livewire.council.public-council-members-portal', [
            'members' => $members,
            'stats' => $stats,
            'positionOptions' => $positionOptions,
            'carouselImages' => $carouselImages,
            'municipalityName' => $municipalityName,
        ])->layout('layouts.home', [
            'title' => 'أعضاء المجلس البلدي | '.$municipalityName,
            'metaDescription' => 'تعرف على أعضاء المجلس البلدي في '.$municipalityName.'، وتصفح ملفاتهم الشخصية.',
        ]);
    }
}
