<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class ArabicTypoMatcher
{
    private const MIN_TERM_LENGTH = 4;

    private const MAX_EDIT_DISTANCE = 2;

    public function __construct(
        private int $maxEditDistance = self::MAX_EDIT_DISTANCE,
        private int $minTermLength = self::MIN_TERM_LENGTH,
    ) {}

    /**
     * Match a query against a candidate term with typo tolerance.
     * Returns a score (0.0–1.0) representing similarity, or null if no match.
     */
    public function match(string $query, string $candidate): ?float
    {
        $query = trim($query);
        $candidate = trim($candidate);

        if ($query === '' || $candidate === '') {
            return null;
        }

        // Exact match already handled elsewhere
        if ($query === $candidate) {
            return null;
        }

        $queryLen = mb_strlen($query);
        $candidateLen = mb_strlen($candidate);

        // Skip very short terms
        if ($queryLen < $this->minTermLength || $candidateLen < $this->minTermLength) {
            return null;
        }

        // Calculate Levenshtein distance
        $distance = $this->levenshteinUtf8($query, $candidate);

        if ($distance === false || $distance > $this->maxEditDistance) {
            return null;
        }

        // Single edit for short terms where safe
        if ($distance > 1 && ($queryLen < 5 || $candidateLen < 5)) {
            return null;
        }

        // Normalize: longer terms can tolerate more edits proportionally
        $maxLen = max($queryLen, $candidateLen);
        $similarity = 1.0 - ($distance / $maxLen);

        // Apply penalty for typo matching
        $similarity *= 0.90;

        return max(0.0, min(1.0, $similarity));
    }

    /**
     * Check if a match is purely typo-based (not an exact or contained match).
     */
    public function isTypoOnly(string $query, string $candidate): bool
    {
        if ($query === $candidate) {
            return false;
        }

        if (str_contains($query, $candidate) || str_contains($candidate, $query)) {
            return false;
        }

        return $this->match($query, $candidate) !== null;
    }

    /**
     * Levenshtein distance for UTF-8 strings using multi-byte characters.
     */
    private function levenshteinUtf8(string $a, string $b): int|false
    {
        $lenA = mb_strlen($a);
        $lenB = mb_strlen($b);

        if ($lenA === 0) {
            return $lenB;
        }

        if ($lenB === 0) {
            return $lenA;
        }

        // For very long strings, use native levenshtein (ASCII-compatible)
        // Since Arabic chars are multi-byte, we need character-level comparison
        $charsA = $this->mbStringToArray($a);
        $charsB = $this->mbStringToArray($b);

        if ($charsA === false || $charsB === false) {
            return false;
        }

        $lenA = count($charsA);
        $lenB = count($charsB);

        // Use two-row approach for memory efficiency
        $prevRow = range(0, $lenB);
        $currRow = [];

        for ($i = 0; $i < $lenA; $i++) {
            $currRow = [$i + 1];

            for ($j = 0; $j < $lenB; $j++) {
                $cost = $charsA[$i] === $charsB[$j] ? 0 : 1;
                $currRow[] = min(
                    $currRow[$j] + 1,
                    $prevRow[$j + 1] + 1,
                    $prevRow[$j] + $cost,
                );
            }

            $prevRow = $currRow;
        }

        return $currRow[$lenB] ?? false;
    }

    private function mbStringToArray(string $string): array|false
    {
        $len = mb_strlen($string);

        if ($len === 0) {
            return [];
        }

        $array = [];

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($string, $i, 1);
            if ($char === false) {
                return false;
            }
            $array[] = $char;
        }

        return $array;
    }
}
