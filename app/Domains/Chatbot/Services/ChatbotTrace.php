<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use Illuminate\Support\Facades\Log;

/**
 * Lightweight, toggleable per-turn trace for the chatbot pipeline.
 *
 * Enabled with CHATBOT_TRACE=1 in .env. Every log entry is a single JSON
 * line appended to storage/logs/chatbot_trace.jsonl so context loss between
 * turns can be inspected precisely (metadata_before/metadata_after).
 */
final class ChatbotTrace
{
    private const FLAG_ENV = 'CHATBOT_TRACE';

    private static ?string $file = null;

    public static function enabled(): bool
    {
        return (bool) env(self::FLAG_ENV, false);
    }

    public static function path(): string
    {
        if (self::$file === null) {
            self::$file = storage_path('logs/chatbot_trace.jsonl');
        }

        return self::$file;
    }

    public static function clear(): void
    {
        if (file_exists(self::path())) {
            @unlink(self::path());
        }
    }

    public static function log(array $entry): void
    {
        if (! self::enabled()) {
            return;
        }

        $entry = array_merge([
            'timestamp' => now()->format('Y-m-d H:i:s.v'),
        ], $entry);

        $line = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($line === false) {
            return;
        }

        @file_put_contents(self::path(), $line.PHP_EOL, FILE_APPEND | LOCK_EX);

        Log::debug('ChatbotTrace', $entry);
    }
}
