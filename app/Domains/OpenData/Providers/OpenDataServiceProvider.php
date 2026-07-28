<?php

declare(strict_types=1);

namespace App\Domains\OpenData\Providers;

use App\Domains\OpenData\Contracts\OpenDataRepositoryInterface;
use App\Domains\OpenData\Models\OpenDataset;
use App\Domains\OpenData\Policies\OpenDataPolicy;
use App\Domains\OpenData\Repositories\EloquentOpenDataRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class OpenDataServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OpenDataRepositoryInterface::class, EloquentOpenDataRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(OpenDataset::class, OpenDataPolicy::class);
    }
}
