<?php

declare(strict_types=1);

use App\Domains\Chatbot\Actions\ProcessRuleBasedChatMessageAction;
use App\Domains\Chatbot\DTOs\IncomingChatMessageData;
use App\Domains\Chatbot\Services\ConversationContextService;
use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Database\Factories\WaterSchedule\WaterAreaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->area1 = (new WaterAreaFactory)->create(['name' => 'حي البلد', 'display_order' => 1, 'is_active' => true]);
    $this->area2 = (new WaterAreaFactory)->create(['name' => 'واد ريشة', 'display_order' => 2, 'is_active' => true]);

    WaterSchedule::create([
        'water_area_id' => $this->area1->id,
        'schedule_date' => now()->toDateString(),
        'start_time' => '08:00',
        'end_time' => '14:00',
        'status' => WaterScheduleStatus::Available->value,
        'is_public' => true,
    ]);
});

function sendDebug(string $message, string $sessionId): mixed
{
    return app(ProcessRuleBasedChatMessageAction::class)->execute(
        new IncomingChatMessageData(message: $message, sessionId: $sessionId),
    );
}

it('debugs water turn 2 state', function (): void {
    $sessionId = 'debug-water-'.Str::uuid();

    $r1 = sendDebug('جدول المياه', $sessionId);
    dump('TURN1', [
        'type' => $r1->type,
        'clarification_type' => $r1->clarificationType,
        'needs_clarification' => $r1->needsClarification,
        'message' => $r1->message,
    ]);

    $ctx = app(ConversationContextService::class);
    dump('STATE1', $ctx->getState($sessionId));

    $r2 = sendDebug('1', $sessionId);
    dump('TURN2', [
        'type' => $r2->type,
        'clarification_type' => $r2->clarificationType,
        'needs_clarification' => $r2->needsClarification,
        'message' => $r2->message,
    ]);

    $r3 = sendDebug('الرسوم', $sessionId);
    dump('TURN3', [
        'type' => $r3->type,
        'clarification_type' => $r3->clarificationType,
        'needs_clarification' => $r3->needsClarification,
        'message' => $r3->message,
    ]);

    expect(true)->toBeTrue();
});
