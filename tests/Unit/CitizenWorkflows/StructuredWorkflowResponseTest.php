<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;

it('includes structured metadata fields on success', function () {
    $result = new WorkflowStepResultData(
        message: 'تم تقديم الشكوى بنجاح.',
        type: 'workflow_success',
        completed: true,
        trackingNumber: 'TRK-001',
        workflowType: 'complaint',
        draftId: 1,
        currentStep: null,
        totalSteps: 5,
        completedSteps: 5,
        progressPercent: 100.0,
        finalEntityType: 'complaint',
        finalEntityId: 42,
    );

    expect($result->workflowType)->toBe('complaint')
        ->and($result->draftId)->toBe(1)
        ->and($result->totalSteps)->toBe(5)
        ->and($result->completedSteps)->toBe(5)
        ->and($result->progressPercent)->toBe(100.0)
        ->and($result->trackingNumber)->toBe('TRK-001')
        ->and($result->finalEntityType)->toBe('complaint')
        ->and($result->finalEntityId)->toBe(42);
});

it('includes progress data on workflow question', function () {
    $result = new WorkflowStepResultData(
        message: 'الرجاء إدخال رقم الهاتف:',
        type: 'workflow_question',
        workflowType: 'contact_request',
        draftId: 2,
        currentStep: 'phone',
        totalSteps: 4,
        completedSteps: 1,
        progressPercent: 25.0,
    );

    expect($result->currentStep)->toBe('phone')
        ->and($result->totalSteps)->toBe(4)
        ->and($result->completedSteps)->toBe(1)
        ->and($result->progressPercent)->toBe(25.0);
});

it('includes confirmation actions on confirmation step', function () {
    $result = new WorkflowStepResultData(
        message: 'هل البيانات صحيحة؟',
        type: 'workflow_confirmation',
        confirming: true,
        workflowType: 'complaint',
        actions: [
            ['label' => 'نعم، تأكيد', 'value' => 'نعم'],
            ['label' => 'لا، إلغاء', 'value' => 'لا'],
        ],
    );

    expect($result->confirming)->toBeTrue()
        ->and($result->actions)->toHaveCount(2)
        ->and($result->actions[0]['label'])->toContain('نعم');
});

it('includes cancelled flag and type on cancel', function () {
    $result = new WorkflowStepResultData(
        message: 'تم إلغاء الطلب.',
        type: 'workflow_cancelled',
        cancelled: true,
        workflowType: 'complaint',
        draftId: 1,
    );

    expect($result->cancelled)->toBeTrue()
        ->and($result->type)->toBe('workflow_cancelled')
        ->and($result->workflowType)->toBe('complaint');
});

it('carries arbitrary metadata', function () {
    $result = new WorkflowStepResultData(
        message: 'Test',
        type: 'test',
        metadata: ['audit_id' => 99, 'notified' => true],
    );

    expect($result->metadata)->toHaveKey('audit_id', 99)
        ->and($result->metadata)->toHaveKey('notified', true);
});
