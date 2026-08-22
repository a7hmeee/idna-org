<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Handlers\TrackWorkflowHandler;
use App\Domains\CitizenWorkflows\Contracts\WorkflowTrackingResolverInterface;
use App\Domains\CitizenWorkflows\DTOs\WorkflowTrackingResultData;

beforeEach(function (): void {
    $this->resolver = Mockery::mock(WorkflowTrackingResolverInterface::class);
    $this->handler = new TrackWorkflowHandler($this->resolver);
});

it('supports only track_workflow intent', function (): void {
    expect($this->handler->supports(ChatbotIntent::TrackWorkflow))->toBeTrue();
    expect($this->handler->supports(ChatbotIntent::CreateComplaint))->toBeFalse();
    expect($this->handler->supports(ChatbotIntent::Greeting))->toBeFalse();
});

it('asks for tracking number when not provided', function (): void {
    $incoming = new IncomingChatMessageData(
        message: 'تتبع طلبي',
        sessionId: 'session-1',
    );

    $response = $this->handler->handle($incoming, null);

    expect($response->message)->toContain('رقم المتابعة');
});

it('returns complaint status when tracking number found', function (): void {
    $trackingData = new WorkflowTrackingResultData(
        trackingNumber: 'CMP-ABC123',
        type: 'complaint',
        status: 'under_review',
        statusLabel: 'قيد المراجعة',
        submittedDate: '2026-07-30',
        lastPublicUpdate: '2026-08-01',
        department: 'قسم الهندسة',
        subject: 'شكوى إنارة',
    );

    $this->resolver->shouldReceive('resolveByTrackingNumber')
        ->with('CMP-ABC123')
        ->once()
        ->andReturn($trackingData);

    $incoming = new IncomingChatMessageData(
        message: 'CMP-ABC123',
        sessionId: 'session-1',
    );

    $response = $this->handler->handle($incoming, null);

    expect($response->type)->toBe('workflow_tracking');
    expect($response->message)->toContain('CMP-ABC123');
    expect($response->message)->toContain('النوع: شكوى');
    expect($response->message)->toContain('الحالة: قيد المراجعة');
    expect($response->message)->toContain('تاريخ التقديم: 2026-07-30');
    expect($response->message)->toContain('القسم: قسم الهندسة');
    expect($response->message)->toContain('الموضوع: شكوى إنارة');
});

it('returns not found message when tracking number not found', function (): void {
    $this->resolver->shouldReceive('resolveByTrackingNumber')
        ->with('CMP-UNKNOWN')
        ->once()
        ->andReturnNull();

    $incoming = new IncomingChatMessageData(
        message: 'CMP-UNKNOWN',
        sessionId: 'session-1',
    );

    $response = $this->handler->handle($incoming, null);

    expect($response->type)->toBe('workflow_not_found');
    expect($response->message)->toContain('لم يتم العثور');
});
