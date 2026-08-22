<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\ElectronicServices\Models\ElectronicService;
use App\Domains\ElectronicServices\Models\ServiceCategory;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Database\Factories\WaterSchedule\WaterAreaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Cache::flush();

    $this->area1 = (new WaterAreaFactory)->create([
        'name' => 'حي البلد',
        'display_order' => 1,
        'is_active' => true,
    ]);
    $this->area2 = (new WaterAreaFactory)->create([
        'name' => 'واد ريشة',
        'display_order' => 2,
        'is_active' => true,
    ]);

    WaterSchedule::create([
        'water_area_id' => $this->area1->id,
        'schedule_date' => now()->toDateString(),
        'start_time' => '08:00',
        'end_time' => '14:00',
        'status' => WaterScheduleStatus::Available->value,
        'is_public' => true,
    ]);
});

function sendChatbotMessage(string $message, string $sessionId): mixed
{
    return app(ProcessRuleBasedChatMessageAction::class)->execute(
        new IncomingChatMessageData(message: $message, sessionId: $sessionId),
    );
}

it('asks for water area when none is mentioned', function (): void {
    $response = sendChatbotMessage('جدول المياه', 'water-session-'.Str::uuid());

    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('water_area');
    expect($response->message)->toContain('لأي منطقة');
    expect($response->message)->toContain('حي البلد');
    expect($response->message)->toContain('واد ريشة');
});

it('resolves numeric water area selection end-to-end', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    $ask = sendChatbotMessage('جدول المياه', $sessionId);
    expect($ask->needsClarification)->toBeTrue();

    $response = sendChatbotMessage('1', $sessionId);

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('جدول المياه لمنطقة حي البلد');
    expect($response->message)->toContain('8:00 صباحًا — 2:00 ظهرًا');
});

it('resolves typed area name selection end-to-end', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);

    $response = sendChatbotMessage('واد ريشة', $sessionId);

    expect($response->message)->toContain('لا يوجد جدول مياه متاح حاليًا لمنطقة واد ريشة');
});

it('returns formatted Arabic time for water schedule', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);
    $response = sendChatbotMessage('1', $sessionId);

    expect($response->message)->toContain('صباحًا');
    expect($response->message)->toContain('ظهراً');
});

it('returns all schedules for area with multiple schedules', function (): void {
    $area = WaterArea::factory()->create(['name' => 'حي الشرقية']);
    $today = now()->toDateString();
    $yesterday = now()->subDay()->toDateString();

    WaterSchedule::create([
        'water_area_id' => $area->id,
        'schedule_date' => $today,
        'start_time' => '08:00',
        'end_time' => '12:00',
        'status' => WaterScheduleStatus::Available->value,
        'is_public' => true,
    ]);

    WaterSchedule::create([
        'water_area_id' => $area->id,
        'schedule_date' => $yesterday,
        'start_time' => '14:00',
        'end_time' => '18:00',
        'status' => WaterScheduleStatus::Available->value,
        'is_public' => true,
    ]);

    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);
    $response = sendChatbotMessage('حي الشرقية', $sessionId);

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('جدول المياه لمنطقة حي الشرقية');
    expect($response->message)->toContain('8:00 صباحًا — 12:00 ظهرًا');
});

it('returns latest schedule when no today schedule exists', function (): void {
    $area = WaterArea::factory()->create(['name' => 'حي الشمالية']);
    $yesterday = now()->subDay()->toDateString();

    WaterSchedule::create([
        'water_area_id' => $area->id,
        'schedule_date' => $yesterday,
        'start_time' => '10:00',
        'end_time' => '14:00',
        'status' => WaterScheduleStatus::Available->value,
        'is_public' => true,
    ]);

    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);
    $response = sendChatbotMessage('حي الشمالية', $sessionId);

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('جدول المياه لمنطقة حي الشمالية');
    expect($response->message)->toContain('10:00 صباحًا — 2:00 ظهرًا');
});

it('resolves fuzzy area name with typo', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);

    $response = sendChatbotMessage('حي البلجد', $sessionId);

    expect($response->type)->toBe('schedule');
    expect($response->message)->toContain('جدول المياه لمنطقة حي البلد');
});

it('thanks after water selection is never trapped', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);
    sendChatbotMessage('1', $sessionId);

    $response = sendChatbotMessage('شكرا', $sessionId);

    expect($response->message)->toContain('العفو');
    expect($response->type)->toBe('text');
});

it('service property question after water flow is not treated as water input', function (): void {
    $sessionId = 'water-session-'.Str::uuid();

    sendChatbotMessage('جدول المياه', $sessionId);
    sendChatbotMessage('1', $sessionId);

    $response = sendChatbotMessage('الرسوم', $sessionId);

    expect($response->needsClarification)->toBeTrue();
    expect($response->clarificationType)->toBe('service');
});

it('guided service selection accepts machine service-action keys', function (): void {
    $category = ServiceCategory::create([
        'name' => 'الخدمات الإلكترونية',
        'slug' => 'alkhdmat-alalktrony',
    ]);
    $service = ElectronicService::create([
        'service_category_id' => $category->id,
        'name' => 'رخصة بناء',
        'status' => 'active',
        'is_public' => true,
        'fees' => [['item' => 'رسوم الترخيص', 'amount' => '500 شيكل']],
    ]);

    $sessionId = 'service-session-'.Str::uuid();

    $response = sendChatbotMessage('بدي خدمة', $sessionId);
    expect($response->message)->toContain('اختر التصنيف');

    $response = sendChatbotMessage('الخدمات الإلكترونية', $sessionId);
    expect($response->message)->toContain('خدمات الخدمات الإلكترونية:');

    $response = sendChatbotMessage('رخصة بناء', $sessionId);
    expect($response->message)->toContain('ممكن تسأل عن أي تفصيل');
    $values = array_column($response->actions, 'value');
    expect($values)->toContain("service-action:fees:{$service->id}");

    $response = sendChatbotMessage("service-action:fees:{$service->id}", $sessionId);

    expect($response->message)->toContain('رسوم خدمة');
    expect(json_encode($response->items, JSON_UNESCAPED_UNICODE))->toContain('500');

});
