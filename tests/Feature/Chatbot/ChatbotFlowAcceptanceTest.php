<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
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
    $this->context = app(ConversationContextService::class);
});

function runTurn($action, string $sessionId, string $message): mixed
{
    return $action->execute(new IncomingChatMessageData(message: $message, sessionId: $sessionId));
}

it('FLOW A: بدي خدمة opens the DB-driven categories list', function (): void {
    $sessionId = 'accept-a-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'بدي خدمة');
    expect($response1->message)->toContain('اختر التصنيف');
    expect($response1->message)->not->toContain('ما لقيت');
    expect($response1->actions)->not->toBeEmpty();

    $labels = array_map(fn (array $a) => $a['label'] ?? '', $response1->actions);
    expect($labels)->toContain('رخص البناء');
    expect($labels)->toContain('الخدمات الإلكترونية');
    expect($labels)->not->toContain('طلب خدمة رقمية');
    expect($labels)->not->toContain('طلب دعم فني');
});

it('FLOW B: بدي خدمة then 2 selects the second category by position', function (): void {
    $sessionId = 'accept-b-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, '2');
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
    expect($response2->message)->toContain('طلب صرف مكافأة نهاية الخدمة');
    expect($response2->actions)->not->toBeEmpty();
});

it('FLOW B2: Arabic-Indic digit ٢ selects the second category', function (): void {
    $sessionId = 'accept-b2-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, '٢');
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
});

it('FLOW B3: ordinal الثاني selects the second category', function (): void {
    $sessionId = 'accept-b3-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, 'الثاني');
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
});

it('FLOW B4: whitespace-padded category label selects the category', function (): void {
    $sessionId = 'accept-b4-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, ' الشؤون  الإدارية ');
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
});

it('FLOW C: جدول توزيع المياه then حي البلد resolves persisted water area', function (): void {
    $sessionId = 'accept-c-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'جدول توزيع المياه');
    expect($response1->clarificationType)->toBe('water_area');

    $response2 = runTurn($this->action, $sessionId, 'حي البلد');
    expect($response2->message)->toContain('حي البلد');
    expect($response2->message)->not->toContain('ما قدرت أحدد');
});

it('FLOW D: جدول توزيع المياه then 1 resolves the first water area', function (): void {
    $sessionId = 'accept-d-'.uniqid();

    runTurn($this->action, $sessionId, 'جدول توزيع المياه');

    $response2 = runTurn($this->action, $sessionId, '1');
    expect($response2->message)->toContain('حي البلد');
});

it('FLOW E: gpk then 3 resolves the third persisted option (complaint)', function (): void {
    $sessionId = 'accept-e-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'gpk');
    expect($response1->needsClarification)->toBeTrue();

    $response2 = runTurn($this->action, $sessionId, '3');
    expect($response2->message)->toContain('تقديم الشكوى');
});

it('FLOW F: مرحبا then 1 opens the categories, then 2 opens a category', function (): void {
    $sessionId = 'accept-f-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'مرحبا');
    expect($response1->actions)->not->toBeEmpty();

    $response2 = runTurn($this->action, $sessionId, '1');
    expect($response2->message)->toContain('اختر التصنيف');

    $state = $this->context->getState($sessionId);
    expect($state->state->value)->toBe('waiting_for_service_selection');
    expect($state->clarificationOptions)->not->toBeEmpty();

    $response3 = runTurn($this->action, $sessionId, '2');
    expect($response3->message)->toContain('خدمات الشؤون الإدارية:');
    expect($response3->message)->not->toContain('ما قدرت أحدد');
});

it('ELECTRONIC-1: category then service then full DB details', function (): void {
    $sessionId = 'accept-elec-1-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response1->message)->toContain('اختر التصنيف');
    expect($response1->message)->toContain('رخص البناء');
    expect($response1->message)->not->toContain('طلب خدمة رقمية');

    $response2 = runTurn($this->action, $sessionId, '1');
    expect($response2->message)->toContain('خدمات رخص البناء:');
    expect($response2->message)->toContain('طلب رخصة بناء جديد');

    $response3 = runTurn($this->action, $sessionId, '1');
    expect($response3->message)->toContain('تتيح للمواطنين التقدم للحصول على رخصة بناء جديدة');
    expect($response3->message)->toContain('المتطلبات:');
    expect($response3->message)->toContain('الرسوم:');
    expect($response3->message)->toContain('مدة الخدمة: 5-7 أيام عمل');
    expect($response3->message)->toContain('خطوات التقديم:');
    expect($response3->message)->toContain('رابط التقديم:');
    expect($response3->actions)->not->toBeEmpty();
});

