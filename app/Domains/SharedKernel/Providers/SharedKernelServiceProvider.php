<?php

declare(strict_types=1);

namespace App\Domains\SharedKernel\Providers;

use App\Domains\SharedKernel\Actions\DeleteBusinessHourAction;
use App\Domains\SharedKernel\Actions\DeleteEmergencyContactAction;
use App\Domains\SharedKernel\Actions\DeleteMediaAction;
use App\Domains\SharedKernel\Actions\SaveBusinessHourAction;
use App\Domains\SharedKernel\Actions\SaveEmergencyContactAction;
use App\Domains\SharedKernel\Actions\SaveMediaAction;
use App\Domains\SharedKernel\Contracts\BusinessHourRepositoryInterface;
use App\Domains\SharedKernel\Contracts\EmergencyContactRepositoryInterface;
use App\Domains\SharedKernel\Contracts\MediaRepositoryInterface;
use App\Domains\SharedKernel\Repositories\EloquentBusinessHourRepository;
use App\Domains\SharedKernel\Repositories\EloquentEmergencyContactRepository;
use App\Domains\SharedKernel\Repositories\EloquentMediaRepository;
use App\Domains\SharedKernel\Services\MediaUploadService;
use Illuminate\Support\ServiceProvider;

final class SharedKernelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MediaRepositoryInterface::class, EloquentMediaRepository::class);
        $this->app->bind(BusinessHourRepositoryInterface::class, EloquentBusinessHourRepository::class);
        $this->app->bind(EmergencyContactRepositoryInterface::class, EloquentEmergencyContactRepository::class);

        $this->app->singleton(MediaUploadService::class);
        $this->app->singleton(SaveMediaAction::class);
        $this->app->singleton(DeleteMediaAction::class);
        $this->app->singleton(SaveBusinessHourAction::class);
        $this->app->singleton(DeleteBusinessHourAction::class);
        $this->app->singleton(SaveEmergencyContactAction::class);
        $this->app->singleton(DeleteEmergencyContactAction::class);
    }
}
