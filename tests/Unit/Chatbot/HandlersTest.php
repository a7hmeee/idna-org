<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Handlers\GreetingHandler;
use App\Domains\Chatbot\Handlers\ThanksHandler;
use App\Domains\Chatbot\Handlers\UnknownHandler;

// =============================================
// Handler support tests
// =============================================

it('greeting handler supports only greeting intent', function (): void {
    $handler = new GreetingHandler;
    expect($handler->supports(ChatbotIntent::Greeting))->toBeTrue();
    expect($handler->supports(ChatbotIntent::Thanks))->toBeFalse();
    expect($handler->supports(ChatbotIntent::Unknown))->toBeFalse();
});

it('thanks handler supports only thanks intent', function (): void {
    $handler = new ThanksHandler;
    expect($handler->supports(ChatbotIntent::Thanks))->toBeTrue();
    expect($handler->supports(ChatbotIntent::Greeting))->toBeFalse();
    expect($handler->supports(ChatbotIntent::Unknown))->toBeFalse();
});

it('unknown handler supports only unknown intent', function (): void {
    $handler = new UnknownHandler;
    expect($handler->supports(ChatbotIntent::Unknown))->toBeTrue();
    expect($handler->supports(ChatbotIntent::Greeting))->toBeFalse();
    expect($handler->supports(ChatbotIntent::Thanks))->toBeFalse();
});

// =============================================
// Handler behavior tests
// =============================================

it('greeting handler returns welcome message with quick actions', function (): void {
    $handler = new GreetingHandler;
    $incoming = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: 'test-session',
    );
    $response = $handler->handle($incoming, null);

    expect($response->message)->toContain('مرحباً بك في المساعد الذكي لبلدية إذنا');
    expect($response->type)->toBe('text');
    expect($response->actions)->toHaveCount(11);
});

it('thanks handler returns polite response without db access', function (): void {
    $handler = new ThanksHandler;
    $incoming = new IncomingChatMessageData(
        message: 'شكرا',
        sessionId: 'test-session',
    );
    $response = $handler->handle($incoming, null);

    expect($response->message)->toContain('العفو');
    expect($response->type)->toBe('text');
});

it('unknown handler returns clarification options', function (): void {
    $handler = new UnknownHandler;
    $incoming = new IncomingChatMessageData(
        message: 'xyz not understood',
        sessionId: 'test-session',
    );
    $response = $handler->handle($incoming, null);

    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('municipality_main_menu');
    expect($response->actions)->toHaveCount(11);
});
