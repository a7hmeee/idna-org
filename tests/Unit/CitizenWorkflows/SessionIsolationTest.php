<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Contracts\CitizenWorkflowRouterInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use App\Domains\CitizenWorkflows\Services\ConfirmationFlow;
use App\Domains\CitizenWorkflows\Services\WorkflowExecutionDispatcher;
use App\Domains\CitizenWorkflows\Services\WorkflowValidator;
use Carbon\Carbon;

beforeEach(function () {
    $this->draftRepo = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $this->draftRepo->shouldIgnoreMissing();
    $this->router = Mockery::mock(CitizenWorkflowRouterInterface::class);
    $this->router->shouldIgnoreMissing();
    $this->validator = Mockery::mock(WorkflowValidator::class);
    $this->validator->shouldIgnoreMissing();
    $this->confirmation = Mockery::mock(ConfirmationFlow::class);
    $this->confirmation->shouldIgnoreMissing();
    $this->executor = Mockery::mock(WorkflowExecutionDispatcher::class);
    $this->executor->shouldIgnoreMissing();

    $this->engine = new CitizenWorkflowEngine(
        $this->draftRepo,
        $this->router,
        $this->validator,
        $this->confirmation,
        $this->executor,
    );
});

it('prevents session A from accessing session Bs draft', function () {
    $draftA = new WorkflowDraft;
    $draftA->id = 1;
    $draftA->session_id = 'session-A';
    $draftA->workflow_type = 'complaint';
    $draftA->current_step = 'name';
    $draftA->answers = [];
    $draftA->status = 'collecting_data';
    $draftA->expires_at = Carbon::now()->addHour();

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-B')->andReturn(null);

    $result = $this->engine->start('session-B', WorkflowType::Complaint, 42);

    expect($result->message)->not->toContain('قيد الإكمال');
    expect($result->type)->toBe('workflow_question');
});

it('properly isolates drafts by session', function () {
    $draftA = new WorkflowDraft;
    $draftA->id = 1;
    $draftA->session_id = 'session-A';
    $draftA->workflow_type = 'complaint';
    $draftA->current_step = 'name';
    $draftA->answers = [];
    $draftA->status = 'collecting_data';
    $draftA->expires_at = Carbon::now()->addHour();

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-A')->andReturn($draftA);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('بياناتي')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->with('بياناتي')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->with('بياناتي')->andReturn(false);

    $this->validator->shouldReceive('validate')->with('name', 'بياناتي')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'بياناتي')->andReturn('بياناتي');

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone', 'details']);
    $this->router->shouldReceive('getStepQuestion')->andReturn('الرجاء إدخال رقم الهاتف:');

    $this->draftRepo->shouldReceive('update')->with(1, Mockery::any());

    $result = $this->engine->processInput('session-A', 'بياناتي');

    expect($result->type)->toBe('workflow_question');

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-B')->andReturn(null);

    $resultB = $this->engine->processInput('session-B', 'بياناتي');

    expect($resultB->type)->toBe('workflow_not_found');
});
