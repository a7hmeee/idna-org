<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Services\ChatbotActionRegistry;
use App\Domains\CitizenWorkflows\Enums\WorkflowType;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\News\Models\NewsItem;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);

    $this->category = ServiceCategory::create([
        'name' => 'الخدمات الإلكترونية',
        'slug' => 'alkhdmat-alalktrony',
        'description' => 'الخدمات الإلكترونية',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 0,
    ]);

    $this->service = ElectronicService::create([
        'service_category_id' => $this->category->id,
        'name' => 'طلب رخصة بناء جديد',
        'summary' => 'التقدم للحصول على رخصة بناء جديدة',
        'description' => 'خدمة إلكترونية لتقديم طلب رخصة بناء جديدة',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 0,
        'published_at' => now(),
    ]);

    $this->action = app(ProcessRuleBasedChatMessageAction::class);
});

it('main-menu electronic services opens service discovery', function (): void {
    $incoming = new IncomingChatMessageData(
        message: 'main-menu:electronic-services',
        sessionId: 'test-main-menu-electronic',
    );

    $response = $this->action->execute($incoming);

    expect($response->type)->toBe('text');
    expect($response->actions)->not->toBeEmpty();
    expect($response->message)->not->toContain('لم أجد خدمة');
});

it('electronic list action stops generic pipeline', function (): void {
    $sessionId = 'test-category-stop';
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    expect($response1->type)->toBe('text');
    expect($response1->actions)->not->toBeEmpty();

    $categoryAction = $response1->actions[0];
    $incomingCategory = new IncomingChatMessageData(
        message: $categoryAction['value'],
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incomingCategory);
    expect($response2->type)->toBe('text');
    expect($response2->actions)->not->toBeEmpty();
    expect($response2->message)->toContain('خدمات');
});

it('service selection stops generic pipeline', function (): void {
    $sessionId = 'test-service-stop';
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: $response1->actions[0]['value'],
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);

    expect($response2->type)->toBe('text');
    expect($response2->actions)->not->toBeEmpty();

    $incoming3 = new IncomingChatMessageData(
        message: $response2->actions[0]['value'],
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);

    expect($response3->type)->toBe('text');
    expect($response3->message)->toContain('ممكن تسأل عن أي تفصيل');
});

it('selected building permit never invokes NewsHandler', function (): void {
    $sessionId = 'test-building-permit';
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: $response1->actions[0]['value'],
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    $incoming3 = new IncomingChatMessageData(
        message: $response2->actions[0]['value'],
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);
    expect($response3->message)->not->toContain('آخر الأخبار');
    expect($response3->type)->not->toBe('news');
});

it('active complaint plus tracking navigates without interruption', function (): void {
    $sessionId = 'test-complaint-tracking-navigate';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    expect($response1->workflow['type'])->toBe(WorkflowType::Complaint->value);

    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'تتبع طلب',
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);
    expect($response3->workflow['type'])->toBe(WorkflowType::Tracking->value);
    expect($response3->message)->not->toContain('غير مكتمل');
});

it('active complaint plus facilities suspends the draft and navigates', function (): void {
    $sessionId = 'test-complaint-facilities-suspend';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);
    expect($response3->workflow)->toBeNull();
    expect($response3->message)->toContain('المرافق العامة');
    expect($response3->message)->not->toContain('غير مكتمل');
    expect($response3->message)->toContain('متابعة طلب');

    expect(WorkflowDraft::query()->where('session_id', $sessionId)->where('status', 'collecting_data')->exists())->toBeTrue();
});

it('active complaint plus jobs suspends the draft and navigates', function (): void {
    $sessionId = 'test-complaint-jobs-suspend';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'الوظائف',
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);
    expect($response3->workflow)->toBeNull();
    expect($response3->message)->toContain('الوظائف');
    expect($response3->message)->not->toContain('غير مكتمل');
});

