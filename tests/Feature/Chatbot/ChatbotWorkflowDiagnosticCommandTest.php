<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingData;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    $this->artisan = Artisan::command('chatbot:workflow-diagnostic');
});

it('shows usage when no arguments provided', function () {
    $this->artisan->expectsQuestion('session?', null)
        ->expectsOutputToContain('Usage: php artisan chatbot:workflow-diagnostic')
        ->execute();
});

it('can inspect a specific session', function () {
    $context = Mockery::mock(ConversationContextInterface::class);
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $tracker = Mockery::mock(WorkflowTrackingResolverInterface::class);
    $engine = Mockery::mock(CitizenWorkflowEngine::class);

    $state = new ConversationStateData(
        state: 'workflow_collecting_data',
        lastIntent: 'create_complaint',
        currentDomain: 'citizen_workflow',
        currentServiceId: null,
        currentServiceName: null,
        needsClarification: false,
        expired: false,
        workflowDraftId: 1,
        workflowType: 'complaint',
    );

    $context->expects('getState')->with('test-session')->andReturn($state);
    $tracker->expects('resolveBySessionId')->with('test-session')->andReturn(
        new WorkflowTrackingData(
            exists: true,
            trackingNumber: 'TRK-001',
            status: 'collecting_data',
            type: 'complaint',
            createdAt: '2025-01-01 10:00:00',
            updatedAt: '2025-01-01 10:15:00',
            currentStep: 'name',
            totalSteps: 5,
        )
    );

    $this->artisan->setInput('test-session --action=status')
        ->expectsOutputToContain('Inspecting session: test-session')
        ->expectsOutputToContain('workflow_collecting_data')
        ->expectsOutputToContain('TRK-001')
        ->execute();
});

it('can cancel a workflow via action', function () {
    $engine = Mockery::mock(CitizenWorkflowEngine::class);
    $engine->expects('cancel')->with('test-session', null)->andReturn(
        new WorkflowStepResultData(
            message: 'تم إلغاء الطلب.',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: 'complaint',
        )
    );

    $this->artisan->setInput('test-session --action=cancel')
        ->expectsOutputToContain('Performing action: cancel')
        ->expectsOutputToContain('Workflow cancelled successfully')
        ->execute();
});

it('shows all active workflows when --all flag used', function () {
    $draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);

    $draft1 = new stdClass;
    $draft1->session_id = 'session-1';
    $draft1->type = 'complaint';
    $draft1->status = 'collecting_data';
    $draft1->tracking_number = 'TRK-001';

    $draftRepo->expects('allActive')->andReturn([$draft1]);

    $this->artisan->setInput('--all')
        ->expectsOutputToContain('Active Workflow Sessions')
        ->expectsOutputToContain('session-1')
        ->execute();
});
