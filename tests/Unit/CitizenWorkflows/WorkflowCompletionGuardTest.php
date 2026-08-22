<?php

declare(strict_types=1);

use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\CitizenWorkflows\Contracts\WorkflowDraftRepositoryInterface;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Exceptions\WorkflowIncompleteDataException;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;
use App\Domains\CitizenWorkflows\Services\WorkflowExecutionDispatcher;
use App\Domains\Complaints\Actions\CreateComplaintAction;
use App\Domains\Complaints\Contracts\ComplaintRepositoryInterface;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\ContactRequests\Actions\CreateContactRequestAction;
use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function runFullComplaintWorkflow(string $sessionId, CitizenWorkflowEngine $engine): void
{
    $engine->start($sessionId, WorkflowType::Complaint);
    $engine->processInput($sessionId, 'أحمد محمد');
    $engine->processInput($sessionId, '0591234567');
    $engine->processInput($sessionId, 'طرق');
    $engine->processInput($sessionId, 'مشكلة في الشارع الرئيسي');
    $engine->processInput($sessionId, 'الشارع الرئيسي مليء بالحفر منذ مدة طويلة.');
}

it('creates a complaint end-to-end with a real confirm', function (): void {
    $engine = app(CitizenWorkflowEngine::class);
    $sessionId = 'it-full-'.Str::random(6);

    runFullComplaintWorkflow($sessionId, $engine);

    $result = $engine->processInput($sessionId, 'نعم');

    expect($result->type)->toBe('workflow_completed');
    expect($result->completed)->toBeTrue();

    $complaint = Complaint::query()->latest('id')->first();
    expect($complaint)->not->toBeNull();
    expect($complaint->citizen_name)->toBe('أحمد محمد');
    expect($complaint->phone)->toBe('0591234567');
    expect($complaint->subject)->not->toBe('');

    $draft = app(WorkflowDraftRepositoryInterface::class)->findActiveBySession($sessionId);
    expect($draft)->toBeNull();
});

it('cancels at the confirmation step with a real cancel', function (): void {
    $engine = app(CitizenWorkflowEngine::class);
    $sessionId = 'it-cancel-'.Str::random(6);

    runFullComplaintWorkflow($sessionId, $engine);

    $result = $engine->processInput($sessionId, 'إلغاء');

    expect($result->cancelled)->toBeTrue();
    expect($result->type)->toBe('workflow_cancelled');
    expect(Complaint::query()->count())->toBe(0);
});

it('does not treat "الوظائف" as a confirm while collecting data', function (): void {
    $engine = app(CitizenWorkflowEngine::class);
    $sessionId = 'it-jobs-'.Str::random(6);

    $engine->start($sessionId, WorkflowType::Complaint);

    $result = $engine->processInput($sessionId, 'الوظائف');

    expect($result->type)->not->toBe('workflow_confirm');
    expect($result->type)->not->toBe('workflow_completed');
    expect($result->type)->toBe('workflow_question');
    expect(Complaint::query()->count())->toBe(0);
});

it('does not treat "الخدمات الإلكترونية" as a cancel while collecting data', function (): void {
    $engine = app(CitizenWorkflowEngine::class);
    $sessionId = 'it-es-'.Str::random(6);

    $engine->start($sessionId, WorkflowType::Complaint);

    $result = $engine->processInput($sessionId, 'الخدمات الإلكترونية');

    expect($result->cancelled)->toBeFalse();
    expect($result->type)->toBe('workflow_question');
    expect($result->currentStep)->toBe('phone');
});

