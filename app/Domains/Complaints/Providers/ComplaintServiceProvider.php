<?php

declare(strict_types=1);

namespace App\Domains\Complaints\Providers;

use App\Domains\Complaints\Actions\AssignComplaintAction;
use App\Domains\Complaints\Actions\ChangeStatusAction;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\Actions\DeleteComplaintAction;
use App\Domains\Complaints\Actions\RecordComplaintViewAction;
use App\Domains\Complaints\Actions\RespondToComplaintAction;
use App\Domains\Complaints\Actions\UpdateComplaintAction;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\Complaints\Policies\ComplaintPolicy;
use App\Domains\Complaints\Repositories\EloquentComplaintRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ComplaintServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ComplaintRepositoryInterface::class, EloquentComplaintRepository::class);

        $this->app->singleton(CreateComplaintAction::class);
        $this->app->singleton(UpdateComplaintAction::class);
        $this->app->singleton(DeleteComplaintAction::class);
        $this->app->singleton(AssignComplaintAction::class);
        $this->app->singleton(ChangeStatusAction::class);
        $this->app->singleton(RespondToComplaintAction::class);
        $this->app->singleton(RecordComplaintViewAction::class);
    }

    public function boot(): void
    {
        Gate::policy(Complaint::class, ComplaintPolicy::class);
    }
}