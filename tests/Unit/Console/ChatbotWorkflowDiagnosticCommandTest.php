<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingData;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

beforeEach(function (): void {
    $this->context = Mockery::mock(ConversationContextInterface::class);
    $this->draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $this->tracker = Mockery::mock(WorkflowTrackingResolverInterface::class);
    $this->engine = Mockery::mock(CitizenWorkflowEngine::class);

    $this->app->instance(ConversationContextInterface::class, $this->context);
    $this->app->instance(WorkflowDraftRepositoryInterface::class, $this->draftRepo);
    $this->app->instance(WorkflowTrackingResolverInterface::class, $this->tracker);
    $this->app->instance(CitizenWorkflowEngine::class, $this->engine);
});

it('displays context state for a session', function () {
    $state = new ConversationStateData(
        state: ConversationState::WorkflowCollectingData->value,
        lastIntent: 'create_complaint',
        currentDomain: 'citizen_workflow',
        currentServiceId: null,
        currentServiceName: null,
        needsClarification: false,
        expired: false,
        workflowDraftId: 1,
        workflowType: 'complaint',
    );

    $this->context->expects('getState')->with('session-test')->andReturn($state);

    $trackingData = new WorkflowTrackingData(
        exists: true,
        trackingNumber: 'TRK-001',
        status: 'collecting_data',
        type: 'complaint',
        currentStep: 1,
        totalSteps: 5,
    );

    $this->tracker->expects('resolveBySessionId')->with('session-test')->andReturn($trackingData);

    $this->artisan('chatbot:workflow-diagnostic', ['session' => 'session-test'])
        ->expectsOutputToContain('session-test')
        ->assertExitCode(0);
});

it('shows warning when no active workflow draft', function () {
    $state = new ConversationStateData(
        state: ConversationState::Normal->value,
        lastIntent: null,
        currentDomain: null,
        currentServiceId: null,
        currentServiceName: null,
        needsClarification: false,
        expired: false,
        workflowDraftId: null,
        workflowType: null,
    );

    $this->context->expects('getState')->with('session-empty')->andReturn($state);
    $this->tracker->expects('resolveBySessionId')->with('session-empty')->andReturn(null);

    $this->artisan('chatbot:workflow-diagnostic', ['session' => 'session-empty'])
        ->assertExitCode(0);
});

it('lists all active workflows with --all flag', function () {
    $draft1 = new stdClass;
    $draft1->session_id = 'session-1';
    $draft1->type = 'complaint';
    $draft1->status = 'collecting_data';
    $draft1->tracking_number = 'TRK-001';

    $this->draftRepo->expects('allActive')->andReturn(collect([$draft1]));

    $this->artisan('chatbot:workflow-diagnostic', ['--all' => true])
        ->assertExitCode(0);
});
