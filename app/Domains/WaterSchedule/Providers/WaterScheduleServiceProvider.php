<?php

declare(strict_types=1);

namespace App\Domains\WaterSchedule\Providers;

use App\Domains\WaterSchedule\Actions\CopyPreviousScheduleAction;
use App\Domains\WaterSchedule\Actions\CreateMaintenanceAction;
use App\Domains\WaterSchedule\Actions\CreateWaterAreaAction;
use App\Domains\WaterSchedule\Actions\CreateWaterScheduleAction;
use App\Domains\WaterSchedule\Actions\DeleteMaintenanceAction;
use App\Domains\WaterSchedule\Actions\DeleteWaterAreaAction;
use App\Domains\WaterSchedule\Actions\PublishWaterScheduleAction;
use App\Domains\WaterSchedule\Actions\UpdateMaintenanceAction;
use App\Domains\WaterSchedule\Actions\UpdateWaterAreaAction;
use App\Domains\WaterSchedule\Actions\UpdateWaterScheduleAction;
use App\Domains\WaterSchedule\Contracts\WaterAreaRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterMaintenanceRepositoryInterface;
use App\Domains\WaterSchedule\Contracts\WaterScheduleRepositoryInterface;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Policies\WaterSchedulePolicy;
use App\Domains\WaterSchedule\Repositories\EloquentWaterAreaRepository;
use App\Domains\WaterSchedule\Repositories\EloquentWaterMaintenanceRepository;
use App\Domains\WaterSchedule\Repositories\EloquentWaterScheduleRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class WaterScheduleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WaterAreaRepositoryInterface::class, EloquentWaterAreaRepository::class);
        $this->app->bind(WaterScheduleRepositoryInterface::class, EloquentWaterScheduleRepository::class);
        $this->app->bind(WaterMaintenanceRepositoryInterface::class, EloquentWaterMaintenanceRepository::class);

        $this->app->singleton(CreateWaterAreaAction::class);
        $this->app->singleton(UpdateWaterAreaAction::class);
        $this->app->singleton(DeleteWaterAreaAction::class);

        $this->app->singleton(CreateWaterScheduleAction::class);
        $this->app->singleton(UpdateWaterScheduleAction::class);
        $this->app->singleton(CopyPreviousScheduleAction::class);
        $this->app->singleton(PublishWaterScheduleAction::class);

        $this->app->singleton(CreateMaintenanceAction::class);
        $this->app->singleton(UpdateMaintenanceAction::class);
        $this->app->singleton(DeleteMaintenanceAction::class);
    }

    public function boot(): void
    {
        Gate::policy(WaterArea::class, WaterSchedulePolicy::class);
    }
}
