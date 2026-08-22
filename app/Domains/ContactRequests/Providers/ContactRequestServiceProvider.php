<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Providers;

use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\Models\ContactRequest;
use App\Domains\ContactRequests\Policies\ContactRequestPolicy;
use App\Domains\ContactRequests\Repositories\EloquentContactRequestRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

final class ContactRequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContactRequestRepositoryInterface::class, EloquentContactRequestRepository::class);
    }

    public function boot(): void
    {
        Gate::policy(ContactRequest::class, ContactRequestPolicy::class);
    }
}