it('ELECTRONIC-2: category label selection works on next turn', function (): void {
    $sessionId = 'accept-elec-2-'.uniqid();

    runTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response2 = runTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response2->message)->toContain('خدمات الخدمات الإلكترونية:');
    expect($response2->message)->toContain('طلب خدمة رقمية');
    expect($response2->message)->toContain('طلب دعم فني');
});

it('ELECTRONIC-3: electronic flow never shows municipal categories', function (): void {
    $sessionId = 'accept-elec-3-'.uniqid();

    $response = runTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    $labels = array_map(fn (array $a) => $a['label'] ?? '', $response->actions);

    expect($labels)->not->toContain('خدمات البلدية');
    expect($labels)->not->toContain('طلب خدمة رقمية');
    expect($labels)->toContain('رخص البناء');
});

it('ELECTRONIC-4: main menu 1 opens categories, then 1 opens a category', function (): void {
    $sessionId = 'accept-elec-4-'.uniqid();

    runTurn($this->action, $sessionId, 'مرحبا');

    $response2 = runTurn($this->action, $sessionId, '1');
    expect($response2->message)->toContain('اختر التصنيف');

    $response3 = runTurn($this->action, $sessionId, '1');
    expect($response3->message)->toContain('خدمات رخص البناء:');
    expect($response3->message)->toContain('طلب رخصة بناء جديد');
});

it('TRUSTED-1: service:{id} typed key shows the real DB details directly', function (): void {
    $sessionId = 'accept-trust-1-'.uniqid();

    $serviceId = ElectronicService::query()
        ->where('name', 'طلب خدمة رقمية')
        ->value('id');

    expect($serviceId)->not->toBeNull();

    $response = runTurn($this->action, $sessionId, "service:{$serviceId}");
    expect($response->message)->toContain('خدمة إلكترونية متنوعة تشمل طلبات رقمية متعددة');
    expect($response->message)->toContain('مدة الخدمة: حسب نوع الخدمة');
    expect($response->actions)->not->toBeEmpty();
});

it('TRUSTED-2: main-menu:electronic-services key opens categories, then 9 opens electronic services', function (): void {
    $sessionId = 'accept-trust-2-'.uniqid();

    runTurn($this->action, $sessionId, 'main-menu:electronic-services');

    $response2 = runTurn($this->action, $sessionId, '9');
    expect($response2->message)->toContain('خدمات الخدمات الإلكترونية:');

    $response3 = runTurn($this->action, $sessionId, '1');
    expect($response3->message)->toContain('طلب خدمة رقمية');
});

it('TRUSTED-3: water-area:1 typed key answers the schedule directly', function (): void {
    $sessionId = 'accept-trust-3-'.uniqid();

    $response = runTurn($this->action, $sessionId, 'water-area:1');
    expect($response->message)->toContain('حي البلد');
});

it('CATEGORY-NAME: typing a category name opens its services from the DB', function (): void {
    $sessionId = 'accept-cat-svc-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, 'الشؤون الإدارية');
    expect($response2->message)->toContain('خدمات الشؤون الإدارية:');
    expect($response2->message)->not->toContain('ما لقيت');
    expect($response2->actions)->not->toBeEmpty();
});

it('MUNICIPAL-GONE: الخدمات البلدية has no dedicated flow', function (): void {
    $sessionId = 'accept-mun-gone-'.uniqid();

    $response = runTurn($this->action, $sessionId, 'الخدمات البلدية');
    expect($response->message)->not->toContain('خدمات البلدية:');
});

it('GUIDED-FALLBACK-1: unknown service name gets the guided fallback once', function (): void {
    $sessionId = 'accept-gf-1-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, 'xyz خدمة مش موجودة');
    expect($response2->message)->toContain('مش قادر أحدد الخدمة المطلوبة 😅');
    expect($response2->actions)->not->toBeEmpty();

    $state = $this->context->getState($sessionId);
    expect($state->state->value)->toBe('waiting_for_service_selection');
});

it('GUIDED-FALLBACK-2: repeated unknown selection re-lists the categories', function (): void {
    $sessionId = 'accept-gf-2-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');
    runTurn($this->action, $sessionId, 'xyz خدمة مش موجودة');
    runTurn($this->action, $sessionId, 'abc خدمة تانية');

    $response4 = runTurn($this->action, $sessionId, 'xyz خدمة تالتة');
    expect($response4->message)->toContain('اختر التصنيف');
    expect($response4->message)->not->toContain('مش قادر أحدد');
    expect($response4->actions)->not->toBeEmpty();
});