it('second target replaces first interrupt target', function (): void {
    $sessionId = 'test-replace-interrupt-target';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'جدول المياه',
        sessionId: $sessionId,
    );
    $response3 = $this->action->execute($incoming3);
    expect($response3->message)->toContain('جدول المياه');

    $incoming4 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );
    $response4 = $this->action->execute($incoming4);
    expect($response4->message)->toContain('المرافق العامة');
    expect($response4->message)->not->toContain('جدول المياه');
});

it('stale water interrupt target never persists after facilities selection', function (): void {
    $sessionId = 'test-stale-water-target';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'جدول المياه',
        sessionId: $sessionId,
    );
    $response3 = $this->action->execute($incoming3);

    $incoming4 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );
    $response4 = $this->action->execute($incoming4);

    expect($response4->message)->toContain('المرافق العامة');
    expect($response4->message)->not->toContain('جدول المياه');
});

it('empty workflow can switch directly', function (): void {
    $sessionId = 'test-empty-workflow-switch';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    expect($response1->workflow['type'])->toBe(WorkflowType::Complaint->value);

    $incoming2 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->workflow)->toBeNull();
    expect($response2->message)->not->toContain('غير مكتمل');
});

it('partially completed workflow never blocks navigation', function (): void {
    $sessionId = 'test-partial-workflow-navigate';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );

    $response3 = $this->action->execute($incoming3);
    expect($response3->message)->toContain('المرافق العامة');
    expect($response3->message)->not->toContain('غير مكتمل');
});

it('resume marker returns to exact workflow question', function (): void {
    $sessionId = 'test-continue-interrupt';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming3);

    $incoming4 = new IncomingChatMessageData(
        message: 'متابعة الشكوى',
        sessionId: $sessionId,
    );

    $response4 = $this->action->execute($incoming4);
    expect($response4->workflow['type'])->toBe(WorkflowType::Complaint->value);
    expect($response4->message)->toContain('الرجاء إدخال رقم الهاتف');
});

it('compound switch-cancel cancels old draft and runs target handler', function (): void {
    $sessionId = 'test-switch-interrupt';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);

    $incoming3 = new IncomingChatMessageData(
        message: 'المرافق العامة',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming3);

    $incoming4 = new IncomingChatMessageData(
        message: 'إلغاء والانتقال إلى المرافق العامة',
        sessionId: $sessionId,
    );

    $response4 = $this->action->execute($incoming4);
    expect($response4->workflow)->toBeNull();
    expect($response4->message)->toContain('المرافق');
});

it('contact_request is separate from complaint', function (): void {
    $sessionId = 'test-contact-separate';
    $incoming = new IncomingChatMessageData(
        message: 'طلب اتصال',
        sessionId: $sessionId,
    );

    $response = $this->action->execute($incoming);
    expect($response->workflow['type'])->toBe(WorkflowType::ContactRequest->value);
});

it('tracking is separate from complaint', function (): void {
    $sessionId = 'test-tracking-separate';
    $incoming = new IncomingChatMessageData(
        message: 'تتبع طلب',
        sessionId: $sessionId,
    );

    $response = $this->action->execute($incoming);
    expect($response->workflow['type'])->toBe(WorkflowType::Tracking->value);
});

it('menu actions never pass through UnknownHandler', function (): void {
    $actionKeys = [
        'main-menu:electronic-services',
        'main-menu:complaint',
        'main-menu:contact-request',
        'main-menu:tracking',
        'main-menu:water',
        'main-menu:facilities',
        'main-menu:jobs',
        'main-menu:council-members',
        'main-menu:council-decisions',
        'main-menu:municipality-contact',
    ];

    foreach ($actionKeys as $key) {
        $incoming = new IncomingChatMessageData(
            message: $key,
            sessionId: 'test-menu-'.md5($key),
        );

        $response = $this->action->execute($incoming);
        expect($response->type)->not->toBe('unknown');
        expect($response->message)->not->toContain('ما فهمت');
    }
});

