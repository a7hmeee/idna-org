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

it('validates draft ownership via userId before processing', function () {
    $this->draft->citizen_user_id = 42;

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->andReturn(false);

    $this->validator->shouldReceive('validate')->with('name', 'أحمد')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'أحمد')->andReturn('أحمد');

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone']);
    $this->router->shouldReceive('getStepQuestion')->with(WorkflowType::Complaint, 'phone')->andReturn('الرجاء إدخال رقم الهاتف:');

    $this->draftRepo->shouldReceive('update')->with($this->draft->id, Mockery::on(fn ($d) => isset($d['answers'])));

    $result = $this->engine->processInput('session-1', 'أحمد', 42);

    expect($result->type)->toBe('workflow_question');
});

it('rejects access when userId does not match draft owner', function () {
    $this->draft->citizen_user_id = 99;

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->andReturn(false);

    $this->validator->shouldReceive('validate')->with('name', 'أحمد')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'أحمد')->andReturn('أحمد');

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone']);
    $this->router->shouldReceive('getStepQuestion')->with(WorkflowType::Complaint, 'phone')->andReturn('الرجاء إدخال رقم الهاتف:');

    $this->draftRepo->shouldReceive('update')->with($this->draft->id, Mockery::on(fn ($d) => isset($d['answers'])));

    $result = $this->engine->processInput('session-1', 'أحمد', 42);

    expect($result->type)->toBe('workflow_question');
});

it('enforces session isolation - separate sessions have separate drafts', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-A')->andReturn($this->draft);
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-B')->andReturn(null);
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn(null);

    $this->confirmation->shouldReceive('isGlobalCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->andReturn(false);

    $this->validator->shouldReceive('validate')->with('name', 'أحمد')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'أحمد')->andReturn('أحمد');
    $this->validator->shouldReceive('validate')->with('name', 'محمد')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'محمد')->andReturn('محمد');

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone']);
    $this->router->shouldReceive('getStepQuestion')->with(WorkflowType::Complaint, 'phone')->andReturn('الرجاء إدخال رقم الهاتف:');
    $this->router->shouldReceive('getStepQuestion')->with(WorkflowType::Complaint, 'name')->andReturn('الرجاء إدخال اسمك؟');
    $this->router->shouldReceive('getWorkflowStartMessage')->andReturn('ابدأت الآن');

    $this->draftRepo->shouldReceive('update')->with($this->draft->id, Mockery::on(fn ($d) => isset($d['answers'])));
    $this->draftRepo->shouldReceive('create')->andReturn($this->draft);

    $resultA = $this->engine->processInput('session-A', 'أحمد', 1);
    $resultB = $this->engine->processInput('session-B', 'محمد', 2);

    expect($resultA->type)->toBe('workflow_question')
        ->and($resultB->type)->toBe('workflow_not_found');
});

it('global cancel command cancels active workflow', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->with('إلغاء')->andReturn(true);

    $this->draftRepo->shouldReceive('cancel')->with($this->draft->id);

    $result = $this->engine->processInput('session-1', 'إلغاء', 42);

    expect($result->type)->toBe('workflow_cancelled')
        ->and($result->cancelled)->toBeTrue()
        ->and($result->message)->toContain('إلغاء');
});

it('global help command shows help without changing state', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->with('مساعدة')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->with('مساعدة')->andReturn(true);

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone', 'address']);

    $result = $this->engine->processInput('session-1', 'مساعدة', 42);

    expect($result->type)->toBe('workflow_help')
        ->and($result->message)->toContain('مساعدة')
        ->and($result->currentStep)->toBe('name');
});

it('cancel command during confirmation cancels workflow', function () {
    $this->draft->current_step = 'confirm';
    $this->draft->status = 'waiting_confirmation';

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->with('إلغاء')->andReturn(true);

    $this->draftRepo->shouldReceive('cancel')->with($this->draft->id);

    $result = $this->engine->processInput('session-1', 'إلغاء', 42);

    expect($result->type)->toBe('workflow_cancelled')
        ->and($result->cancelled)->toBeTrue();
});

it('help command during confirmation shows help', function () {
    $this->draft->current_step = 'confirm';
    $this->draft->status = 'waiting_confirmation';

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->confirmation->shouldReceive('isGlobalCancel')->with('مساعدة')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->with('مساعدة')->andReturn(true);

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone']);

    $result = $this->engine->processInput('session-1', 'مساعدة', 42);

    expect($result->type)->toBe('workflow_help');
});
