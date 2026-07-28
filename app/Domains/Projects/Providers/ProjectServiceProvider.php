<?php

declare(strict_types=1);

namespace App\Domains\Projects\Providers;

use App\Domains\Projects\Actions\CreateProjectAction;
use App\Domains\Projects\Actions\DeleteProjectAction;
use App\Domains\Projects\Actions\PublishProjectAction;
use App\Domains\Projects\Actions\RecordProjectViewAction;
use App\Domains\Projects\Actions\ToggleFeaturedProjectAction;
use App\Domains\Projects\Actions\UpdateProjectAction;
use App\Domains\Projects\Contracts\ProjectRepositoryInterface;
use App\Domains\Projects\Models\Project;
use App\Domains\Projects\Policies\ProjectPolicy;
use App\Domains\Projects\Repositories\EloquentProjectRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ProjectServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ProjectRepositoryInterface::class,
            EloquentProjectRepository::class,
        );

        $this->app->singleton(CreateProjectAction::class);
        $this->app->singleton(UpdateProjectAction::class);
        $this->app->singleton(DeleteProjectAction::class);
        $this->app->singleton(PublishProjectAction::class);
        $this->app->singleton(ToggleFeaturedProjectAction::class);
        $this->app->singleton(RecordProjectViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Project::class, ProjectPolicy::class);
    }
}
