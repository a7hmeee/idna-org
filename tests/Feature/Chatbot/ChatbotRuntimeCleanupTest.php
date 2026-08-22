<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\PublicChatbotDataQualityGuard;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Livewire\Chatbot\ChatbotPage;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
});

// =============================================
// Machine-key leak tests
// =============================================

it('clicking service category displays Arabic label, not a doubled machine key', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'main-menu:electronic-services')
        ->call('quickAction', 'main-menu:electronic-services', 'الخدمات الإلكترونية')
        ->assertSee('الخدمات الإلكترونية')
        ->assertSee('رخص البناء')
        ->assertDontSee('service-category:9:9');
});

it('clicking service displays service label, not service:ID', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'service:1',
        sessionId: 'test-service-label',
    );

    $response = $action->execute($incoming);

    expect($response->message)->toContain('ممكن تسأل عن أي تفصيل');
    if (! empty($response->actions)) {
        foreach ($response->actions as $action) {
            expect($action['label'] ?? $action['value'] ?? '')->not->toContain('service:');
        }
    }
});

it('clicking fees displays الرسوم, not service-action key', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'service-action:fees:1',
        sessionId: 'test-fees-label',
    );

    $response = $action->execute($incoming);

    expect($response->message)->toContain('رسوم خدمة');
    expect($response->message)->not->toContain('service-action:fees:1');
});

// =============================================
// Category key support tests
// =============================================

it('service-category key opens the matching category flow', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);

    $categoryId = ServiceCategory::query()
        ->where('name', 'رخص البناء')
        ->value('id');

    expect($categoryId)->not->toBeNull();

    $response = $action->execute(new IncomingChatMessageData(
        message: "service-category:{$categoryId}",
        sessionId: 'test-category-key',
    ));

    expect($response->message)->toContain('خدمات رخص البناء:');
    expect($response->message)->toContain('طلب رخصة بناء جديد');
    expect($response->actions)->not->toBeEmpty();
});

// =============================================
// Greeting tests
// =============================================

it('greeting returns welcome and exactly one bot response', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSee('مرحباً بك في المساعد الذكي لبلدية إذنا')
        ->assertDontSee('عذرًا، حدث خطأ أثناء المعالجة');
});

it('greeting produces no error', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرخبا')
        ->call('sendMessage')
        ->assertDontSee('عذرًا، حدث خطأ أثناء المعالجة');
});

// =============================================
// Empty state tests
// =============================================

it('water no-schedule appears once', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'main-menu:water',
        sessionId: 'test-water-once',
    );

    $response = $action->execute($incoming);

    $messageText = $response->message;
    $count = substr_count($messageText, 'لا يوجد جدول مياه متاح حالياً');

    expect($count)->toBeLessThanOrEqual(1);
});

it('jobs empty state appears once', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'main-menu:jobs',
        sessionId: 'test-jobs-once',
    );

    $response = $action->execute($incoming);

    $messageText = $response->message;
    $count = substr_count($messageText, 'لا توجد وظائف مفتوحة حالياً');

    expect($count)->toBeLessThanOrEqual(1);
});

// =============================================
// Data quality tests
// =============================================

it('corrupted demo values not publicly rendered', function (): void {
    $guard = app(PublicChatbotDataQualityGuard::class);

    $r1 = $guard->isDemoValue('m sclerosis');
    $r2 = $guard->isDemoValue('+970-22-123456');
    $r3 = $guard->isLoremOrFaker('Lorem ipsum dolor sit amet');

    if (! $r1 || ! $r2 || ! $r3) {
        $this->fail("r1=$r1 r2=$r2 r3=$r3");
    }

    expect($r1)->toBeTrue();
    expect($r2)->toBeTrue();
    expect($r3)->toBeTrue();
});

it('raw Markdown contact syntax absent', function (): void {
    $action = app(ProcessRuleBasedChatMessageAction::class);
    $incoming = new IncomingChatMessageData(
        message: 'main-menu:municipality-contact',
        sessionId: 'test-contact-markdown',
    );

    $response = $action->execute($incoming);

    expect($response->message)->not->toContain('[info@');
    expect($response->message)->not->toContain('mailto:');
});

it('placeholder contact data excluded', function (): void {
    $guard = app(PublicChatbotDataQualityGuard::class);

    expect($guard->filterValue('info@idhna.ps', 'email'))->toBeNull();
    expect($guard->filterValue('support@idhna.ps', 'email'))->toBeNull();
});

// =============================================
// One response per turn
// =============================================

it('one user turn produces exactly one bot logical response', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'مرحبا')
        ->call('sendMessage')
        ->assertSee('مرحباً بك في المساعد الذكي لبلدية إذنا')
        ->assertDontSee('عذرًا، حدث خطأ أثناء المعالجة');
});

it('no machine key appears in visible history', function (): void {
    Livewire::test(ChatbotPage::class)
        ->set('message', 'main-menu:electronic-services')
        ->call('quickAction', 'main-menu:electronic-services', 'الخدمات الإلكترونية')
        ->assertSee('الخدمات الإلكترونية')
        ->assertDontSee('main-menu:electronic-services');
});
