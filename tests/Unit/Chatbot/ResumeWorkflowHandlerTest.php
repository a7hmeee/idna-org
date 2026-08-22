<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Handlers\ResumeWorkflowHandler;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

it('supports ResumeWorkflow intent', function () {
    $engine = Mockery::mock(CitizenWorkflowEngine::class);
    $handler = new ResumeWorkflowHandler($engine);

    expect($handler->supports(ChatbotIntent::ResumeWorkflow))->toBeTrue()
        ->and($handler->supports(ChatbotIntent::CancelWorkflow))->toBeFalse();
});

it('delegates to engine resume and returns WorkflowCollectingData state', function () {
    $engine = Mockery::mock(CitizenWorkflowEngine::class);
    $handler = new ResumeWorkflowHandler($engine);

    $engine->expects('resume')
        ->with('session-1', 42)
        ->andReturn(new WorkflowStepResultData(
            message: "تم استئناف الطلب.\nالرجاء إدخال الاسم:",
            type: 'workflow_resumed',
            workflowType: 'complaint',
            draftId: 1,
            currentStep: 'name',
            totalSteps: 5,
            completedSteps: 2,
            progressPercent: 40.0,
        ));

    $message = new IncomingChatMessageData(
        message: 'متابعة',
        sessionId: 'session-1',
        userId: 42,
    );

    $response = $handler->handle($message, null);

    expect($response)
        ->toBeInstanceOf(ChatResponseData::class)
        ->message->toContain('تم استئناف الطلب')
        ->type->toBe('workflow_resumed')
        ->nextConversationState->toBe(ConversationState::WorkflowCollectingData->value);

    expect($response->metadata['workflow_type'])->toBe('complaint')
        ->and($response->metadata['current_step'])->toBe('name')
        ->and($response->metadata['progress_percent'])->toBe(40.0);
});
