<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class ArabicTextNormalizer
{
    public function normalize(string $text): string
    {
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        // Normalize Alef variants to ا
        $text = preg_replace('/[أإآٱ]/u', 'ا', $text);

        // Normalize ى to ي
        $text = preg_replace('/[ى]/u', 'ي', $text);

        // Normalize ؤ to و
        $text = preg_replace('/[ؤ]/u', 'و', $text);

        // Normalize ئ to ي
        $text = preg_replace('/[ئ]/u', 'ي', $text);

        // Normalize ه to ة at end of words (common colloquial variation)
        $text = preg_replace('/ه(?=\s|$)/u', 'ة', $text);

        // Remove Arabic diacritics (tashkeel)
        $text = preg_replace('/[\x{064B}-\x{065F}]/u', '', $text);

        // Remove tatweel/kashida
        $text = preg_replace('/\x{0640}/u', '', $text);

        // Remove punctuation (keep Arabic letters, Arabic-Indic digits, Latin letters, numbers, and spaces)
        $text = preg_replace('/[^\x{0621}-\x{064A}\x{0660}-\x{0669}a-zA-Z0-9\s]/u', '', $text);

        // Collapse repeated whitespace
        $text = preg_replace('/\s+/u', ' ', $text);

        // Lowercase Latin letters
        $text = preg_replace_callback('/[a-zA-Z]/u', fn (array $m) => mb_strtolower($m[0], 'UTF-8'), $text);

        return trim($text);
    }
}
