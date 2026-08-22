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

    $this->draft = new WorkflowDraft;
    $this->draft->id = 1;
    $this->draft->session_id = 'session-1';
    $this->draft->workflow_type = 'complaint';
    $this->draft->current_step = 'name';
    $this->draft->answers = [];
    $this->draft->status = 'collecting_data';
    $this->draft->expires_at = Carbon::now()->addHour();
});

it('handles global cancel command during data collection', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('إلغاء')->andReturn(true);
    $this->confirmation->shouldReceive('isHelp')->never();

    $this->draftRepo->shouldReceive('cancel')->with(1);

    $result = $this->engine->processInput('session-1', 'إلغاء');

    expect($result->cancelled)->toBeTrue()
        ->and($result->type)->toBe('workflow_cancelled')
        ->and($result->message)->toContain('إلغاء');
});

it('handles help command during data collection', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('مساعدة')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->with('مساعدة')->andReturn(true);

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone', 'address']);

    $result = $this->engine->processInput('session-1', 'مساعدة');

    expect($result->type)->toBe('workflow_help')
        ->and($result->message)->toContain('مساعدة');
});

it('handles global cancel during confirmation', function () {
    $this->draft->current_step = 'confirm';
    $this->draft->status = 'waiting_confirmation';

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('إلغاء')->andReturn(true);

    $this->draftRepo->shouldReceive('cancel')->with(1);

    $result = $this->engine->processInput('session-1', 'إلغاء');

    expect($result->cancelled)->toBeTrue();
    expect($result->type)->toBe('workflow_cancelled');
});
