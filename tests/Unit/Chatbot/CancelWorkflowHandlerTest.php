<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Handlers\CancelWorkflowHandler;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

it('supports CancelWorkflow intent', function () {
    $engine = Mockery::mock(CitizenWorkflowEngine::class);
    $handler = new CancelWorkflowHandler($engine);

    expect($handler->supports(ChatbotIntent::CancelWorkflow))->toBeTrue()
        ->and($handler->supports(ChatbotIntent::CreateComplaint))->toBeFalse();
});

it('delegates to engine cancel and returns Normal state', function () {
    $engine = Mockery::mock(CitizenWorkflowEngine::class);
    $handler = new CancelWorkflowHandler($engine);

    $engine->expects('cancel')
        ->with('session-1', 42)
        ->andReturn(new WorkflowStepResultData(
            message: 'تم إلغاء الطلب.',
            type: 'workflow_cancelled',
            cancelled: true,
            workflowType: 'complaint',
        ));

    $message = new IncomingChatMessageData(
        message: 'إلغاء',
        sessionId: 'session-1',
        userId: 42,
    );

    $response = $handler->handle($message, null);

    expect($response)
        ->toBeInstanceOf(ChatResponseData::class)
        ->message->toBe('تم إلغاء الطلب.')
        ->type->toBe('workflow_cancelled')
        ->nextConversationState->toBe(ConversationState::Normal->value);

    expect($response->metadata['workflow_cancelled'])->toBeTrue();
});
