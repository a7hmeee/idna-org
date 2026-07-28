<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\Providers;

use App\Domains\RoleManagement\Actions\CreateRoleAction;
use App\Domains\RoleManagement\Actions\DeleteRoleAction;
use App\Domains\RoleManagement\Actions\UpdateRoleAction;
use App\Domains\RoleManagement\Contracts\RoleRepositoryInterface;
use App\Domains\RoleManagement\Repositories\EloquentRoleRepository;
use Illuminate\Support\ServiceProvider;

final class RoleManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);

        $this->app->singleton(CreateRoleAction::class);
        $this->app->singleton(UpdateRoleAction::class);
        $this->app->singleton(DeleteRoleAction::class);
    }
}
