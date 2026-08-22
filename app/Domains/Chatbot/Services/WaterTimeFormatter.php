<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final class WaterTimeFormatter
{
    public static function formatTime(?string $time): ?string
    {
        if ($time === null || $time === '') {
            return null;
        }

        $parts = explode(':', $time);
        $hour = (int) ($parts[0] ?? 0);
        $minute = $parts[1] ?? '00';

        if ($hour === 0) {
            return "12:{$minute} صباحًا";
        }

        if ($hour < 12) {
            return "{$hour}:{$minute} صباحًا";
        }

        if ($hour === 12) {
            return "12:{$minute} ظهرًا";
        }

        return ($hour - 12).":{$minute} مساءً";
    }

    public static function formatRange(?string $startTime, ?string $endTime): ?string
    {
        $start = self::formatTime($startTime);
        $end = self::formatTime($endTime);

        if ($start === null && $end === null) {
            return null;
        }

        if ($start === null) {
            return $end;
        }

        if ($end === null) {
            return $start;
        }

        return "{$start} — {$end}";
    }
}
