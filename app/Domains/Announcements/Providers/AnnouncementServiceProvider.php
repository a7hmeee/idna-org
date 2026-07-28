<?php

declare(strict_types=1);

namespace App\Domains\Announcements\Providers;

use App\Domains\Announcements\Actions\CreateAnnouncementAction;
use App\Domains\Announcements\Actions\DeleteAnnouncementAction;
use App\Domains\Announcements\Actions\PublishAnnouncementAction;
use App\Domains\Announcements\Actions\RecordAnnouncementViewAction;
use App\Domains\Announcements\Actions\ReorderAnnouncementsAction;
use App\Domains\Announcements\Actions\ToggleFeaturedAnnouncementAction;
use App\Domains\Announcements\Actions\UnpublishAnnouncementAction;
use App\Domains\Announcements\Actions\UpdateAnnouncementAction;
use App\Domains\Announcements\Contracts\AnnouncementRepositoryInterface;
use App\Domains\Announcements\Models\Announcement;
use App\Domains\Announcements\Policies\AnnouncementPolicy;
use App\Domains\Announcements\Repositories\EloquentAnnouncementRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class AnnouncementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            AnnouncementRepositoryInterface::class,
            EloquentAnnouncementRepository::class,
        );

        $this->app->singleton(CreateAnnouncementAction::class);
        $this->app->singleton(UpdateAnnouncementAction::class);
        $this->app->singleton(DeleteAnnouncementAction::class);
        $this->app->singleton(PublishAnnouncementAction::class);
        $this->app->singleton(UnpublishAnnouncementAction::class);
        $this->app->singleton(ToggleFeaturedAnnouncementAction::class);
        $this->app->singleton(RecordAnnouncementViewAction::class);
        $this->app->singleton(ReorderAnnouncementsAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Announcement::class, AnnouncementPolicy::class);
    }
}
