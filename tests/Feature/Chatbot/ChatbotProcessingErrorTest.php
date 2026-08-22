<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\ChatResponseData;
use App\Livewire\Chatbot\ChatbotPage;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
});

const CHATBOT_GENERIC_ERROR = 'عذرًا، حدث خطأ أثناء المعالجة. الرجاء المحاولة مرة أخرى.';

it('does not set the generic error on a normal request', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('validationError', null)
        ->assertDontSee(CHATBOT_GENERIC_ERROR);
});

it('sets the generic error once when the pipeline throws', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): never
            {
                throw new RuntimeException('boom: simulated pipeline failure');
            }
        };
    });

    $component = Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage');

    $component->assertSet('validationError', CHATBOT_GENERIC_ERROR);
    $component->assertSet('loading', false);

    $html = preg_replace('/wire:snapshot="[^"]*"/', '', $component->html());
    expect(substr_count((string) $html, CHATBOT_GENERIC_ERROR))->toBe(1);
});

it('restores the typed input and does not duplicate the citizen message on failure', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): never
            {
                throw new RuntimeException('boom');
            }
        };
    });

    $component = Livewire::test(ChatbotPage::class)
        ->set('message', 'سؤال خاص')
        ->call('sendMessage');

    // The typed input is restored to the composer so the citizen can retry.
    $component->assertSet('message', 'سؤال خاص');

    // The failed citizen message is rolled back: no user bubble is left behind.
    $userMessages = collect($component->get('messages'))->where('role', 'user')->values();
    expect($userMessages->count())->toBe(0);
});

it('clears a previous generic error on the next successful request', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): never
            {
                throw new RuntimeException('boom');
            }
        };
    });

    $component = Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage');

    $component->assertSet('validationError', CHATBOT_GENERIC_ERROR);

    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(message: 'تمت المعالجة بنجاح', type: 'text');
            }
        };
    });

    $component->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('validationError', null)
        ->assertDontSee(CHATBOT_GENERIC_ERROR)
        ->assertSee('تمت المعالجة بنجاح');
});

it('does not persist the generic error as a chat message', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): never
            {
                throw new RuntimeException('boom');
            }
        };
    });

    $component = Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage');

    $botMessages = collect($component->get('messages'))->where('role', 'bot')->values();
    expect($botMessages->every(fn (array $msg): bool => ! str_contains($msg['content'], CHATBOT_GENERIC_ERROR)))->toBeTrue();
});

it('keeps the error cleared after a success followed by another success', function (): void {
    app()->bind(ProcessRuleBasedChatMessageAction::class, function (): object {
        return new class
        {
            public function execute($incoming): ChatResponseData
            {
                return new ChatResponseData(message: 'رد عادي', type: 'text');
            }
        };
    });

    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSet('validationError', null)
        ->set('message', 'كيف الحال')
        ->call('sendMessage')
        ->assertSet('validationError', null)
        ->assertDontSee(CHATBOT_GENERIC_ERROR);
});
