<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\CitizenWorkflows\Models\WorkflowDraft;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use Database\Seeders\DepartmentSeeder;
use Database\Seeders\ElectronicServicesSeeder;
use Database\Seeders\MunicipalityDemoSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WaterScheduleSeeder;
use Illuminate\Support\Str;

beforeEach(function (): void {
    $this->seed(RolePermissionSeeder::class);
    $this->seed(DepartmentSeeder::class);
    $this->seed(ElectronicServicesSeeder::class);
    $this->seed(WaterScheduleSeeder::class);
    $this->seed(MunicipalityDemoSeeder::class);
    $this->action = app(ProcessRuleBasedChatMessageAction::class);
    $this->context = app(ConversationContextService::class);
});

function auditTurn($action, string $sessionId, string $message): mixed
{
    return $action->execute(new IncomingChatMessageData(message: $message, sessionId: $sessionId));
}

function auditDraft(string $sessionId): ?WorkflowDraft
{
    return WorkflowDraft::query()->where('session_id', $sessionId)->first();
}

// =============================================
// P1/P17/P18: track-request status renders without error
// =============================================

it('AUDIT-1: tracking a real complaint renders the status label, no generic error', function (): void {
    $tracking = 'CMP-'.strtoupper(Str::random(10));

    Complaint::query()->create([
        'tracking_number' => $tracking,
        'citizen_name' => 'أحمد العسود',
        'phone' => '0599000000',
        'category' => 'infrastructure',
        'subject' => 'شكوى إنارة',
        'description' => 'انارة مكسورة امام المنزل',
        'status' => ComplaintStatus::UnderReview->value,
        'submitted_at' => now(),
    ]);

    $sessionId = 'audit-1-'.uniqid();

    $prompt = auditTurn($this->action, $sessionId, 'متابعة طلب');
    expect($prompt->message)->toContain('رقم المتابعة');

    $response = auditTurn($this->action, $sessionId, $tracking);

    expect($response->type)->toBe('workflow_tracking');
    expect($response->message)->toContain($tracking);
    expect($response->message)->toContain('النوع: شكوى');
    expect($response->message)->toContain('الحالة: قيد المراجعة');
    expect($response->message)->toContain('الموضوع: شكوى إنارة');
    expect($response->message)->toContain('تاريخ التقديم');
    expect($response->message)->not->toContain('عذرًا، حدث خطأ أثناء المعالجة');
});

it('AUDIT-2: tracking an unknown number is a graceful not-found, no error', function (): void {
    $sessionId = 'audit-2-'.uniqid();

    auditTurn($this->action, $sessionId, 'متابعة طلب');
    $response = auditTurn($this->action, $sessionId, 'CMP-NOT-EXISTS-9999999');

    expect($response->type)->toBe('workflow_not_found');
    expect($response->message)->toContain('المتابعة: ');
    expect($response->message)->not->toContain('عذرًا، حدث خطأ أثناء المعالجة');
});

// =============================================
// P2/P3/P16: explicit domain switch interrupts the workflow
// =============================================

it('AUDIT-3: explicit domain switch interrupts collection instead of being saved as an answer', function (): void {
    $sessionId = 'audit-3-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    auditTurn($this->action, $sessionId, 'أحمد العسود');

    $response = auditTurn($this->action, $sessionId, 'أعضاء المجلس البلدي');

    expect($response->type)->toBe('workflow_interrupt_confirmation');
    expect($response->message)->toContain('المجلس البلدي');
    expect($this->context->getState($sessionId)->state->value)->toBe('workflow_interrupting');
    expect($response->actions)->not->toBeEmpty();
});

it('AUDIT-4: interrupting does not lose the draft when the user continues', function (): void {
    $sessionId = 'audit-4-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    auditTurn($this->action, $sessionId, 'أحمد العسود');
    auditTurn($this->action, $sessionId, 'الوظائف');

    $response = auditTurn($this->action, $sessionId, 'متابعة');

    expect($response->message)->toContain('هاتف');

    $draft = auditDraft($sessionId);
    expect($draft)->not->toBeNull();
    expect($draft->answers['citizen_name'] ?? null)->toBe('احمد العسود');
    expect($draft->answers['phone'] ?? null)->toBeNull();
});

it('AUDIT-5: confirming the interrupt cancels the draft and navigates to the target', function (): void {
    $sessionId = 'audit-5-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    auditTurn($this->action, $sessionId, 'أحمد العسود');
    auditTurn($this->action, $sessionId, 'الوظائف');

    $response = auditTurn($this->action, $sessionId, 'إلغاء والانتقال');

    expect($response->type)->not->toBe('workflow_interrupt_confirmation');
    expect($response->message)->not->toContain('مش قادر');
    $draft = auditDraft($sessionId);
    expect($draft?->status)->toBe('cancelled');
});

it('AUDIT-6: الوظائف typed at the very first step switches directly (nothing to lose)', function (): void {
    $sessionId = 'audit-6-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');

    $response = auditTurn($this->action, $sessionId, 'الوظائف');

    expect($response->type)->not->toBe('workflow_interrupt_confirmation');
    expect($response->message)->not->toContain('مش قادر');
    $draft = auditDraft($sessionId);
    expect($draft?->status)->toBe('cancelled');
});

