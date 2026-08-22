<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Enums\ConversationState;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
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

function routeTurn($action, string $sessionId, string $message): mixed
{
    return $action->execute(new IncomingChatMessageData(message: $message, sessionId: $sessionId));
}

it('TEST 1: greeting opens the home menu without any state lock', function (): void {
    $sessionId = 'router-1-'.uniqid();

    $response1 = routeTurn($this->action, $sessionId, 'مرحبا');
    expect($response1->actions)->not->toBeEmpty();
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::Normal->value);

    $response2 = routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response2->message)->toContain('اختر التصنيف');
});

it('TEST 2: الخدمات الإلكترونية opens the DB categories list', function (): void {
    $sessionId = 'router-2-'.uniqid();

    $response = routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response->message)->toContain('اختر التصنيف');
    expect($response->message)->toContain('رخص البناء');
    expect($response->message)->not->toContain('ما لقيت');
});

it('TEST 3: "شو الخدمات الي عندكو" re-lists categories instead of failing', function (): void {
    $sessionId = 'router-3-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, 'شو الخدمات الي عندكو');
    expect($response->message)->toContain('اختر التصنيف');
    expect($response->message)->not->toContain('ما لقيت');
    expect($response->actions)->not->toBeEmpty();
});

it('TEST 4: "الخدمات الإلكترونية" opens its services, then a service name selects it', function (): void {
    $sessionId = 'router-4-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response->message)->toContain('خدمات الخدمات الإلكترونية:');
    expect($response->message)->toContain('طلب خدمة رقمية');

    $response3 = routeTurn($this->action, $sessionId, 'طلب خدمة رقمية');
    expect($response3->message)->toContain('خدمة إلكترونية متنوعة تشمل طلبات رقمية متعددة');
});

it('TEST 5: "1" opens the first category services', function (): void {
    $sessionId = 'router-5-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, '1');
    expect($response->message)->toContain('خدمات رخص البناء:');
    expect($response->message)->toContain('طلب رخصة بناء جديد');
});

it('TEST 6: "طلب دعم فني" selects the support service by name across categories', function (): void {
    $sessionId = 'router-6-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, 'طلب دعم فني');
    expect($response->message)->toContain('دعم فني للمواطنين');
});

it('TEST 7: "2" opens the second category services', function (): void {
    $sessionId = 'router-7-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, '2');
    expect($response->message)->toContain('خدمات الشؤون الإدارية:');
});

it('TEST 8: incomplete contact request does not block electronic navigation', function (): void {
    $sessionId = 'router-8-'.uniqid();

    routeTurn($this->action, $sessionId, 'طلب اتصال');
    routeTurn($this->action, $sessionId, 'أحمد');

    $response = routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response->workflow)->toBeNull();
    expect($response->message)->toContain('اختر التصنيف');
    expect($response->message)->not->toContain('غير مكتمل');

    expect(WorkflowDraft::query()->where('session_id', $sessionId)->where('status', 'collecting_data')->exists())->toBeTrue();
});

it('TEST 9: "متابعة طلب الاتصال" resumes the suspended draft', function (): void {
    $sessionId = 'router-9-'.uniqid();

    routeTurn($this->action, $sessionId, 'طلب اتصال');
    routeTurn($this->action, $sessionId, 'أحمد');
    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, 'متابعة طلب الاتصال');
    expect($response->workflow['type'])->toBe('contact_request');
    expect($response->message)->toContain('تم استئناف الطلب');
});

it('TEST 10: "إلغاء" mid-workflow truly cancels the draft', function (): void {
    $sessionId = 'router-10-'.uniqid();

    routeTurn($this->action, $sessionId, 'طلب اتصال');
    routeTurn($this->action, $sessionId, 'أحمد');

    $response = routeTurn($this->action, $sessionId, 'إلغاء');
    expect($response->message)->toContain('تم إلغاء الطلب');
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::Normal->value);
    expect(WorkflowDraft::query()->where('session_id', $sessionId)->where('status', 'collecting_data')->exists())->toBeFalse();
});

it('TEST 11: "هاي" inside a workflow does not break the request', function (): void {
    $sessionId = 'router-11-'.uniqid();

    routeTurn($this->action, $sessionId, 'تقديم شكوى');

    $response = routeTurn($this->action, $sessionId, 'هاي');
    expect($response->message)->toContain('بنكمّل طلبك');
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::WorkflowCollectingData->value);

    expect(WorkflowDraft::query()->where('session_id', $sessionId)->where('status', 'collecting_data')->exists())->toBeTrue();

    $response3 = routeTurn($this->action, $sessionId, 'أحمد');
    expect($response3->message)->toContain('الرجاء إدخال رقم الهاتف');
});

