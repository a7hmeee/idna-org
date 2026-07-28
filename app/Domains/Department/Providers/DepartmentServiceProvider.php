<?php

declare(strict_types=1);

namespace App\Domains\Department\Providers;

use App\Domains\Department\Actions\CreateDepartmentAction;
use App\Domains\Department\Actions\DeleteDepartmentAction;
use App\Domains\Department\Actions\ReorderDepartmentsAction;
use App\Domains\Department\Actions\ToggleDepartmentFeaturedAction;
use App\Domains\Department\Actions\ToggleDepartmentPublicAction;
use App\Domains\Department\Actions\UpdateDepartmentAction;
use App\Domains\Department\Contracts\DepartmentRepositoryInterface;
use App\Domains\Department\Models\Department;
use App\Domains\Department\Policies\DepartmentPolicy;
use App\Domains\Department\Repositories\EloquentDepartmentRepository;
use App\Domains\Homepage\Actions\CacheForgetHomepageDataAction;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class DepartmentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DepartmentRepositoryInterface::class, EloquentDepartmentRepository::class);

        $this->app->singleton(CreateDepartmentAction::class);
        $this->app->singleton(UpdateDepartmentAction::class);
        $this->app->singleton(DeleteDepartmentAction::class);
        $this->app->singleton(ToggleDepartmentPublicAction::class);
        $this->app->singleton(ToggleDepartmentFeaturedAction::class);
        $this->app->singleton(ReorderDepartmentsAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Department::class, DepartmentPolicy::class);

        Department::saved(function (): void {
            CacheForgetHomepageDataAction::execute();
        });
        Department::deleted(function (): void {
            CacheForgetHomepageDataAction::execute();
        });
    }
}
