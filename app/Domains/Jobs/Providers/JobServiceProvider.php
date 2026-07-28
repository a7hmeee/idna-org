<?php

declare(strict_types=1);

namespace App\Domains\Jobs\Providers;

use App\Domains\Jobs\Actions\ArchiveJobAction;
use App\Domains\Jobs\Actions\CloseJobAction;
use App\Domains\Jobs\Actions\CreateJobAction;
use App\Domains\Jobs\Actions\DeleteJobAction;
use App\Domains\Jobs\Actions\PublishJobAction;
use App\Domains\Jobs\Actions\RecordJobViewAction;
use App\Domains\Jobs\Actions\ToggleFeaturedJobAction;
use App\Domains\Jobs\Actions\UpdateJobAction;
use App\Domains\Jobs\Contracts\JobRepositoryInterface;
use App\Domains\Jobs\Models\Job;
use App\Domains\Jobs\Policies\JobPolicy;
use App\Domains\Jobs\Repositories\EloquentJobRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class JobServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(JobRepositoryInterface::class, EloquentJobRepository::class);

        $this->app->singleton(CreateJobAction::class);
        $this->app->singleton(UpdateJobAction::class);
        $this->app->singleton(DeleteJobAction::class);
        $this->app->singleton(PublishJobAction::class);
        $this->app->singleton(ArchiveJobAction::class);
        $this->app->singleton(CloseJobAction::class);
        $this->app->singleton(ToggleFeaturedJobAction::class);
        $this->app->singleton(RecordJobViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Job::class, JobPolicy::class);
    }
}
