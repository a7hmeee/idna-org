<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Providers;

use App\Domains\Homepage\Actions\CacheForgetHomepageDataAction;
use App\Domains\Homepage\Actions\CreateHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\CreateHomepageSlideAction;
use App\Domains\Homepage\Actions\CreateHomepageStatisticAction;
use App\Domains\Homepage\Actions\DeleteHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\DeleteHomepageSlideAction;
use App\Domains\Homepage\Actions\DeleteHomepageStatisticAction;
use App\Domains\Homepage\Actions\ReorderHomepageQuickLinksAction;
use App\Domains\Homepage\Actions\ReorderHomepageSectionsAction;
use App\Domains\Homepage\Actions\ReorderHomepageSlidesAction;
use App\Domains\Homepage\Actions\ReorderHomepageStatisticsAction;
use App\Domains\Homepage\Actions\ToggleHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\ToggleHomepageSectionAction;
use App\Domains\Homepage\Actions\ToggleHomepageSlideAction;
use App\Domains\Homepage\Actions\ToggleHomepageStatisticAction;
use App\Domains\Homepage\Actions\UpdateHomepageQuickLinkAction;
use App\Domains\Homepage\Actions\UpdateHomepageSectionAction;
use App\Domains\Homepage\Actions\UpdateHomepageSettingsAction;
use App\Domains\Homepage\Actions\UpdateHomepageSlideAction;
use App\Domains\Homepage\Actions\UpdateHomepageStatisticAction;
use App\Domains\Homepage\Contracts\HomepagePublicRepositoryInterface;
use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Policies\HomepagePolicy;
use App\Domains\Homepage\Repositories\EloquentHomepagePublicRepository;
use App\Domains\Homepage\Repositories\EloquentHomepageRepository;
use App\Domains\Municipality\Events\MunicipalityUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class HomepageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HomepageRepositoryInterface::class, EloquentHomepageRepository::class);
        $this->app->bind(HomepagePublicRepositoryInterface::class, EloquentHomepagePublicRepository::class);

        $this->app->singleton(UpdateHomepageSettingsAction::class);

        $this->app->singleton(CreateHomepageSlideAction::class);
        $this->app->singleton(UpdateHomepageSlideAction::class);
        $this->app->singleton(DeleteHomepageSlideAction::class);
        $this->app->singleton(ToggleHomepageSlideAction::class);
        $this->app->singleton(ReorderHomepageSlidesAction::class);

        $this->app->singleton(UpdateHomepageSectionAction::class);
        $this->app->singleton(ToggleHomepageSectionAction::class);
        $this->app->singleton(ReorderHomepageSectionsAction::class);

        $this->app->singleton(CreateHomepageQuickLinkAction::class);
        $this->app->singleton(UpdateHomepageQuickLinkAction::class);
        $this->app->singleton(DeleteHomepageQuickLinkAction::class);
        $this->app->singleton(ToggleHomepageQuickLinkAction::class);
        $this->app->singleton(ReorderHomepageQuickLinksAction::class);

        $this->app->singleton(CreateHomepageStatisticAction::class);
        $this->app->singleton(UpdateHomepageStatisticAction::class);
        $this->app->singleton(DeleteHomepageStatisticAction::class);
        $this->app->singleton(ToggleHomepageStatisticAction::class);
        $this->app->singleton(ReorderHomepageStatisticsAction::class);

        $this->app->singleton(CacheForgetHomepageDataAction::class);
    }

    public function boot(): void
    {
        Gate::policy(HomepageSetting::class, HomepagePolicy::class);

        Event::listen(function (MunicipalityUpdated $event): void {
            CacheForgetHomepageDataAction::execute($event->section);
        });
    }
}
