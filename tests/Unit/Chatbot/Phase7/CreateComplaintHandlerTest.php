<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Handlers\CreateComplaintHandler;
use App\Domains\CitizenWorkflows\DTOs\WorkflowStepResultData;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Services\CitizenWorkflowEngine;

beforeEach(function (): void {
    $this->engine = Mockery::mock(CitizenWorkflowEngine::class);
    $this->handler = new CreateComplaintHandler($this->engine);
});

it('supports only create_complaint intent', function (): void {
    expect($this->handler->supports(ChatbotIntent::CreateComplaint))->toBeTrue();
    expect($this->handler->supports(ChatbotIntent::ContactRequest))->toBeFalse();
    expect($this->handler->supports(ChatbotIntent::Greeting))->toBeFalse();
});

it('starts complaint workflow on handle', function (): void {
    $this->engine->shouldReceive('start')
        ->with('session-1', WorkflowType::Complaint)
        ->once()
        ->andReturn(new WorkflowStepResultData(
            message: 'ما هو اسمك الكامل؟',
            type: 'workflow_question',
        ));

    $incoming = new IncomingChatMessageData(
        message: 'أريد تقديم شكوى',
        sessionId: 'session-1',
    );

    $response = $this->handler->handle($incoming, null);

    expect($response->message)->toBe('ما هو اسمك الكامل؟');
    expect($response->type)->toBe('workflow_question');
    expect($response->nextConversationState)->toBe(ConversationState::WorkflowCollectingData->value);
});