it('TEST 12: "تم" outside a workflow is acknowledged without random routing', function (): void {
    $sessionId = 'router-12-'.uniqid();

    routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = routeTurn($this->action, $sessionId, 'تم');
    expect($response->message)->not->toBeEmpty();
    expect($response->message)->not->toContain('ما فهمت');
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::Normal->value);
});

it('TEST 13: unknown service gets a single fallback and no lock', function (): void {
    $sessionId = 'router-13-'.uniqid();

    $response = routeTurn($this->action, $sessionId, 'خدمة غير موجودة تماما');
    $wasFallback = $response->isFallbackResponse
        || in_array($response->type, ['clarification', 'unknown', 'empty_state'], true)
        || str_contains($response->message, 'ما لقيت')
        || str_contains($response->message, 'لم أجد');
    expect($wasFallback)->toBeTrue();

    $response2 = routeTurn($this->action, $sessionId, 'مرحبا');
    expect($response2->actions)->not->toBeEmpty();
});

it('TEST 14: three unknown turns end in a safe main menu, never a loop', function (): void {
    $sessionId = 'router-14-'.uniqid();

    routeTurn($this->action, $sessionId, 'xzvqr');
    routeTurn($this->action, $sessionId, 'qplmn');

    $response = routeTurn($this->action, $sessionId, 'wwxkk');
    expect($response->message)->toContain('واضح إني ما فهمت طلبك');
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::Normal->value);
    expect($this->context->getState($sessionId)->fallbackCount)->toBe(0);
});

it('TEST 15: a service added to the DB appears in the electronic list and is selectable', function (): void {
    $category = ServiceCategory::query()
        ->where('slug', 'alkhdmat-alalktrony')
        ->first() ?? ServiceCategory::query()
        ->where('name', 'الخدمات الإلكترونية')
        ->first();

    expect($category)->not->toBeNull();

    ElectronicService::create([
        'service_category_id' => $category->id,
        'name' => 'طلب تجديد رخصة تجارية',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 99,
        'summary' => 'تجديد الرخصة التجارية إلكترونياً',
    ]);

    $sessionId = 'router-15-'.uniqid();

    $response1 = routeTurn($this->action, $sessionId, 'بدي خدمة');
    expect($response1->message)->toContain('اختر التصنيف');
    expect($response1->message)->not->toContain('طلب تجديد رخصة تجارية');

    $response2 = routeTurn($this->action, $sessionId, 'الخدمات الإلكترونية');
    expect($response2->message)->toContain('طلب تجديد رخصة تجارية');
    expect($response2->message)->not->toContain('ما لقيت');

    $response3 = routeTurn($this->action, $sessionId, 'طلب تجديد رخصة تجارية');
    expect($response3->message)->toContain('تجديد الرخصة التجارية إلكترونياً');
});

it('TEST 15b: a brand new category with published services appears in the list', function (): void {
    $newCategory = ServiceCategory::create([
        'name' => 'تصنيف بلدي جديد',
        'is_public' => true,
        'status' => 'active',
        'sort_order' => 99,
    ]);

    ElectronicService::create([
        'service_category_id' => $newCategory->id,
        'name' => 'طلب ترخيص مخبز تجريبي',
        'description' => 'خدمة إصدار ترخيص مخبز تجريبي عبر البوابة.',
        'status' => 'active',
        'is_public' => true,
        'sort_order' => 1,
    ]);

    $sessionId = 'router-15b-'.uniqid();

    $response1 = routeTurn($this->action, $sessionId, 'بدي خدمة');
    expect($response1->message)->toContain('تصنيف بلدي جديد');

    $response2 = routeTurn($this->action, $sessionId, 'تصنيف بلدي جديد');
    expect($response2->message)->toContain('طلب ترخيص مخبز تجريبي');

    $response3 = routeTurn($this->action, $sessionId, 'طلب ترخيص مخبز تجريبي');
    expect($response3->message)->toContain('خدمة إصدار ترخيص مخبز تجريبي عبر البوابة.');
});

it('TEST 16: "لا" never cancels an in-progress workflow', function (): void {
    $sessionId = 'router-16-'.uniqid();

    routeTurn($this->action, $sessionId, 'تقديم شكوى');
    routeTurn($this->action, $sessionId, 'أحمد');

    $response = routeTurn($this->action, $sessionId, 'لا');
    expect($response->message)->not->toContain('تم إلغاء الطلب');
    expect($this->context->getState($sessionId)->state->value)->toBe(ConversationState::WorkflowCollectingData->value);
    expect(WorkflowDraft::query()->where('session_id', $sessionId)->where('status', 'collecting_data')->exists())->toBeTrue();
});
