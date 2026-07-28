<?php

declare(strict_types=1);

namespace App\Domains\News\Providers;

use App\Domains\News\Actions\CreateNewsAction;
use App\Domains\News\Actions\DeleteNewsAction;
use App\Domains\News\Actions\PublishNewsAction;
use App\Domains\News\Actions\RecordNewsViewAction;
use App\Domains\News\Actions\ToggleFeaturedNewsAction;
use App\Domains\News\Actions\UpdateNewsAction;
use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Models\NewsItem;
use App\Domains\News\Policies\NewsPolicy;
use App\Domains\News\Repositories\EloquentNewsRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class NewsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NewsRepositoryInterface::class, EloquentNewsRepository::class);

        $this->app->singleton(CreateNewsAction::class);
        $this->app->singleton(UpdateNewsAction::class);
        $this->app->singleton(DeleteNewsAction::class);
        $this->app->singleton(PublishNewsAction::class);
        $this->app->singleton(ToggleFeaturedNewsAction::class);
        $this->app->singleton(RecordNewsViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(NewsItem::class, NewsPolicy::class);
    }
}
