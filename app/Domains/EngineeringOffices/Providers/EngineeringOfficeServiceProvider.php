<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\Providers;

use App\Domains\EngineeringOffices\Actions\ApproveEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\CreateEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\DeleteEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\MarkEngineeringOfficeExpiredAction;
use App\Domains\EngineeringOffices\Actions\ReorderEngineeringOfficesAction;
use App\Domains\EngineeringOffices\Actions\SuspendEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Actions\ToggleEngineeringOfficePublicAction;
use App\Domains\EngineeringOffices\Actions\UpdateEngineeringOfficeAction;
use App\Domains\EngineeringOffices\Contracts\EngineeringOfficeRepositoryInterface;
use App\Domains\EngineeringOffices\Models\EngineeringOffice;
use App\Domains\EngineeringOffices\Policies\EngineeringOfficePolicy;
use App\Domains\EngineeringOffices\Repositories\EloquentEngineeringOfficeRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class EngineeringOfficeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EngineeringOfficeRepositoryInterface::class, EloquentEngineeringOfficeRepository::class);

        $this->app->singleton(CreateEngineeringOfficeAction::class);
        $this->app->singleton(UpdateEngineeringOfficeAction::class);
        $this->app->singleton(DeleteEngineeringOfficeAction::class);
        $this->app->singleton(ApproveEngineeringOfficeAction::class);
        $this->app->singleton(SuspendEngineeringOfficeAction::class);
        $this->app->singleton(MarkEngineeringOfficeExpiredAction::class);
        $this->app->singleton(ToggleEngineeringOfficePublicAction::class);
        $this->app->singleton(ReorderEngineeringOfficesAction::class);
    }

    public function boot(): void
    {
        Gate::policy(EngineeringOffice::class, EngineeringOfficePolicy::class);
    }
}
