<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Providers;

use App\Domains\Authentication\Actions\ChangePasswordAction;
use App\Domains\Authentication\Actions\ForgotPasswordAction;
use App\Domains\Authentication\Actions\LoginAction;
use App\Domains\Authentication\Actions\LogoutAction;
use App\Domains\Authentication\Actions\ResetPasswordAction;
use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\Listeners\LogFailedLoginAttempt;
use App\Domains\Authentication\Listeners\LogPasswordChange;
use App\Domains\Authentication\Listeners\LogSuccessfulLogin;
use App\Domains\Authentication\Listeners\LogSuccessfulLogout;
use App\Domains\Authentication\Repositories\EloquentLoginActivityRepository;
use App\Domains\Authentication\Repositories\EloquentUserRepository;
use App\Domains\Authentication\Services\AuthenticationService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

final class AuthenticationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(LoginActivityRepositoryInterface::class, EloquentLoginActivityRepository::class);

        $this->app->singleton(LoginAction::class);
        $this->app->singleton(LogoutAction::class);
        $this->app->singleton(ForgotPasswordAction::class);
        $this->app->singleton(ResetPasswordAction::class);
        $this->app->singleton(ChangePasswordAction::class);
        $this->app->singleton(AuthenticationService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../../../database/migrations');

        Event::listen(
            \App\Domains\Authentication\Events\UserLoggedIn::class,
            LogSuccessfulLogin::class,
        );

        Event::listen(
            \App\Domains\Authentication\Events\UserLoggedOut::class,
            LogSuccessfulLogout::class,
        );

        Event::listen(
            \App\Domains\Authentication\Events\LoginAttemptFailed::class,
            LogFailedLoginAttempt::class,
        );

        Event::listen(
            \App\Domains\Authentication\Events\PasswordChanged::class,
            LogPasswordChange::class,
        );
    }
}