it('DB-DRIVEN-1: a service added to the DB appears in its category without code changes', function (): void {
    $category = ServiceCategory::query()
        ->where('slug', 'alkhdmat-alalktrony')
        ->first() ?? ServiceCategory::query()
        ->where('name', 'الخدمات الإلكترونية')
        ->first();

    expect($category)->not->toBeNull();

    ElectronicService::create([
        'service_category_id' => $category->id,
        'name' => 'طلب إصدار شهادة إقامة',
        'description' => 'خدمة إصدار شهادة إقامة رقمية جديدة للمواطنين.',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 99,
    ]);

    $sessionId = 'accept-db-1-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'بدي خدمة');
    expect($response1->message)->not->toContain('طلب إصدار شهادة إقامة');

    $response2 = runTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response2->message)->toContain('طلب إصدار شهادة إقامة');

    $response3 = runTurn($this->action, $sessionId, 'طلب إصدار شهادة إقامة');
    expect($response3->message)->toContain('خدمة إصدار شهادة إقامة رقمية جديدة للمواطنين.');
});

it('DB-DRIVEN-2: a new category with published services appears in the list', function (): void {
    $municipalCategory = ServiceCategory::create([
        'name' => 'خدمات تجريبية جديدة',
        'is_public' => true,
        'status' => 'active',
        'sort_order' => 99,
    ]);

    ElectronicService::create([
        'service_category_id' => $municipalCategory->id,
        'name' => 'طلب ترخيص مخبز',
        'description' => 'خدمة إصدار ترخيص مخبز تجريبي.',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 1,
    ]);

    $sessionId = 'accept-db-2-'.uniqid();

    $response1 = runTurn($this->action, $sessionId, 'بدي خدمة');
    expect($response1->message)->toContain('خدمات تجريبية جديدة');

    $response2 = runTurn($this->action, $sessionId, 'خدمات تجريبية جديدة');
    expect($response2->message)->toContain('طلب ترخيص مخبز');

    $response3 = runTurn($this->action, $sessionId, 'طلب ترخيص مخبز');
    expect($response3->message)->toContain('خدمة إصدار ترخيص مخبز تجريبي.');
});

it('SERVICE-PROPERTY: property action shows the real DB fees', function (): void {
    $sessionId = 'accept-prop-'.uniqid();

    $serviceId = ElectronicService::query()
        ->where('name', 'طلب رخصة بناء جديد')
        ->value('id');

    expect($serviceId)->not->toBeNull();

    runTurn($this->action, $sessionId, 'بدي خدمة');
    runTurn($this->action, $sessionId, '1');
    runTurn($this->action, $sessionId, '1');

    $response = runTurn($this->action, $sessionId, "service-action:fees:{$serviceId}");
    expect($response->message)->toContain('رسوم خدمة');
    expect(json_encode($response->items, JSON_UNESCAPED_UNICODE))->toContain('رسوم الإصدار: 100');
});

it('NO-DUPLICATE: resolved selection never shows the generic fallback menu', function (): void {
    $sessionId = 'accept-dup-'.uniqid();

    runTurn($this->action, $sessionId, 'بدي خدمة');

    $response2 = runTurn($this->action, $sessionId, '2');
    expect($response2->message)->not->toContain('ما قدرت أحدد طلبك بالضبط');
});

it('CONTEXT-PERSIST: options survive between turns (no stale overwrite)', function (): void {
    $sessionId = 'accept-ctx-'.uniqid();

    runTurn($this->action, $sessionId, 'مرحبا');
    runTurn($this->action, $sessionId, '1');

    $state = $this->context->getState($sessionId);
    expect($state->state->value)->toBe('waiting_for_service_selection');
    expect(count($state->clarificationOptions))->toBe(9);
    expect($state->pendingField)->toBe('service_category');

    runTurn($this->action, $sessionId, '2');

    $after = $this->context->getState($sessionId);
    expect($after->state->value)->toBe('waiting_for_service_selection');
    expect($after->pendingField)->toBe('electronic_service');

    runTurn($this->action, $sessionId, '1');

    $done = $this->context->getState($sessionId);
    expect($done->state->value)->toBe('normal');
});
