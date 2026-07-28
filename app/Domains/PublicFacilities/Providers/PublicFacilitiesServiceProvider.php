<?php

declare(strict_types=1);

namespace App\Domains\PublicFacilities\Providers;

use App\Domains\PublicFacilities\Actions\ArchiveFacilityAction;
use App\Domains\PublicFacilities\Actions\CreateFacilityAction;
use App\Domains\PublicFacilities\Actions\CreateFacilityCategoryAction;
use App\Domains\PublicFacilities\Actions\DeleteFacilityAction;
use App\Domains\PublicFacilities\Actions\DeleteFacilityCategoryAction;
use App\Domains\PublicFacilities\Actions\PublishFacilityAction;
use App\Domains\PublicFacilities\Actions\RecordFacilityViewAction;
use App\Domains\PublicFacilities\Actions\ToggleFeaturedFacilityAction;
use App\Domains\PublicFacilities\Actions\UpdateFacilityAction;
use App\Domains\PublicFacilities\Actions\UpdateFacilityCategoryAction;
use App\Domains\PublicFacilities\Contracts\FacilityCategoryRepositoryInterface;
use App\Domains\PublicFacilities\Contracts\FacilityRepositoryInterface;
use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use App\Domains\PublicFacilities\Policies\FacilityCategoryPolicy;
use App\Domains\PublicFacilities\Policies\FacilityPolicy;
use App\Domains\PublicFacilities\Repositories\EloquentFacilityCategoryRepository;
use App\Domains\PublicFacilities\Repositories\EloquentFacilityRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class PublicFacilitiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FacilityCategoryRepositoryInterface::class, EloquentFacilityCategoryRepository::class);
        $this->app->bind(FacilityRepositoryInterface::class, EloquentFacilityRepository::class);

        $this->app->singleton(CreateFacilityCategoryAction::class);
        $this->app->singleton(UpdateFacilityCategoryAction::class);
        $this->app->singleton(DeleteFacilityCategoryAction::class);
        $this->app->singleton(CreateFacilityAction::class);
        $this->app->singleton(UpdateFacilityAction::class);
        $this->app->singleton(DeleteFacilityAction::class);
        $this->app->singleton(PublishFacilityAction::class);
        $this->app->singleton(ArchiveFacilityAction::class);
        $this->app->singleton(ToggleFeaturedFacilityAction::class);
        $this->app->singleton(RecordFacilityViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(FacilityCategory::class, FacilityCategoryPolicy::class);
        Gate::policy(Facility::class, FacilityPolicy::class);
    }
}
