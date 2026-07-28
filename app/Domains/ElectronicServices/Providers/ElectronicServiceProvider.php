<?php

declare(strict_types=1);

namespace App\Domains\ElectronicServices\Providers;

use App\Domains\ElectronicServices\Actions\ArchiveElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\CreateElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\CreateServiceCategoryAction;
use App\Domains\ElectronicServices\Actions\DeleteElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\DeleteServiceCategoryAction;
use App\Domains\ElectronicServices\Actions\PublishElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\RecordPortalClickAction;
use App\Domains\ElectronicServices\Actions\RecordServiceViewAction;
use App\Domains\ElectronicServices\Actions\ReorderServiceCategoriesAction;
use App\Domains\ElectronicServices\Actions\ToggleElectronicServiceFeaturedAction;
use App\Domains\ElectronicServices\Actions\ToggleElectronicServicePublicAction;
use App\Domains\ElectronicServices\Actions\ToggleServiceCategoryPublicAction;
use App\Domains\ElectronicServices\Actions\UpdateElectronicServiceAction;
use App\Domains\ElectronicServices\Actions\UpdateServiceCategoryAction;
use App\Domains\ElectronicServices\Contracts\ElectronicServiceRepositoryInterface;
use App\Domains\ElectronicServices\Contracts\ServiceAnalyticsRepositoryInterface;
use App\Domains\ElectronicServices\Contracts\ServiceCategoryRepositoryInterface;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\ElectronicServices\Policies\ElectronicServicePolicy;
use App\Domains\ElectronicServices\Policies\ServiceCategoryPolicy;
use App\Domains\ElectronicServices\Repositories\EloquentElectronicServiceRepository;
use App\Domains\ElectronicServices\Repositories\EloquentServiceAnalyticsRepository;
use App\Domains\ElectronicServices\Repositories\EloquentServiceCategoryRepository;
use App\Domains\Homepage\Actions\CacheForgetHomepageDataAction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ElectronicServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServiceCategoryRepositoryInterface::class, EloquentServiceCategoryRepository::class);
        $this->app->bind(ElectronicServiceRepositoryInterface::class, EloquentElectronicServiceRepository::class);
        $this->app->bind(ServiceAnalyticsRepositoryInterface::class, EloquentServiceAnalyticsRepository::class);

        $this->app->singleton(CreateServiceCategoryAction::class);
        $this->app->singleton(UpdateServiceCategoryAction::class);
        $this->app->singleton(DeleteServiceCategoryAction::class);
        $this->app->singleton(ToggleServiceCategoryPublicAction::class);
        $this->app->singleton(ReorderServiceCategoriesAction::class);

        $this->app->singleton(CreateElectronicServiceAction::class);
        $this->app->singleton(UpdateElectronicServiceAction::class);
        $this->app->singleton(DeleteElectronicServiceAction::class);
        $this->app->singleton(PublishElectronicServiceAction::class);
        $this->app->singleton(ArchiveElectronicServiceAction::class);
        $this->app->singleton(ToggleElectronicServicePublicAction::class);
        $this->app->singleton(ToggleElectronicServiceFeaturedAction::class);
        $this->app->singleton(RecordServiceViewAction::class);
        $this->app->singleton(RecordPortalClickAction::class);
    }

    public function boot(): void
    {
        Gate::policy(ServiceCategory::class, ServiceCategoryPolicy::class);
        Gate::policy(ElectronicService::class, ElectronicServicePolicy::class);

        ElectronicService::saved(function (): void {
            CacheForgetHomepageDataAction::execute();
        });
        ElectronicService::deleted(function (): void {
            CacheForgetHomepageDataAction::execute();
        });

        ServiceCategory::saved(function (): void {
            CacheForgetHomepageDataAction::execute();
        });
        ServiceCategory::deleted(function (): void {
            CacheForgetHomepageDataAction::execute();
        });
    }
}
