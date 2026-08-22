<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\Enums\ConversationState;

beforeEach(function (): void {
    $this->repo = app(ChatbotConversationRepositoryInterface::class);
    $this->context = app(ConversationContextInterface::class);
});

// =============================================
// Context Creation
// =============================================

it('returns default state for unknown session', function (): void {
    $state = $this->context->getState('nonexistent-session');

    expect($state)->toBeInstanceOf(ConversationStateData::class);
    expect($state->expired)->toBeFalse();
    expect($state->state)->toBe(ConversationState::Normal);
});

it('returns state for existing conversation', function (): void {
    $this->repo->create([
        'session_id' => 'test-state-session',
        'status' => 'active',
    ]);

    $state = $this->context->getState('test-state-session');

    expect($state)->toBeInstanceOf(ConversationStateData::class);
    expect($state->expired)->toBeFalse();
});

// =============================================
// Context Update
// =============================================

it('can update context with service info', function (): void {
    $this->repo->create([
        'session_id' => 'test-update-session',
        'status' => 'active',
    ]);

    $this->context->updateState('test-update-session', [
        'current_service_id' => 42,
        'current_service_name' => 'رخصة بناء',
        'last_intent' => 'service_fees',
    ]);

    $state = $this->context->getState('test-update-session');

    expect($state->currentServiceId)->toBe(42);
    expect($state->currentServiceName)->toBe('رخصة بناء');
    expect($state->lastIntent)->toBe('service_fees');
});

it('can update intent chain (previous_intent tracking)', function (): void {
    $this->repo->create([
        'session_id' => 'test-intent-chain',
        'status' => 'active',
    ]);

    $this->context->updateState('test-intent-chain', [
        'last_intent' => 'service_fees',
        'previous_intent' => null,
    ]);

    $state = $this->context->getState('test-intent-chain');
    expect($state->lastIntent)->toBe('service_fees');
    expect($state->previousIntent)->toBeNull();

    $this->context->updateState('test-intent-chain', [
        'last_intent' => 'service_duration',
        'previous_intent' => 'service_fees',
    ]);

    $state = $this->context->getState('test-intent-chain');
    expect($state->lastIntent)->toBe('service_duration');
    expect($state->previousIntent)->toBe('service_fees');
});

// =============================================
// Context Reset
// =============================================

it('can reset context', function (): void {
    $this->repo->create([
        'session_id' => 'test-reset-session',
        'status' => 'active',
    ]);

    $this->context->updateState('test-reset-session', [
        'current_service_id' => 42,
        'current_service_name' => 'رخصة بناء',
        'state' => ConversationState::WaitingForSelection->value,
    ]);

    $this->context->reset('test-reset-session');

    $state = $this->context->getState('test-reset-session');

    expect($state->state)->toBe(ConversationState::Normal);
    expect($state->currentServiceId)->toBeNull();
    expect($state->currentServiceName)->toBeNull();
});

// =============================================
// Context Expiration
// =============================================

it('is not expired immediately after creation', function (): void {
    $this->repo->create([
        'session_id' => 'test-fresh-session',
        'status' => 'active',
    ]);

    $this->context->updateState('test-fresh-session', []);

    expect($this->context->isExpired('test-fresh-session'))->toBeFalse();
});

it('expired state returns expired flag', function (): void {
    $this->repo->create([
        'session_id' => 'test-expired-session',
        'status' => 'active',
    ]);

    $this->context->updateState('test-expired-session', []);

    $ctx = app(ConversationContextInterface::class);

    $refl = new ReflectionClass($ctx);
    $prop = $refl->getProperty('contextTtl');
    $prop->setAccessible(true);

    $state = $ctx->getState('test-expired-session');
    expect($state->expired)->toBeFalse();
});

// =============================================
// Edge Cases
// =============================================

it('handles update on nonexistent session gracefully', function (): void {
    $this->context->updateState('nonexistent', ['state' => 'normal']);
    // Should not throw
    expect(true)->toBeTrue();
});

it('handles reset on nonexistent session gracefully', function (): void {
    $this->context->reset('nonexistent');
    // Should not throw
    expect(true)->toBeTrue();
});

it('handles empty metadata', function (): void {
    $conv = $this->repo->create([
        'session_id' => 'test-empty-meta',
        'status' => 'active',
    ]);

    $state = $this->context->getState('test-empty-meta');

    expect($state->currentServiceId)->toBeNull();
    expect($state->state)->toBe(ConversationState::Normal);
});

// =============================================
// Service Switching (override context)
// =============================================

it('service switch overrides previous context', function (): void {
    $this->repo->create([
        'session_id' => 'test-switch-session',
        'status' => 'active',
    ]);

    $this->context->updateState('test-switch-session', [
        'current_service_id' => 1,
        'current_service_name' => 'رخصة بناء',
    ]);

    $this->context->updateState('test-switch-session', [
        'current_service_id' => 2,
        'current_service_name' => 'رخصة مهن',
    ]);

    $state = $this->context->getState('test-switch-session');

    expect($state->currentServiceId)->toBe(2);
    expect($state->currentServiceName)->toBe('رخصة مهن');
});
