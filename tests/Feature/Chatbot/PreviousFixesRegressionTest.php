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

function regressionTurn($action, string $sessionId, string $message): mixed
{
    return $action->execute(new IncomingChatMessageData(message: $message, sessionId: $sessionId));
}

// =============================================
// Phase 7 regressions: previous fixes still hold
// =============================================

it('REGRESSION-1: main menu then electronic services word opens categories', function (): void {
    $sessionId = 'reg-1-'.uniqid();

    regressionTurn($this->action, $sessionId, 'مرحبا');
    $response = regressionTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    expect($response->message)->toContain('اختر التصنيف');
    expect($response->message)->toContain('رخص البناء');
    expect($response->actions)->not->toBeEmpty();
    expect($this->context->getState($sessionId)->pendingField)->toBe('service_category');
});

it('REGRESSION-2: category by button key service-category:{realId} opens its services', function (): void {
    $categoryId = ServiceCategory::query()->where('name', 'رخص البناء')->value('id');
    expect($categoryId)->not->toBeNull();

    $sessionId = 'reg-2-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, "service-category:{$categoryId}");

    expect($response->message)->toContain('خدمات رخص البناء:');
    expect($response->message)->toContain('طلب رخصة بناء جديد');
    expect($response->actions)->not->toBeEmpty();
    expect($this->context->getState($sessionId)->pendingField)->toBe('electronic_service');
});

it('REGRESSION-3: electronic services category via button key service-category:{realId} works (prod id 9 shape)', function (): void {
    $categoryId = ServiceCategory::query()->where('name', 'الخدمات الإلكترونية')->value('id');
    expect($categoryId)->not->toBeNull();

    $sessionId = 'reg-3-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, "service-category:{$categoryId}");

    expect($response->message)->toContain('خدمات الخدمات الإلكترونية:');
    expect($response->message)->toContain('طلب خدمة رقمية');
    expect($response->message)->toContain('طلب دعم فني');
});

it('REGRESSION-4: service by button key service:{realId} shows full DB details (prod id 29 shape)', function (): void {
    $serviceId = ElectronicService::query()->where('name', 'طلب رخصة بناء جديد')->value('id');
    expect($serviceId)->not->toBeNull();

    $sessionId = 'reg-4-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, "service:{$serviceId}");

    expect($response->message)->toContain('مدة الخدمة: 5-7 أيام عمل');
    expect($response->message)->toContain('المتطلبات:');
    expect($response->message)->toContain('الرسوم:');
    expect($response->actions)->not->toBeEmpty();
    expect($this->context->getState($sessionId)->state->value)->toBe('normal');
});

it('REGRESSION-5: category by typed name still opens services', function (): void {
    $sessionId = 'reg-5-'.uniqid();

    regressionTurn($this->action, $sessionId, 'بدي خدمة');
    $response = regressionTurn($this->action, $sessionId, 'الشؤون الإدارية');

    expect($response->message)->toContain('خدمات الشؤون الإدارية:');
    expect($response->message)->toContain('طلب صرف مكافأة نهاية الخدمة');
});

it('REGRESSION-6: service by typed name still opens details', function (): void {
    $sessionId = 'reg-6-'.uniqid();

    regressionTurn($this->action, $sessionId, 'بدي خدمة');
    regressionTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    $response = regressionTurn($this->action, $sessionId, 'طلب خدمة رقمية');

    expect($response->message)->toContain('خدمة إلكترونية متنوعة تشمل طلبات رقمية متعددة');
});

it('REGRESSION-7: الوظائف typed while browsing services does not cancel or derail the selection', function (): void {
    $sessionId = 'reg-7-'.uniqid();

    regressionTurn($this->action, $sessionId, 'بدي خدمة');
    $response = regressionTurn($this->action, $sessionId, 'الوظائف');

    expect($response->message)->not->toContain('تم إلغاء الطلب');
    expect($this->context->getState($sessionId)->state->value)->toBe('waiting_for_service_selection');
});

it('REGRESSION-8: الخدمات الإلكترونية typed while browsing services does not cancel the selection', function (): void {
    $sessionId = 'reg-8-'.uniqid();

    regressionTurn($this->action, $sessionId, 'بدي خدمة');
    $response = regressionTurn($this->action, $sessionId, 'الخدمات الإلكترونية');

    expect($response->message)->not->toContain('تم إلغاء الطلب');
    expect($this->context->getState($sessionId)->state->value)->toBe('waiting_for_service_selection');
});

it('REGRESSION-9: complaint quick action starts the complaint workflow', function (): void {
    $sessionId = 'reg-9-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, 'تقديم شكوى');

    expect($response->metadata['workflow_type'])->toBe('complaint');
    expect($response->message)->toContain('اسمك');
});

it('REGRESSION-10: contact request quick action starts the contact workflow', function (): void {
    $sessionId = 'reg-10-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, 'طلب اتصال');

    expect($response->metadata['workflow_type'])->toBe('contact_request');
});

it('REGRESSION-11: track request flow responds with a tracking prompt', function (): void {
    $sessionId = 'reg-11-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, 'متابعة طلب');

    expect($response->message)->toContain('رقم المتابعة');
});

it('REGRESSION-12: jobs flow works', function (): void {
    $sessionId = 'reg-12-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, 'الوظائف');

    expect($response->message)->not->toContain('تم إلغاء الطلب');
    expect($response->message)->not->toBeEmpty();
});

it('REGRESSION-13: water schedule flow works end-to-end', function (): void {
    $sessionId = 'reg-13-'.uniqid();

    $response1 = regressionTurn($this->action, $sessionId, 'جدول توزيع المياه');
    expect($response1->clarificationType)->toBe('water_area');

    $response2 = regressionTurn($this->action, $sessionId, '1');
    expect($response2->message)->toContain('حي البلد');
});

it('REGRESSION-14: municipality contact flow works and shows Arabic content', function (): void {
    $sessionId = 'reg-14-'.uniqid();

    $response = regressionTurn($this->action, $sessionId, 'أعضاء المجلس البلدي');

    expect($response->message)->not->toContain('تم إلغاء الطلب');
    expect($response->message)->not->toContain('mailto:');
    expect($response->message)->not->toBeEmpty();
});
