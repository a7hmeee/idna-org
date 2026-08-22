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

it('expires draft and prevents further processing', function () {
    $draft = createDraft(['expires_at' => Carbon::now()->subHour()]);

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($draft);
    $this->draftRepo->shouldReceive('expire')->with($draft->id);

    $result = $this->engine->processInput('session-1', 'أي بيانات');

    expect($result->type)->toBe('workflow_expired')
        ->and($result->message)->toContain('انتهت صلاحية');
});

it('allows processing for non-expired draft', function () {
    $draft = createDraft(['expires_at' => Carbon::now()->addHour(), 'current_step' => 'name']);

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->andReturn(false);
    $this->confirmation->shouldReceive('isHelp')->andReturn(false);

    $this->validator->shouldReceive('validate')->with('name', 'أحمد')->andReturn(null);
    $this->validator->shouldReceive('normalize')->with('name', 'أحمد')->andReturn('أحمد');

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone', 'address']);
    $this->router->shouldReceive('getStepQuestion')->with(WorkflowType::Complaint, 'phone')->andReturn('الرجاء إدخال رقم الهاتف:');

    $this->draftRepo->shouldReceive('update')->with($draft->id, Mockery::on(fn ($data) => isset($data['answers']) || isset($data['current_step'])));

    $result = $this->engine->processInput('session-1', 'أحمد');

    expect($result->type)->toBe('workflow_question');
});

function createDraft(array $overrides = [])
{
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->session_id = 'session-1';
    $draft->workflow_type = 'complaint';
    $draft->current_step = $overrides['current_step'] ?? 'name';
    $draft->answers = $overrides['answers'] ?? [];
    $draft->status = $overrides['status'] ?? 'collecting_data';
    $draft->expires_at = $overrides['expires_at'] ?? Carbon::now()->addHour();

    return $draft;
}
