<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class PublicChatbotDataQualityGuard
{
    private const DEMO_PHONE_PATTERNS = [
        '/^\+?970-22-123456$/',
        '/^\+?970-22-123457$/',
        '/^\+?970-22-123458$/',
        '/^\+?970-22-123459$/',
        '/^\+?970-22-123450$/',
        '/^\+?970-22-111111$/',
        '/^\+?970-22-222222$/',
        '/^\+?970-22-333333$/',
        '/^\+?970-22-444444$/',
        '/^\+?970-22-555555$/',
        '/^\+?970-22-666666$/',
        '/^\+?970-22-777777$/',
        '/^\+?970-22-888888$/',
        '/^\+?970-22-999999$/',
        '/^\+?970-22-000000$/',
        '/^1234567890$/',
        '/^000-000-0000$/',
        '/^999-999-9999$/',
    ];

    private const LOREM_FAKER_PATTERNS = [
        '/lorem\s+ipsum/i',
        '/faker\.?php/i',
        '/factory\(\s*[\'"](App|Database)/i',
        '/placeholder\s+text/i',
        '/dummy\s+text/i',
        '/test\s+data/i',
        '/sample\s+data/i',
        '/demo\s+data/i',
        '/example\.com/i',
        '/example\.org/i',
        '/test\.com/i',
        '/localhost/i',
        '/127\.0\.0\.1/',
    ];

    private const CORRUPTED_PATTERNS = [
        '/m\s+sclerosis/i',
        '/\s+sclerosis$/i',
        '/[\x{4E00}-\x{9FFF}]/u',
        '/[\x{3400}-\x{4DBF}]/u',
        '/[\x{3000}-\x{303F}]/u',
        '/[\x{FF00}-\x{FFEF}]/u',
        '/^[\x{0600}-\x{06FF}]*[\x{4E00}-\x{9FFF}]+/u',
        '/[\x{4E00}-\x{9FFF}]+[\x{0600}-\x{06FF}]*$/u',
    ];

    public function isDemoPhone(string $value): bool
    {
        $normalized = preg_replace('/[^0-9+]/', '', $value) ?? $value;

        foreach (self::DEMO_PHONE_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }

            if (preg_match($pattern, $normalized)) {
                return true;
            }
        }

        return false;
    }

    public function isLoremOrFaker(string $value): bool
    {
        foreach (self::LOREM_FAKER_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    public function isCorrupted(string $value): bool
    {
        foreach (self::CORRUPTED_PATTERNS as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    public function isPlaceholderEmail(string $value): bool
    {
        $lower = mb_strtolower($value, 'UTF-8');

        return str_contains($lower, 'example.')
            || str_contains($lower, 'test.')
            || str_contains($lower, 'demo.')
            || str_contains($lower, 'placeholder.')
            || str_contains($lower, 'dummy.')
            || $lower === 'info@idhna.ps'
            || $lower === 'support@idhna.ps';
    }

    public function isDemoValue(string $value): bool
    {
        return $this->isDemoPhone($value)
            || $this->isLoremOrFaker($value)
            || $this->isCorrupted($value)
            || $this->isPlaceholderEmail($value);
    }

    public function filterValue(string $value, string $field): ?string
    {
        if ($value === '' || $value === null) {
            return null;
        }

        if ($this->isDemoValue($value)) {
            return null;
        }

        return $value;
    }

    public function auditRecord(string $table, int $recordId, array $fields): array
    {
        $issues = [];

        foreach ($fields as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $valueStr = (string) $value;

            if ($this->isDemoPhone($valueStr)) {
                $issues[] = [
                    'table' => $table,
                    'record_id' => $recordId,
                    'field' => $field,
                    'bad_value' => $valueStr,
                    'reason' => 'placeholder_phone',
                ];
            }

            if ($this->isLoremOrFaker($valueStr)) {
                $issues[] = [
                    'table' => $table,
                    'record_id' => $recordId,
                    'field' => $field,
                    'bad_value' => $valueStr,
                    'reason' => 'lorem_faker',
                ];
            }

            if ($this->isCorrupted($valueStr)) {
                $issues[] = [
                    'table' => $table,
                    'record_id' => $recordId,
                    'field' => $field,
                    'bad_value' => $valueStr,
                    'reason' => 'corrupted_multilingual',
                ];
            }

            if ($this->isPlaceholderEmail($valueStr)) {
                $issues[] = [
                    'table' => $table,
                    'record_id' => $recordId,
                    'field' => $field,
                    'bad_value' => $valueStr,
                    'reason' => 'placeholder_email',
                ];
            }
        }

        return $issues;
    }
}
