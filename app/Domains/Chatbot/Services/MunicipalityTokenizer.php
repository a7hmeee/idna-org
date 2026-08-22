<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

final readonly class MunicipalityTokenizer
{
    public function __construct(
        private ArabicTextNormalizer $normalizer,
    ) {}

    public function tokenize(string $text): array
    {
        $normalized = $this->normalizer->normalize($text);

        if ($normalized === '') {
            return [];
        }

        $tokens = preg_split('/\s+/u', $normalized);

        return array_values(array_filter($tokens, fn (string $t) => $t !== ''));
    }

    public function getVocabulary(array $samples): array
    {
        $vocab = [];
        foreach ($samples as $sample) {
            $tokens = $this->tokenize($sample);
            foreach ($tokens as $token) {
                $vocab[$token] = true;
            }
        }

        return array_keys($vocab);
    }
}