it('refuses to complete a confirming draft with missing fields', function (string $partialAnswers): void {
    $engine = app(CitizenWorkflowEngine::class);
    $repository = app(WorkflowDraftRepositoryInterface::class);
    $sessionId = 'it-guard-'.Str::random(8);

    $repository->create([
        'session_id' => $sessionId,
        'workflow_type' => WorkflowType::Complaint->value,
        'current_step' => 'confirm',
        'status' => 'confirming',
        'answers' => json_decode($partialAnswers, true, 512, JSON_THROW_ON_ERROR),
        'expires_at' => now()->addHours(2),
    ]);

    $result = $engine->processInput($sessionId, 'نعم');

    expect($result->type)->toBe('workflow_validation_error');
    expect($result->completed)->toBeFalse();
    expect($result->nextConversationState)->toBe(ConversationState::WorkflowCollectingData->value);
    expect(Complaint::query()->count())->toBe(0);

    $draft = $repository->findActiveBySession($sessionId);
    expect($draft)->not->toBeNull();
    expect($draft->status)->toBe('collecting_data');
    expect($draft->current_step)->not->toBe('confirm');
})->with([
    'missing phone' => json_encode(['citizen_name' => 'أحمد محمد', 'category' => 'طرق', 'subject' => 'عناوان قصير', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
    'missing subject' => json_encode(['citizen_name' => 'أحمد محمد', 'phone' => '0591234567', 'category' => 'طرق', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
    'missing name' => json_encode(['phone' => '0591234567', 'category' => 'طرق', 'subject' => 'عناوان قصير', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
    'missing category' => json_encode(['citizen_name' => 'أحمد محمد', 'phone' => '0591234567', 'subject' => 'عناوان قصير', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
    'empty phone value' => json_encode(['citizen_name' => 'أحمد محمد', 'phone' => '', 'category' => 'طرق', 'subject' => 'عناوان قصير', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
    'empty subject value' => json_encode(['citizen_name' => 'أحمد محمد', 'phone' => '0591234567', 'category' => 'طرق', 'subject' => ' ', 'description' => 'وصف طويل كافٍ للمشكلة الموجودة.']),
]);

it('continues from the exact missing step after the guard fires', function (): void {
    $engine = app(CitizenWorkflowEngine::class);
    $repository = app(WorkflowDraftRepositoryInterface::class);
    $sessionId = 'it-guard-next-'.Str::random(8);

    $repository->create([
        'session_id' => $sessionId,
        'workflow_type' => WorkflowType::Complaint->value,
        'current_step' => 'confirm',
        'status' => 'confirming',
        'answers' => ['citizen_name' => 'أحمد محمد'],
        'expires_at' => now()->addHours(2),
    ]);

    $result = $engine->processInput($sessionId, 'نعم');
    expect($result->type)->toBe('workflow_validation_error');
    expect($result->currentStep)->toBe('phone');

    $result = $engine->processInput($sessionId, '0591234567');
    expect($result->type)->toBe('workflow_question');
    expect($result->currentStep)->toBe('category');

    $result = $engine->processInput($sessionId, 'طرق');
    $result = $engine->processInput($sessionId, 'عنوان قصير للمشكلة');
    $result = $engine->processInput($sessionId, 'وصف كافٍ وطويل لمشكلة الطريق القديمة.');
    expect($result->type)->toBe('workflow_confirm');

    $result = $engine->processInput($sessionId, 'نعم');
    expect($result->type)->toBe('workflow_completed');
    expect(Complaint::query()->count())->toBe(1);
});

it('dispatcher refuses to persist incomplete data at the last line of defense', function (): void {
    $complaintRepository = Mockery::mock(ComplaintRepositoryInterface::class);
    $complaintRepository->shouldNotReceive('create');
    $createComplaint = new CreateComplaintAction($complaintRepository);

    $contactRequestRepository = Mockery::mock(ContactRequestRepositoryInterface::class);
    $contactRequestRepository->shouldNotReceive('create');
    $createContactRequest = new CreateContactRequestAction($contactRequestRepository);

    $dispatcher = new WorkflowExecutionDispatcher($createComplaint, $createContactRequest);

    try {
        $dispatcher->execute(WorkflowType::Complaint, ['citizen_name' => 'أحمد']);
        $this->fail('WorkflowIncompleteDataException was not thrown.');
    } catch (WorkflowIncompleteDataException $e) {
        expect($e->missingStep)->toBe('phone');
    }

    try {
        $dispatcher->execute(WorkflowType::ContactRequest, ['name' => 'أحمد', 'phone' => '0591234567']);
        $this->fail('WorkflowIncompleteDataException was not thrown.');
    } catch (WorkflowIncompleteDataException $e) {
        expect($e->missingStep)->toBe('message');
    }
});
