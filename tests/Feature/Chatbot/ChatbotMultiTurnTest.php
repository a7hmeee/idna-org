<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\MunicipalityDemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WaterScheduleSeeder;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(WaterScheduleSeeder::class);
    $this->seed(MunicipalityDemoSeeder::class);
    $this->action = app(ProcessRuleBasedChatMessageAction::class);
});

it('TEST 1: turn1 بدي خدمة then turn2 طلب دعم فني selects the service by name', function (): void {
    $sessionId = 'test-multiturn-category-text';
    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming1);
    expect($response1->actions)->not->toBeEmpty();

    $incoming2 = new IncomingChatMessageData(
        message: 'طلب دعم فني',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->message)->toContain('دعم فني للمواطنين');
    expect($response2->actions)->not->toBeEmpty();
});

it('TEST 2: turn1 بدي خدمة then turn2 2 opens the second category services', function (): void {
    $sessionId = 'test-multiturn-category-numeric';
    $incoming1 = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming1);
    expect($response1->actions)->not->toBeEmpty();

    $incoming2 = new IncomingChatMessageData(
        message: '2',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
    expect($response2->actions)->not->toBeEmpty();
});

it('TEST 3: turn1 مرحبا then turn2 2 starts complaint', function (): void {
    $sessionId = 'test-multiturn-greeting-complaint';
    $incoming1 = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming1);
    expect($response1->actions)->not->toBeEmpty();

    $incoming2 = new IncomingChatMessageData(
        message: '2',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->workflow['type'] ?? null)->not->toBeNull();
    expect($response2->message)->toContain('تقديم الشكوى');
});

it('TEST 4: turn1 مرحبا then turn2 7 runs jobs handler', function (): void {
    $sessionId = 'test-multiturn-greeting-jobs';
    $incoming1 = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: '7',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->message)->not->toContain('ما لقيت خدمة');
});

it('TEST 5: turn1 مرحبا then turn2 10 runs municipality contact handler', function (): void {
    $sessionId = 'test-multiturn-greeting-contact';
    $incoming1 = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming1);

    $incoming2 = new IncomingChatMessageData(
        message: '10',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->type)->toBe('contact');
    expect($response2->message)->toContain('معلومات الاتصال');
});

it('TEST 6: turn1 جدول توزيع المياه then turn2 1 resolves first water area', function (): void {
    $sessionId = 'test-multiturn-water-numeric';
    $incoming1 = new IncomingChatMessageData(
        message: 'جدول توزيع المياه',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming1);
    expect($response1->type)->toBe('clarification');
    expect($response1->clarificationType)->toBe('water_area');

    $incoming2 = new IncomingChatMessageData(
        message: '1',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->type)->toBe('schedule');
    expect($response2->message)->toContain('حي البلد');
});

it('TEST 7: turn1 جدول توزيع المياه then turn2 حي البلد resolves first water area', function (): void {
    $sessionId = 'test-multiturn-water-text';
    $incoming1 = new IncomingChatMessageData(
        message: 'جدول توزيع المياه',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming1);
    expect($response1->type)->toBe('clarification');
    expect($response1->clarificationType)->toBe('water_area');

    $incoming2 = new IncomingChatMessageData(
        message: 'حي البلد',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->type)->toBe('schedule');
    expect($response2->message)->toContain('حي البلد');
});
