<?php

declare(strict_types=1);

namespace App\Domains\Dashboard\Providers;

use App\Domains\Dashboard\Contracts\DashboardRepositoryInterface;
use App\Domains\Dashboard\Repositories\ExecutiveDashboardRepository;
use Illuminate\Support\ServiceProvider;

final class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DashboardRepositoryInterface::class, ExecutiveDashboardRepository::class);
    }
}
