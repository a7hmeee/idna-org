<?php

declare(strict_types=1);

namespace App\Domains\CitizenWorkflows\Providers;

use App\Domains\Chatbot\Services\ResponseTextPresenter;
use App\Domains\CitizenWorkflows\Contracts\CitizenWorkflowRouterInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\Repositories\EloquentWorkflowDraftRepository;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowRouter;
use App\Domains\CitizenWorkflows\Services\ConfirmationFlow;
use App\Domains\CitizenWorkflows\Services\WorkflowExecutionDispatcher;
use App\Domains\CitizenWorkflows\Services\WorkflowResponseBuilder;
use App\Domains\CitizenWorkflows\Services\WorkflowTrackingResolver;
use App\Domains\CitizenWorkflows\Services\WorkflowValidator;
use Illuminate\Support\ServiceProvider;

final class CitizenWorkflowServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(WorkflowDraftRepositoryInterface::class, EloquentWorkflowDraftRepository::class);
        $this->app->bind(CitizenWorkflowRouterInterface::class, CitizenWorkflowRouter::class);
        $this->app->bind(WorkflowTrackingResolverInterface::class, WorkflowTrackingResolver::class);

        $this->app->singleton(WorkflowValidator::class);
        $this->app->singleton(ConfirmationFlow::class);
        $this->app->singleton(WorkflowExecutionDispatcher::class);
        $this->app->singleton(CitizenWorkflowEngine::class);
        $this->app->singleton(WorkflowResponseBuilder::class);
        $this->app->singleton(ResponseTextPresenter::class);
    }
}
