<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

final class StringHelper
{
    public static function sanitize(?string $value): string
    {
        return trim(strip_tags($value ?? ''));
    }

    public static function truncate(string $value, int $length = 100): string
    {
        if (mb_strlen($value) <= $length) {
            return $value;
        }

        return mb_substr($value, 0, $length).'...';
    }

    public static function slug(string $value): string
    {
        return str_replace(' ', '-', mb_strtolower(trim($value)));
    }
}