it('structured category response does not duplicate categories in message', function (): void {
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-category-dup',
    );

    $response = $this->action->execute($incoming);
    $categoryName = $response->actions[0]['label'];

    $incoming2 = new IncomingChatMessageData(
        message: $categoryName,
        sessionId: 'test-category-dup',
    );

    $response2 = $this->action->execute($incoming2);
    $messageLines = explode("\n", $response2->message);
    $categoryCount = 0;
    foreach ($messageLines as $line) {
        if (str_contains($line, $categoryName)) {
            $categoryCount++;
        }
    }

    expect($categoryCount)->toBeLessThan(3);
});

it('structured service response does not duplicate services', function (): void {
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: 'test-service-dup',
    );

    $response = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: $response->actions[0]['value'],
        sessionId: 'test-service-dup',
    );

    $response2 = $this->action->execute($incoming2);
    $serviceName = $response2->actions[0]['label'];

    $incoming3 = new IncomingChatMessageData(
        message: $serviceName,
        sessionId: 'test-service-dup',
    );

    $response3 = $this->action->execute($incoming3);
    $messageLines = explode("\n", $response3->message);
    $serviceCount = 0;
    foreach ($messageLines as $line) {
        if (str_contains($line, $serviceName)) {
            $serviceCount++;
        }
    }

    expect($serviceCount)->toBeLessThan(3);
});

it('workflow question renders once', function (): void {
    $sessionId = 'test-workflow-once';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    $messageLines = explode("\n", $response2->message);
    $uniqueLines = array_unique($messageLines);

    expect(count($uniqueLines))->toBe(count($messageLines));
});

it('actions deduplicated by key', function (): void {
    $registry = app(ChatbotActionRegistry::class);
    $all = $registry->all();

    $keys = array_keys($all);
    expect(count($keys))->toBe(count(array_unique($keys)));
});

it('faker news excluded from public queries', function (): void {
    $publicNews = NewsItem::query()
        ->where('status', 'published')
        ->where('is_public', true)
        ->where('publish_at', '<=', now())
        ->get();

    foreach ($publicNews as $news) {
        expect($news->title_ar)->not->toMatch('/[a-zA-Z]{5,}/');
        expect($news->summary)->not->toMatch('/[a-zA-Z]{10,}/');
    }
});

it('normal state after workflow completion', function (): void {
    $sessionId = 'test-normal-after-completion';
    $incoming = new IncomingChatMessageData(
        message: 'تقديم شكوى',
        sessionId: $sessionId,
    );

    $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: 'أحمد',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming2);
    $incoming3 = new IncomingChatMessageData(
        message: '0501234567',
        sessionId: $sessionId,
    );
    $this->action->execute($incoming3);
    $incoming4 = new IncomingChatMessageData(
        message: 'نعم',
        sessionId: $sessionId,
    );

    $response4 = $this->action->execute($incoming4);
    expect($response4->nextConversationState)->toBe(ConversationState::Normal->value);
});

it('one user request produces one bot response', function (): void {
    $sessionId = 'test-one-response';
    $incoming = new IncomingChatMessageData(
        message: 'مرحبا',
        sessionId: $sessionId,
    );

    $response = $this->action->execute($incoming);
    expect($response->message)->not->toBeEmpty();
    expect($response->type)->not->toBeEmpty();
});

it('numeric clarification uses active typed list only', function (): void {
    $sessionId = 'test-numeric-clarification';
    $incoming = new IncomingChatMessageData(
        message: 'بدي خدمة',
        sessionId: $sessionId,
    );

    $response1 = $this->action->execute($incoming);
    $incoming2 = new IncomingChatMessageData(
        message: '99',
        sessionId: $sessionId,
    );

    $response2 = $this->action->execute($incoming2);
    expect($response2->message)->toContain('مش قادر أحدد الخدمة المطلوبة');
});
