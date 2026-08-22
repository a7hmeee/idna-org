<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Handlers\ContactRequestHandler;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

beforeEach(function (): void {
    $this->engine = Mockery::mock(CitizenWorkflowEngine::class);
    $this->handler = new ContactRequestHandler($this->engine);
});

it('supports only contact_request intent', function (): void {
    expect($this->handler->supports(ChatbotIntent::ContactRequest))->toBeTrue();
    expect($this->handler->supports(ChatbotIntent::CreateComplaint))->toBeFalse();
    expect($this->handler->supports(ChatbotIntent::Greeting))->toBeFalse();
});

it('starts contact request workflow on handle', function (): void {
    $this->engine->shouldReceive('start')
        ->with('session-1', WorkflowType::ContactRequest)
        ->once()
        ->andReturn(new WorkflowStepResultData(
            message: 'ما هو اسمك؟',
            type: 'workflow_question',
        ));

    $incoming = new IncomingChatMessageData(
        message: 'طلب اتصال',
        sessionId: 'session-1',
    );

    $response = $this->handler->handle($incoming, null);

    expect($response->message)->toBe('ما هو اسمك؟');
    expect($response->type)->toBe('workflow_question');
    expect($response->nextConversationState)->toBe(ConversationState::WorkflowCollectingData->value);
});
