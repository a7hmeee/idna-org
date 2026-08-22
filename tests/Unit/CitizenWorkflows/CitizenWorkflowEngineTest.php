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
use App\Domains\Complaints\Models\Complaint;

beforeEach(function (): void {
    $this->draftRepository = Mockery::mock(WorkflowDraftRepositoryInterface::class);
    $this->draftRepository->shouldIgnoreMissing();
    $this->router = Mockery::mock(CitizenWorkflowRouterInterface::class);
    $this->router->shouldIgnoreMissing();
    $this->validator = new WorkflowValidator;
    $this->confirmationFlow = new ConfirmationFlow;
    $this->executionDispatcher = Mockery::mock(WorkflowExecutionDispatcher::class);

    $this->engine = new CitizenWorkflowEngine(
        $this->draftRepository,
        $this->router,
        $this->validator,
        $this->confirmationFlow,
        $this->executionDispatcher,
    );
});

it('starts a new workflow when no existing draft', function (): void {
    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturnNull();

    $this->router->shouldReceive('getSteps')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn(['citizen_name', 'phone']);

    $this->router->shouldReceive('getInitialQuestion')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn('ما هو اسمك؟');

    $this->draftRepository->shouldReceive('create')
        ->once()
        ->andReturn(new WorkflowDraft);

    $result = $this->engine->start('session-1', WorkflowType::Complaint);

    expect($result->message)->toBe('ما هو اسمك؟');
    expect($result->type)->toBe('workflow_question');
    expect($result->completed)->toBeFalse();
});

it('prompts to resume and sets resume decision step when existing draft found', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->current_step = 'citizen_name';

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->draftRepository->shouldReceive('update')
        ->with(1, ['current_step' => '_resume_decision'])
        ->once();

    $result = $this->engine->start('session-1', WorkflowType::Complaint);

    expect($result->message)->toContain('يوجد طلب قيد الإكمال');
    expect($result->type)->toBe('workflow_resume');
});

it('handles resume decision confirm and returns next question', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';
    $draft->current_step = '_resume_decision';
    $draft->data = ['citizen_name' => 'أحمد'];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->router->shouldReceive('getSteps')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn(['citizen_name', 'phone']);

    $this->router->shouldReceive('getStepQuestion')
        ->with(WorkflowType::Complaint, 'phone')
        ->once()
        ->andReturn('ما هو هاتفك؟');

    $this->draftRepository->shouldReceive('update')
        ->with(1, ['current_step' => 'phone', 'status' => 'collecting_data'])
        ->once();

    $result = $this->engine->processInput('session-1', 'نعم');

    expect($result->type)->toBe('workflow_question');
    expect($result->message)->toContain('تم استئناف الطلب');
    expect($result->message)->toContain('هاتفك');
});

it('handles resume decision to confirmation when all steps complete', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';
    $draft->current_step = '_resume_decision';
    $draft->data = ['citizen_name' => 'أحمد', 'phone' => '0599', 'category' => 'طرق', 'subject' => 'مشكلة', 'description' => 'شرح مفصل'];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->router->shouldReceive('getSteps')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn(['citizen_name', 'phone', 'category', 'subject', 'description']);

    $this->router->shouldReceive('getConfirmationMessage')
        ->once()
        ->andReturn('الرجاء تأكيد البيانات');

    $this->draftRepository->shouldReceive('update')
        ->with(1, ['current_step' => 'confirm', 'status' => 'confirming'])
        ->once();

    $result = $this->engine->processInput('session-1', 'نعم');

    expect($result->type)->toBe('workflow_confirm');
    expect($result->confirming)->toBeTrue();
    expect($result->message)->toContain('تم استئناف الطلب');
});

it('handles resume decision cancel', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';
    $draft->current_step = '_resume_decision';
    $draft->data = [];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->draftRepository->shouldReceive('cancel')
        ->with(1)
        ->once();

    $result = $this->engine->processInput('session-1', 'لا');

    expect($result->cancelled)->toBeTrue();
    expect($result->type)->toBe('workflow_cancelled');
});

it('returns error when no active draft for processInput', function (): void {
    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturnNull();

    $result = $this->engine->processInput('session-1', 'أحمد');

    expect($result->type)->toBe('workflow_not_found');
    expect($result->message)->toContain('لا يوجد طلب نشط');
});

