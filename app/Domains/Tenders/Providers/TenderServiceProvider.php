<?php

declare(strict_types=1);

namespace App\Domains\Tenders\Providers;

use App\Domains\Tenders\Actions\ArchiveTenderAction;
use App\Domains\Tenders\Actions\AwardTenderAction;
use App\Domains\Tenders\Actions\CancelTenderAction;
use App\Domains\Tenders\Actions\CreateTenderAction;
use App\Domains\Tenders\Actions\DeleteTenderAction;
use App\Domains\Tenders\Actions\PublishTenderAction;
use App\Domains\Tenders\Actions\RecordTenderViewAction;
use App\Domains\Tenders\Actions\ToggleFeaturedTenderAction;
use App\Domains\Tenders\Actions\UpdateTenderAction;
use App\Domains\Tenders\Contracts\TenderRepositoryInterface;
use App\Domains\Tenders\Models\Tender;
use App\Domains\Tenders\Policies\TenderPolicy;
use App\Domains\Tenders\Repositories\EloquentTenderRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class TenderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenderRepositoryInterface::class, EloquentTenderRepository::class);

        $this->app->singleton(CreateTenderAction::class);
        $this->app->singleton(UpdateTenderAction::class);
        $this->app->singleton(DeleteTenderAction::class);
        $this->app->singleton(PublishTenderAction::class);
        $this->app->singleton(AwardTenderAction::class);
        $this->app->singleton(CancelTenderAction::class);
        $this->app->singleton(ArchiveTenderAction::class);
        $this->app->singleton(ToggleFeaturedTenderAction::class);
        $this->app->singleton(RecordTenderViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Tender::class, TenderPolicy::class);
    }
}
