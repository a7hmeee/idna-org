<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class ServiceSearchTokenizer
{
    private array $stopWords;

    private int $minimumTokenLength;

    public function __construct(
        private ArabicTextNormalizer $normalizer,
        array $stopWords = [],
        int $minimumTokenLength = 2,
    ) {
        $this->stopWords = $stopWords;
        $this->minimumTokenLength = $minimumTokenLength;
    }

    public function tokenize(string $text): array
    {
        $normalized = $this->normalizer->normalize($text);

        if ($normalized === '') {
            return [];
        }

        $tokens = preg_split('/\s+/u', $normalized);

        if ($tokens === false) {
            return [];
        }

        $tokens = array_map('trim', $tokens);
        $tokens = array_filter($tokens, fn (string $t) => $t !== '');
        $tokens = array_values($tokens);

        // Remove stop words
        $tokens = array_filter($tokens, fn (string $token) => ! $this->isStopWord($token));

        // Re-index after filtering
        $tokens = array_values($tokens);

        // Remove tokens shorter than minimum length (unless numeric)
        $tokens = array_filter($tokens, function (string $token): bool {
            if (is_numeric($token)) {
                return true;
            }

            return mb_strlen($token) >= $this->minimumTokenLength;
        });

        // Remove duplicates
        $tokens = array_unique($tokens);

        return array_values($tokens);
    }

    public function tokenizePreserveStopWords(string $text): array
    {
        $normalized = $this->normalizer->normalize($text);

        if ($normalized === '') {
            return [];
        }

        $tokens = preg_split('/\s+/u', $normalized);

        if ($tokens === false) {
            return [];
        }

        $tokens = array_map('trim', $tokens);
        $tokens = array_filter($tokens, fn (string $t) => $t !== '');
        $tokens = array_values($tokens);

        // Remove duplicates
        $tokens = array_unique($tokens);

        return array_values($tokens);
    }

    public function setStopWords(array $stopWords): void
    {
        $this->stopWords = $stopWords;
    }

    public function getStopWords(): array
    {
        return $this->stopWords;
    }

    private function isStopWord(string $token): bool
    {
        return in_array($token, $this->stopWords, true);
    }
}
