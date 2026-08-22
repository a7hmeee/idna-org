<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\WaterTimeFormatter;

it('formats morning time correctly', function (): void {
    expect(WaterTimeFormatter::formatTime('08:00'))->toBe('8:00 صباحًا');
    expect(WaterTimeFormatter::formatTime('11:30'))->toBe('11:30 صباحًا');
});

it('formats noon time correctly', function (): void {
    expect(WaterTimeFormatter::formatTime('12:00'))->toBe('12:00 ظهرًا');
});

it('formats afternoon time correctly', function (): void {
    expect(WaterTimeFormatter::formatTime('14:00'))->toBe('2:00 مساءً');
    expect(WaterTimeFormatter::formatTime('18:30'))->toBe('6:30 مساءً');
});

it('formats midnight time correctly', function (): void {
    expect(WaterTimeFormatter::formatTime('00:00'))->toBe('12:00 صباحًا');
});

it('returns null for empty time', function (): void {
    expect(WaterTimeFormatter::formatTime(null))->toBeNull();
    expect(WaterTimeFormatter::formatTime(''))->toBeNull();
});

it('formats time range correctly', function (): void {
    expect(WaterTimeFormatter::formatRange('08:00', '12:00'))->toBe('8:00 صباحًا — 12:00 ظهرًا');
    expect(WaterTimeFormatter::formatRange('14:00', '18:00'))->toBe('2:00 مساءً — 6:00 مساءً');
});

it('formats time range with null values', function (): void {
    expect(WaterTimeFormatter::formatRange(null, '12:00'))->toBe('12:00 ظهرًا');
    expect(WaterTimeFormatter::formatRange('08:00', null))->toBe('8:00 صباحًا');
    expect(WaterTimeFormatter::formatRange(null, null))->toBeNull();
});