it('cancels draft on cancel input', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->draftRepository->shouldReceive('cancel')
        ->with(1)
        ->once();

    $result = $this->engine->processInput('session-1', 'إلغاء');

    expect($result->cancelled)->toBeTrue();
    expect($result->type)->toBe('workflow_cancelled');
    expect($result->message)->toContain('إلغاء');
});

it('processes input and advances to next step', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';
    $draft->current_step = 'citizen_name';
    $draft->data = [];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->router->shouldReceive('getSteps')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn(['citizen_name', 'phone', 'description']);

    $this->router->shouldReceive('getStepQuestion')
        ->with(WorkflowType::Complaint, 'phone')
        ->once()
        ->andReturn('ما هو هاتفك؟');

    $this->draftRepository->shouldReceive('update')
        ->with(1, Mockery::on(function (array $data): bool {
            return isset($data['answers']['citizen_name']) && $data['answers']['citizen_name'] === 'أحمد';
        }))
        ->once()
        ->andReturn($draft);

    $this->draftRepository->shouldReceive('update')
        ->with(1, ['current_step' => 'phone'])
        ->once();

    $result = $this->engine->processInput('session-1', 'أحمد');

    expect($result->type)->toBe('workflow_question');
    expect($result->message)->toContain('هاتفك');
    expect($result->completed)->toBeFalse();
});

it('moves to confirmation after last step', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'in_progress';
    $draft->workflow_type = 'complaint';
    $draft->current_step = 'description';
    $draft->data = ['citizen_name' => 'أحمد', 'phone' => '0599', 'subject' => 'مشكلة', 'description' => 'شرح مفصل'];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->router->shouldReceive('getSteps')
        ->with(WorkflowType::Complaint)
        ->once()
        ->andReturn(['citizen_name', 'phone', 'subject', 'description']);

    $this->router->shouldReceive('getConfirmationMessage')
        ->with(WorkflowType::Complaint, Mockery::type('array'))
        ->once()
        ->andReturn('الرجاء تأكيد البيانات');

    $this->draftRepository->shouldReceive('update')
        ->with(1, Mockery::on(function (array $data): bool {
            return ($data['current_step'] ?? null) === 'confirm' && ($data['status'] ?? null) === 'confirming';
        }))
        ->once()
        ->andReturn($draft);

    $result = $this->engine->processInput('session-1', 'شرح مفصل جدا للمشكلة');

    expect($result->type)->toBe('workflow_confirm');
    expect($result->confirming)->toBeTrue();
    expect($result->message)->toContain('تأكيد');
});

it('executes workflow on confirmation', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->session_id = 'session-1';
    $draft->status = 'confirming';
    $draft->workflow_type = 'complaint';
    $draft->data = ['citizen_name' => 'أحمد', 'phone' => '0599', 'subject' => 'مشكلة', 'description' => 'شرح'];
    $draft->created_at = now();

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->times(2)
        ->andReturn($draft);

    $execResult = new Complaint;
    $execResult->tracking_number = 'CMP-001';
    $execResult->id = 42;

    $this->executionDispatcher->shouldReceive('execute')
        ->with(WorkflowType::Complaint, Mockery::type('array'))
        ->once()
        ->andReturn($execResult);

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['citizen_name', 'phone', 'subject', 'description']);
    $this->router->shouldReceive('getSuccessDetails')->andReturn(['submission_date' => '2025-01-01', 'status_label' => 'تم التقديم']);
    $this->router->shouldReceive('getSuccessActions')->andReturn([]);

    $this->router->shouldReceive('getSuccessMessage')
        ->once()
        ->andReturn('تم تقديم شكواك بنجاح.');

    $this->draftRepository->shouldReceive('complete')
        ->with(1)
        ->once();

    $this->draftRepository->shouldReceive('update')
        ->with(1, Mockery::any());

    $result = $this->engine->processInput('session-1', 'نعم');

    expect($result->type)->toBe('workflow_completed');
    expect($result->completed)->toBeTrue();
});

it('cancels workflow during confirmation', function (): void {
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->status = 'confirming';
    $draft->workflow_type = 'complaint';
    $draft->data = [];

    $this->draftRepository->shouldReceive('findActiveBySession')
        ->with('session-1')
        ->once()
        ->andReturn($draft);

    $this->draftRepository->shouldReceive('cancel')
        ->with(1)
        ->once();

    $result = $this->engine->processInput('session-1', 'لا');

    expect($result->cancelled)->toBeTrue();
    expect($result->type)->toBe('workflow_cancelled');
});
