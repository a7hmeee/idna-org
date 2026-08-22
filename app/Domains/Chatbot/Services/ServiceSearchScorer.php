<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\DTOs\ServiceSearchMatchData;

final readonly class ServiceSearchScorer
{
    private const WEIGHTS = [
        'exact_official_name' => 1.00,
        'exact_phrase' => 0.98,
        'exact_alias' => 0.95,
        'contained_official_name' => 0.90,
        'contained_phrase' => 0.86,
        'contained_alias' => 0.82,
        'keyword_exact' => 0.72,
        'token_overlap_max' => 0.65,
        'citizen_expression_exact' => 0.88,
        'citizen_expression_contained' => 0.78,
        'short_description_overlap_max' => 0.40,
        'category_overlap_max' => 0.25,
        'context_boost' => 0.08,
        'priority_boost_max' => 0.05,
    ];

    public function __construct(
        private ServiceSearchTokenizer $tokenizer,
        private ArabicTypoMatcher $typoMatcher,
        private array $weights = self::WEIGHTS,
    ) {}

    public function score(
        ServiceSearchDocumentData $document,
        array $queryTokens,
        string $normalizedMessage,
        ?int $currentServiceId = null,
    ): ?ServiceSearchMatchData {
        $matchedBy = null;
        $matchedTerms = [];
        $score = 0.0;

        // 1. Exact official name match
        if ($document->normalizedOfficialName === $normalizedMessage) {
            $score = $this->weights['exact_official_name'];
            $matchedBy = 'exact_official_name';
            $matchedTerms = [$document->officialName];
        }

        // 2. Exact searchable phrase match
        if ($score < $this->weights['exact_phrase']) {
            foreach ($document->normalizedSearchablePhrases as $phrase) {
                if ($phrase === $normalizedMessage) {
                    $score = max($score, $this->weights['exact_phrase']);
                    $matchedBy = 'exact_phrase';
                    $matchedTerms = [$phrase];
                    break;
                }
            }
        }

        // 3. Exact citizen expression match
        if ($score < $this->weights['citizen_expression_exact']) {
            foreach ($document->normalizedCitizenExpressions as $expr) {
                if ($expr === $normalizedMessage) {
                    $score = max($score, $this->weights['citizen_expression_exact']);
                    $matchedBy = 'citizen_expression';
                    $matchedTerms = [$expr];
                    break;
                }
            }
        }

        // 4. Exact alias match
        if ($score < $this->weights['exact_alias']) {
            foreach ($document->normalizedAliases as $alias) {
                if ($alias === $normalizedMessage) {
                    $score = max($score, $this->weights['exact_alias']);
                    $matchedBy = 'exact_alias';
                    $matchedTerms = [$alias];
                    break;
                }
            }
        }

        // 5. Official name contained in message
        if ($score < $this->weights['contained_official_name']) {
            if (mb_strlen($document->normalizedOfficialName) >= 3
                && str_contains($normalizedMessage, $document->normalizedOfficialName)) {
                $score = max($score, $this->weights['contained_official_name']);
                $matchedBy = 'contained_official_name';
                $matchedTerms = [$document->officialName];
            }
        }

        // 6. Searchable phrase contained in message
        if ($score < $this->weights['contained_phrase']) {
            foreach ($document->normalizedSearchablePhrases as $phrase) {
                if (mb_strlen($phrase) >= 3 && str_contains($normalizedMessage, $phrase)) {
                    $score = max($score, $this->weights['contained_phrase']);
                    $matchedBy = 'contained_phrase';
                    $matchedTerms = [$phrase];
                    break;
                }
            }
        }

        // 7. Citizen expression contained in message
        if ($score < $this->weights['citizen_expression_contained']) {
            foreach ($document->normalizedCitizenExpressions as $expr) {
                if (mb_strlen($expr) >= 3 && str_contains($normalizedMessage, $expr)) {
                    $score = max($score, $this->weights['citizen_expression_contained']);
                    $matchedBy = 'citizen_expression_contained';
                    $matchedTerms = [$expr];
                    break;
                }
            }
        }

        // 8. Alias contained in message
        if ($score < $this->weights['contained_alias']) {
            foreach ($document->normalizedAliases as $alias) {
                if (mb_strlen($alias) >= 3 && str_contains($normalizedMessage, $alias)) {
                    $score = max($score, $this->weights['contained_alias']);
                    $matchedBy = 'contained_alias';
                    $matchedTerms = [$alias];
                    break;
                }
            }
        }

        // 9. Keyword exact match
        if ($score < $this->weights['keyword_exact']) {
            foreach ($queryTokens as $token) {
                if (in_array($token, $document->normalizedKeywords, true)) {
                    $kwScore = $this->weights['keyword_exact'];
                    $score = max($score, $kwScore);
                    $matchedBy = 'keyword';
                    $matchedTerms = [$token];
                    break;
                }
            }
        }

        // 10. Token overlap score (weighted Jaccard-like)
        if ($score < $this->weights['token_overlap_max']) {
            $overlapScore = $this->computeTokenOverlap($queryTokens, $document);
            if ($overlapScore > 0) {
                $score = max($score, $overlapScore);
                if ($matchedBy === null || $score < $this->weights['keyword_exact']) {
                    $matchedBy = 'token_overlap';
                }
            }
        }

        // 11. Short description overlap
        if ($score < $this->weights['short_description_overlap_max']
            && $document->normalizedShortDescription !== null) {
            $descTokens = $this->tokenizer->tokenize($document->normalizedShortDescription);
            $overlap = array_intersect($queryTokens, $descTokens);
            if (! empty($overlap)) {
                $ratio = count($overlap) / max(count($descTokens), 1);
                $descScore = $ratio * $this->weights['short_description_overlap_max'];
                if ($descScore > $score) {
                    $score = $descScore;
                    $matchedBy = 'description_overlap';
                }
            }
        }

        // 12. Category overlap
        if ($score < $this->weights['category_overlap_max']
            && $document->normalizedCategoryName !== null) {
            $catTokens = $this->tokenizer->tokenize($document->normalizedCategoryName);
            $overlap = array_intersect($queryTokens, $catTokens);
            if (! empty($overlap)) {
                $ratio = count($overlap) / max(count($catTokens), 1);
                $catScore = $ratio * $this->weights['category_overlap_max'];
                if ($catScore > $score) {
                    $score = $catScore;
                    $matchedBy = 'category_overlap';
                }
            }
        }

        // 13. Typo tolerance (only for score improvement, never primary match)
        if ($score < $this->weights['exact_alias'] && $score < 0.80) {
            $typoScore = $this->computeTypoScore($normalizedMessage, $document);
            if ($typoScore > $score) {
                $score = $typoScore;
                $matchedBy = 'typo_match';
            }
        }

        // 14. Current context boost
        if ($currentServiceId !== null && $currentServiceId === $document->serviceId) {
            $score = min(1.0, $score + $this->weights['context_boost']);
        }

        // 15. Priority boost
        $priorityBoost = ($document->priority / 100) * $this->weights['priority_boost_max'];
        $score = min(1.0, $score + $priorityBoost);

        if ($score <= 0.0 || $matchedBy === null) {
            return null;
        }

        $explanation = $this->buildExplanation($matchedBy, $matchedTerms, $score);

        return new ServiceSearchMatchData(
            serviceId: $document->serviceId,
            serviceName: $document->officialName,
            score: $score,
            matchedBy: $matchedBy,
            matchedTerms: $matchedTerms,
            explanation: $explanation,
            priority: $document->priority,
            applicationUrl: $document->applicationUrl,
        );
    }

    private function computeTokenOverlap(array $queryTokens, ServiceSearchDocumentData $document): float
    {
        if (empty($queryTokens)) {
            return 0.0;
        }

        // Collect all searchable tokens from the document
        $docTokens = array_merge(
            $document->normalizedKeywords,
            $document->normalizedAliases,
            $this->tokenizer->tokenize($document->normalizedOfficialName),
        );

        // Add normalized citizen expression tokens
        foreach ($document->normalizedCitizenExpressions as $expr) {
            $docTokens = array_merge($docTokens, $this->tokenizer->tokenize($expr));
        }

        // Add normalized phrase tokens
        foreach ($document->normalizedSearchablePhrases as $phrase) {
            $docTokens = array_merge($docTokens, $this->tokenizer->tokenize($phrase));
        }

        $docTokens = array_unique(array_filter($docTokens));

        if (empty($docTokens)) {
            return 0.0;
        }

        $matched = array_intersect($queryTokens, $docTokens);

        if (empty($matched)) {
            return 0.0;
        }

        // Weighted Jaccard-like score with query coverage emphasis
        $intersection = count($matched);
        $union = count(array_unique(array_merge($queryTokens, $docTokens)));
        $queryCoverage = $intersection / count($queryTokens);

        $jaccard = $union > 0 ? $intersection / $union : 0.0;

        // Emphasize query coverage over pure Jaccard
        $score = ($jaccard * 0.4 + $queryCoverage * 0.6) * $this->weights['token_overlap_max'];

        return min($score, $this->weights['token_overlap_max']);
    }

    private function computeTypoScore(string $normalizedMessage, ServiceSearchDocumentData $document): float
    {
        // Check official name with typo tolerance
        $nameResult = $this->typoMatcher->match($normalizedMessage, $document->normalizedOfficialName);
        if ($nameResult !== null) {
            return $nameResult * 0.85; // Typo matches get 85% of their score
        }

        // Check aliases
        foreach ($document->normalizedAliases as $alias) {
            $aliasResult = $this->typoMatcher->match($normalizedMessage, $alias);
            if ($aliasResult !== null) {
                return $aliasResult * 0.80;
            }
        }

        // Check keywords
        foreach ($document->normalizedKeywords as $keyword) {
            $kwResult = $this->typoMatcher->match($normalizedMessage, $keyword);
            if ($kwResult !== null) {
                return $kwResult * 0.70;
            }
        }

        return 0.0;
    }

    private function buildExplanation(string $matchedBy, array $matchedTerms, float $score): string
    {
        $labels = [
            'exact_official_name' => 'Exact official name match',
            'exact_phrase' => 'Exact searchable phrase match',
            'exact_alias' => 'Exact alias match',
            'citizen_expression' => 'Exact citizen expression match',
            'citizen_expression_contained' => 'Citizen expression contained in message',
            'contained_official_name' => 'Official name contained in message',
            'contained_phrase' => 'Searchable phrase contained in message',
            'contained_alias' => 'Alias contained in message',
            'keyword' => 'Keyword match',
            'token_overlap' => 'Token overlap',
            'description_overlap' => 'Short description overlap',
            'category_overlap' => 'Category overlap',
            'typo_match' => 'Typo-tolerant match',
        ];

        $label = $labels[$matchedBy] ?? $matchedBy;
        $terms = ! empty($matchedTerms) ? ' Terms: '.implode(', ', $matchedTerms) : '';

        return "{$label}. Score: {$score}.{$terms}";
    }
}