it('AUDIT-7: a workflow-neutral message stays a normal answer (no interruption)', function (): void {
    $sessionId = 'audit-7-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    $response = auditTurn($this->action, $sessionId, 'مشكلة أمام منزلي');

    expect($response->type)->toBe('workflow_question');
    expect($response->message)->toContain('هاتف');
    expect(auditDraft($sessionId)?->answers['citizen_name'] ?? null)->toBe('مشكلة امام منزلي');
});

// =============================================
// P5/P6: expanded cancel words end the workflow cleanly
// =============================================

it('AUDIT-8: ما بدي أكمل cancels the workflow and returns to a working main menu', function (): void {
    $sessionId = 'audit-8-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    auditTurn($this->action, $sessionId, 'أحمد العسود');

    $response = auditTurn($this->action, $sessionId, 'ما بدي أكمل');

    expect($response->message)->toContain('تم إلغاء الطلب');
    expect(auditDraft($sessionId)?->status)->toBe('cancelled');
    expect($this->context->getState($sessionId)->pendingField)->toBe('municipality_main_menu');

    $menuPick = auditTurn($this->action, $sessionId, '1');
    expect($menuPick->message)->toContain('اختار التصنيف');
    expect($menuPick->message)->toContain('رخص البناء');
});

it('AUDIT-9: خلاص cancels the workflow', function (): void {
    $sessionId = 'audit-9-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    $response = auditTurn($this->action, $sessionId, 'خلاص');

    expect($response->message)->toContain('تم إلغاء الطلب');
    expect(auditDraft($sessionId)?->status)->toBe('cancelled');
});

// =============================================
// P7: the main menu is active right after cancel/home/fallback
// =============================================

it('AUDIT-10: typing a menu number right after إلغاء opens the target on the first try', function (): void {
    $sessionId = 'audit-10-'.uniqid();

    auditTurn($this->action, $sessionId, 'تقديم شكوى');
    auditTurn($this->action, $sessionId, 'إلغاء');

    $response = auditTurn($this->action, $sessionId, '1');

    expect($response->message)->toContain('اختار التصنيف');
    expect($response->message)->toContain('رخص البناء');
});

it('AUDIT-11: typing a menu number right after the help menu opens the target', function (): void {
    $sessionId = 'audit-11-'.uniqid();

    auditTurn($this->action, $sessionId, 'شو بتقدر تساعدني');

    $response = auditTurn($this->action, $sessionId, '1');

    expect($response->message)->toContain('اختار التصنيف');
});

// =============================================
// P8: municipal services wording is honored
// =============================================

it('AUDIT-12: الخدمات البلدية opens the categories list with municipal header', function (): void {
    $sessionId = 'audit-12-'.uniqid();

    $response = auditTurn($this->action, $sessionId, 'الخدمات البلدية');

    expect($response->message)->toContain('خدمات البلدية — اختار التصنيف:');
    expect($response->message)->toContain('رخص البناء');
    expect($response->message)->not->toContain('عذرًا، حدث خطأ أثناء المعالجة');
});

// =============================================
// P10: category name while viewing a service re-opens the category
// =============================================

it('AUDIT-13: typing a category name while viewing a service opens that category list', function (): void {
    $categoryId = ServiceCategory::query()->where('name', 'الخدمات الإلكترونية')->value('id');
    $serviceId = ElectronicService::query()->where('name', 'طلب خدمة رقمية')->value('id');
    expect($categoryId)->not->toBeNull();
    expect($serviceId)->not->toBeNull();

    $sessionId = 'audit-13-'.uniqid();

    $details = auditTurn($this->action, $sessionId, "service:{$serviceId}");
    expect($details->message)->toContain('ممكن تسأل عن أي تفصيل');

    $response = auditTurn($this->action, $sessionId, 'الخدمات الصحية');

    expect($response->message)->toContain('خدمات الخدمات الصحية:');
    expect($this->context->getState($sessionId)->state->value)->toBe('waiting_for_service_selection');
});

// =============================================
// P11: loose phrasing resolves to the digital service
// =============================================

it('AUDIT-14: الخدمة الرقمية resolves to the digital service by token match', function (): void {
    $categoryId = ServiceCategory::query()->where('name', 'الخدمات الإلكترونية')->value('id');
    expect($categoryId)->not->toBeNull();

    $sessionId = 'audit-14-'.uniqid();

    auditTurn($this->action, $sessionId, "service-category:{$categoryId}");

    $response = auditTurn($this->action, $sessionId, 'الخدمة الرقمية');

    expect($response->message)->not->toContain('مش قادر أحدد');
    expect($response->message)->toContain('خدمة إلكترونية متنوعة');
});

it('AUDIT-15: بدي طلب خدمة رقمية resolves to the digital service by token match', function (): void {
    $categoryId = ServiceCategory::query()->where('name', 'الخدمات الإلكترونية')->value('id');
    expect($categoryId)->not->toBeNull();

    $sessionId = 'audit-15-'.uniqid();

    auditTurn($this->action, $sessionId, "service-category:{$categoryId}");

    $response = auditTurn($this->action, $sessionId, 'بدي طلب خدمة رقمية');

    expect($response->message)->not->toContain('مش قادر أحدد');
    expect($response->message)->toContain('خدمة إلكترونية متنوعة');
});
