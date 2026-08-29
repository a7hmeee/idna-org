<?php

declare(strict_types=1);

namespace App\Livewire\Municipality;

use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\Municipality\Contracts\CouncilMemberRepositoryInterface;
use App\Domains\Municipality\Enums\CouncilMemberPosition;
use App\Domains\Municipality\Models\CouncilMember;
use App\Domains\Municipality\Models\Municipality;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Models\NewsItem;
use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

final class PublicMunicipalityAbout extends Component
{
    public ?Municipality $municipality = null;

    /** @var array<int, array<string, mixed>> */
    public array $contacts = [];

    /** @var array<int, array<string, mixed>> */
    public array $socialPlatforms = [];

    /** @var array<int, array<string, mixed>> */
    public array $images = [];

    /** @var array<int, array<string, mixed>> */
    public array $businessHours = [];

    /** @var array<int, array<string, mixed>> */
    public array $customFields = [];

    /** @var array<int, array<string, mixed>> */
    public array $councilMembers = [];

    /** @var \Illuminate\Support\Collection<int, NewsItem> */
    public \Illuminate\Support\Collection $latestNews;

    /** @var \Illuminate\Support\Collection<int, Project> */
    public \Illuminate\Support\Collection $latestProjects;

    /** @var Collection<int, ServiceCategory> */
    public Collection $serviceCategories;

    public string $municipalityName = 'بلدية إذنا';

    public function mount(): void
    {
        $this->latestNews = collect();
        $this->latestProjects = collect();
        $this->serviceCategories = new Collection;

        try {
            $municipality = Municipality::with([
                'contacts',
                'socialPlatforms',
                'media',
                'businessHours',
                'customFields',
            ])->first();

            if ($municipality) {
                $this->municipality = $municipality;
                $this->municipalityName = $municipality->name_ar ?? $this->municipalityName;

                $this->contacts = $municipality->contacts
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->socialPlatforms = $municipality->socialPlatforms
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->images = $municipality->media
                    ->where('is_active', true)
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->businessHours = $municipality->businessHours
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();

                $this->customFields = $municipality->customFields
                    ->where('is_active', true)
                    ->sortBy('display_order')
                    ->values()
                    ->toArray();
            }

            // Council members — load mayor separately (may have is_public=false), merge with public members
            try {
                $councilRepo = app(CouncilMemberRepositoryInterface::class);

                $mayorModel = $councilRepo->getMayor();
                /** @var Collection<int, CouncilMember> $publicMembers */
                $publicMembers = $councilRepo->getPublicMembers();

                $positionOrder = [
                    CouncilMemberPosition::Mayor->value => 0,
                    CouncilMemberPosition::DeputyMayor->value => 1,
                    CouncilMemberPosition::Secretary->value => 2,
                    CouncilMemberPosition::Treasurer->value => 3,
                    CouncilMemberPosition::CouncilMember->value => 4,
                ];

                // Merge mayor into public members (avoid duplicate by id)
                $merged = $publicMembers;
                if ($mayorModel && ! $publicMembers->contains('id', $mayorModel->id)) {
                    $merged = $mayorModel->newCollection([$mayorModel])->concat($publicMembers);
                } elseif ($mayorModel) {
                    // Mayor is in public members — ensure he sorts first
                    $merged = $publicMembers;
                }

                $this->councilMembers = $merged
                    ->sortBy(fn ($m) => [
                        $positionOrder[$m->position] ?? 5,
                        $m->display_order,
                    ])
                    ->values()
                    ->toArray();
            } catch (\Throwable $e) {
                //
            }

            // Latest news
            try {
                $this->latestNews = app(NewsRepositoryInterface::class)->getLatest(4);
            } catch (\Throwable $e) {
                //
            }

            // Latest projects
            try {
                $this->latestProjects = app(ProjectRepositoryInterface::class)->getLatest(4);
            } catch (\Throwable $e) {
                //
            }

            // Service categories
            try {
                $this->serviceCategories = app(ServiceCategoryRepositoryInterface::class)->getRootPublicCategories();
            } catch (\Throwable $e) {
                //
            }
        } catch (\Throwable $e) {
            //
        }
    }

    public function render(): mixed
    {
        $metaDescription = $this->municipality !== null
            ? ($this->municipality->short_description ?? 'تعرف على '.$this->municipalityName)
            : 'تعرف على '.$this->municipalityName;

        return view('livewire.municipality.public-municipality-about', [
            'municipality' => $this->municipality,
            'contacts' => $this->contacts,
            'socialPlatforms' => $this->socialPlatforms,
            'images' => $this->images,
            'businessHours' => $this->businessHours,
            'customFields' => $this->customFields,
            'councilMembers' => $this->councilMembers,
            'latestNews' => $this->latestNews,
            'latestProjects' => $this->latestProjects,
            'serviceCategories' => $this->serviceCategories,
        ])->layout('layouts.home', [
            'title' => 'عن '.$this->municipalityName,
            'metaDescription' => $metaDescription,
        ]);
    }
}
