<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\Providers;

use App\Domains\UserManagement\Actions\CreateUserAction;
use App\Domains\UserManagement\Actions\DeleteUserAction;
use App\Domains\UserManagement\Actions\ResetUserPasswordAction;
use App\Domains\UserManagement\Actions\UpdateUserAction;
use App\Domains\UserManagement\Contracts\UserManagementRepositoryInterface;
use App\Domains\UserManagement\Repositories\EloquentUserManagementRepository;
use Illuminate\Support\ServiceProvider;

final class UserManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserManagementRepositoryInterface::class, EloquentUserManagementRepository::class);

        $this->app->singleton(CreateUserAction::class);
        $this->app->singleton(UpdateUserAction::class);
        $this->app->singleton(DeleteUserAction::class);
        $this->app->singleton(ResetUserPasswordAction::class);
    }
}
