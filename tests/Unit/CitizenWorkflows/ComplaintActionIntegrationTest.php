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
    $this->draft->current_step = 'confirm';
    $this->draft->answers = ['name' => 'أحمد', 'phone' => '0555000111', 'details' => 'مشكلة'];
    $this->draft->status = 'waiting_confirmation';
    $this->draft->expires_at = Carbon::now()->addHour();
});

it('executes complaint action on confirmation', function () {
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('نعيم')->andReturn(false);
    $this->confirmation->shouldReceive('isCancel')->with('نعيم')->andReturn(false);
    $this->confirmation->shouldReceive('isConfirm')->with('نعيم')->andReturn(true);

    $this->router->shouldReceive('getConfirmationMessage')->never();

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($this->draft);
    $this->draftRepo->shouldReceive('complete')->with(1);
    $this->draftRepo->shouldReceive('update')->with(1, Mockery::any());

    $this->router->shouldReceive('getSteps')->with(WorkflowType::Complaint)->andReturn(['name', 'phone', 'details']);
    $this->router->shouldReceive('getSuccessDetails')->andReturn(['submission_date' => '2025-01-01', 'status_label' => 'تم التقديم']);
    $this->router->shouldReceive('getSuccessActions')->andReturn([]);

    $execResult = new Complaint;
    $execResult->tracking_number = 'CMP-001';
    $execResult->id = 42;

    $this->executor->shouldReceive('execute')
        ->with(WorkflowType::Complaint, $this->draft->answers)
        ->andReturn($execResult);

    $this->router->shouldReceive('getSuccessMessage')
        ->with(WorkflowType::Complaint, $this->draft->answers, $execResult)
        ->andReturn('تم تقديم الشكوى رقم CMP-001 بنجاح.');

    $result = $this->engine->processInput('session-1', 'نعم');

    expect($result->completed)->toBeTrue()
        ->and($result->type)->toBe('workflow_completed')
        ->and($result->trackingNumber)->toBe('CMP-001')
        ->and($result->finalEntityType)->toBe('complaint')
        ->and($result->finalEntityId)->toBe(42)
        ->and($result->message)->toBe('تم تقديم الشكوى رقم CMP-001 بنجاح.');
});
