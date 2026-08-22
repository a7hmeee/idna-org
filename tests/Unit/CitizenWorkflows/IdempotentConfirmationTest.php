<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Contracts\CitizenWorkflowRouterInterface;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
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

it('processes duplicate confirmation idempotently', function () {
    $draft = createIdempotentDraft();

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('نعم')->andReturn(false);
    $this->confirmation->shouldReceive('isConfirm')->with('نعم')->andReturn(true);

    // Second call hits DB transaction where draft is re-fetched — already processed
    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn(
        createIdempotentDraft(['status' => 'completed']),
    );

    $result = $this->engine->processInput('session-1', 'نعم');

    expect($result->type)->toBe('workflow_failure')
        ->and($result->message)->toContain('تمت معالجة هذا الطلب مسبقاً');
});

it('rejects non-confirm responses during confirmation', function () {
    $draft = createIdempotentDraft();

    $this->draftRepo->shouldReceive('findActiveBySession')->with('session-1')->andReturn($draft);

    $this->confirmation->shouldReceive('isGlobalCancel')->with('xyz')->andReturn(false);
    $this->confirmation->shouldReceive('isConfirm')->with('xyz')->andReturn(false);

    $this->router->shouldReceive('getConfirmationMessage')->andReturn('بيانات الشكوى: ...');

    $result = $this->engine->processInput('session-1', 'xyz');

    expect($result->type)->toBe('workflow_confirm')
        ->and($result->message)->toContain('نعم');
});

function createIdempotentDraft(array $overrides = [])
{
    $draft = new WorkflowDraft;
    $draft->id = 1;
    $draft->session_id = 'session-1';
    $draft->workflow_type = 'complaint';
    $draft->current_step = 'confirm';
    $draft->answers = ['name' => 'أحمد', 'phone' => '0555000111'];
    $draft->status = $overrides['status'] ?? 'waiting_confirmation';
    $draft->expires_at = Carbon::now()->addHour();

    return $draft;
}
