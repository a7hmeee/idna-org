<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ClarificationResolverInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\DTOs\ResolvedServiceData;
use App\Domains\Chatbot\Enums\ConversationState;

beforeEach(function (): void {
    $this->resolver = app(ClarificationResolverInterface::class);
    $this->context = app(ConversationContextInterface::class);
});

// =============================================
// Pronoun Resolution
// =============================================

it('resolves pronoun هاي when context has service', function (): void {
    $state = new ConversationStateData(
        currentServiceId: 1,
        currentServiceName: 'رخصة بناء',
        state: ConversationState::Normal,
    );

    $result = $this->resolver->resolvePronoun('هاي', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedServiceId)->toBe(1);
});

it('resolves pronoun قديش رسومها when context has service', function (): void {
    $state = new ConversationStateData(
        currentServiceId: 1,
        currentServiceName: 'رخصة بناء',
        state: ConversationState::Normal,
    );

    $result = $this->resolver->resolvePronoun('قديش رسومها', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedServiceId)->toBe(1);
});

it('resolves pronoun وين أقدمها when context has service', function (): void {
    $state = new ConversationStateData(
        currentServiceId: 1,
        currentServiceName: 'رخصة بناء',
        state: ConversationState::Normal,
    );

    $result = $this->resolver->resolvePronoun('وين أقدمها', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedServiceId)->toBe(1);
});

it('asks for service when pronoun used without context', function (): void {
    $state = new ConversationStateData(state: ConversationState::Normal);

    $result = $this->resolver->resolvePronoun('هاي', $state);

    expect($result->needsClarification)->toBeTrue();
    expect($result->message)->toBe('أي خدمة تقصد؟');
});

it('does not resolve non-pronoun message', function (): void {
    $state = new ConversationStateData(
        currentServiceId: 1,
        currentServiceName: 'رخصة بناء',
    );

    $result = $this->resolver->resolvePronoun('بدي رخصة بناء', $state);

    expect($result->needsClarification)->toBeFalse();
});

// =============================================
// Numeric Selection
// =============================================

it('resolves direct digit selection', function (): void {
    $state = new ConversationStateData(
        state: ConversationState::WaitingForSelection,
        clarificationOptions: [
            ['id' => 1, 'name' => 'خدمة الأولى', 'number' => 1],
            ['id' => 2, 'name' => 'خدمة الثانية', 'number' => 2],
        ],
    );

    $result = $this->resolver->resolveNumericSelection('2', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedOption)->toBe(2);
    expect($result->selectedServiceId)->toBe(2);
});

it('resolves ordinal selection الثانية', function (): void {
    $state = new ConversationStateData(
        state: ConversationState::WaitingForSelection,
        clarificationOptions: [
            ['id' => 1, 'name' => 'خدمة الأولى', 'number' => 1],
            ['id' => 2, 'name' => 'خدمة الثانية', 'number' => 2],
        ],
    );

    $result = $this->resolver->resolveNumericSelection('الثانية', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedOption)->toBe(2);
    expect($result->selectedServiceId)->toBe(2);
});

it('resolves رقم ٢ style', function (): void {
    $state = new ConversationStateData(
        state: ConversationState::WaitingForSelection,
        clarificationOptions: [
            ['id' => 1, 'name' => 'خدمة', 'number' => 1],
            ['id' => 2, 'name' => 'خدمة', 'number' => 2],
            ['id' => 3, 'name' => 'خدمة', 'number' => 3],
        ],
    );

    $result = $this->resolver->resolveNumericSelection('رقم ٢', $state);

    expect($result->needsClarification)->toBeFalse();
    expect($result->selectedOption)->toBe(2);
});

it('returns null for invalid selection out of range', function (): void {
    $state = new ConversationStateData(
        state: ConversationState::WaitingForSelection,
        clarificationOptions: [
            ['id' => 1, 'name' => 'خدمة', 'number' => 1],
        ],
    );

    $result = $this->resolver->resolveNumericSelection('5', $state);

    expect($result)->toBeNull();
});

it('returns null when no pending clarification', function (): void {
    $state = new ConversationStateData(
        state: ConversationState::Normal,
        clarificationOptions: [],
    );

    $result = $this->resolver->resolveNumericSelection('1', $state);

    expect($result)->toBeNull();
});

// =============================================
// Clarification Question Building
// =============================================

it('builds clarification question from candidates', function (): void {
    $candidates = [
        new ResolvedServiceData(id: 1, name: 'خدمة الأولى'),
        new ResolvedServiceData(id: 2, name: 'خدمة الثانية'),
    ];

    $result = $this->resolver->buildClarificationQuestion($candidates);

    expect($result->needsClarification)->toBeTrue();
    expect($result->message)->toContain('هل تقصد');
    expect($result->message)->toContain('1 خدمة الأولى');
    expect($result->message)->toContain('2 خدمة الثانية');
    expect($result->options)->toHaveCount(2);
});

it('returns null when single candidate (no clarification needed)', function (): void {
    $resolver = app(ClarificationResolverInterface::class);
    $candidates = [
        new ResolvedServiceData(id: 1, name: 'خدمة'),
    ];

    $result = $resolver->needsClarification('رسوم الخدمة', $candidates);

    expect($result)->toBeNull();
});

it('returns clarification when multiple candidates', function (): void {
    $resolver = app(ClarificationResolverInterface::class);
    $candidates = [
        new ResolvedServiceData(id: 1, name: 'خدمة الأولى'),
        new ResolvedServiceData(id: 2, name: 'خدمة الثانية'),
    ];

    $result = $resolver->needsClarification('المياه', $candidates);

    expect($result)->not->toBeNull();
    expect($result->needsClarification)->toBeTrue();
});

// =============================================
// Context Switching Tests
// =============================================

it('service switch does not keep previous service', function (): void {
    $sessionId = 'test-switch-'.Str::uuid();
    app(ChatbotConversationRepositoryInterface::class)->create([
        'session_id' => $sessionId,
        'status' => 'active',
    ]);

    $ctx = app(ConversationContextInterface::class);
    $ctx->updateState($sessionId, ['current_service_id' => 1, 'current_service_name' => 'خدمة أولى']);
    $ctx->updateState($sessionId, ['current_service_id' => 2, 'current_service_name' => 'خدمة ثانية']);

    $state = $ctx->getState($sessionId);
    expect($state->currentServiceId)->toBe(2);
    expect($state->currentServiceName)->toBe('خدمة ثانية');
});
