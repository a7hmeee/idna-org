<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ArabicTypoMatcher;
use App\Domains\Chatbot\Services\ServiceSearchScorer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->tokenizer = new ServiceSearchTokenizer($this->normalizer, [
        'بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'شو', 'ايش', 'كيف', 'خدمة', 'معلومات',
    ]);
    $this->scorer = new ServiceSearchScorer(
        tokenizer: $this->tokenizer,
        typoMatcher: new ArabicTypoMatcher,
    );
});

// ===================================================
// Helper: build document
// ===================================================

function buildDoc(array $overrides = []): ServiceSearchDocumentData
{
    return new ServiceSearchDocumentData(
        serviceId: $overrides['id'] ?? 1,
        officialName: $overrides['name'] ?? 'رخصة بناء',
        normalizedOfficialName: $overrides['normalized'] ?? 'رخصه بناء',
        aliases: $overrides['aliases'] ?? ['تصريح بناء'],
        normalizedAliases: $overrides['normalizedAliases'] ?? ['تصريح بناء'],
        keywords: $overrides['keywords'] ?? ['بناء', 'بيت', 'دار'],
        normalizedKeywords: $overrides['normalizedKeywords'] ?? ['بناء', 'بيت', 'دار'],
        citizenExpressions: $overrides['citizenExprs'] ?? ['بدي أبني بيت'],
        normalizedCitizenExpressions: $overrides['normalizedCitizenExprs'] ?? ['بدي ابني بيت'],
        searchablePhrases: $overrides['phrases'] ?? [],
        normalizedSearchablePhrases: $overrides['normalizedPhrases'] ?? [],
        shortDescription: $overrides['desc'] ?? 'رخصة بناء جديدة',
        normalizedShortDescription: $overrides['normalizedDesc'] ?? 'رخصه بناء جديده',
        categoryName: $overrides['category'] ?? 'رخص البناء',
        normalizedCategoryName: $overrides['normalizedCategory'] ?? 'رخص البناء',
        priority: $overrides['priority'] ?? 0,
        isPublished: true,
        applicationUrl: $overrides['url'] ?? null,
    );
}

// ===================================================
// 1. Exact Official Name
// ===================================================

it('scores exact official name at 1.0', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('رخصة بناء');
    $match = $this->scorer->score($doc, $tokens, 'رخصه بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(1.0);
    expect($match->matchedBy)->toBe('exact_official_name');
});

// ===================================================
// 2. Exact Searchable Phrase
// ===================================================

it('scores exact phrase at 0.98', function (): void {
    $doc = buildDoc(['phrases' => ['رخصة بناء جديد'], 'normalizedPhrases' => ['رخصه بناء جديده']]);
    $tokens = $this->tokenizer->tokenize('رخصة بناء جديد');
    $match = $this->scorer->score($doc, $tokens, 'رخصه بناء جديده');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.98);
    expect($match->matchedBy)->toBe('exact_phrase');
});

// ===================================================
// 3. Exact Citizen Expression
// ===================================================

it('scores exact citizen expression at 0.88', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('بدي أبني بيت');
    $match = $this->scorer->score($doc, $tokens, 'بدي ابني بيت');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.88);
    expect($match->matchedBy)->toBe('citizen_expression');
});

// ===================================================
// 4. Exact Alias
// ===================================================

it('scores exact alias at 0.95', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('تصريح بناء');
    $match = $this->scorer->score($doc, $tokens, 'تصريح بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.95);
    expect($match->matchedBy)->toBe('exact_alias');
});

// ===================================================
// 5. Contained Official Name
// ===================================================

it('scores contained official name at 0.90', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('بدي رخصة بناء');
    $match = $this->scorer->score($doc, $tokens, 'بدي رخصه بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.90);
    expect($match->matchedBy)->toBe('contained_official_name');
});

// ===================================================
// 6. Contained Phrase
// ===================================================

it('scores contained phrase at 0.86', function (): void {
    $doc = buildDoc([
        'name' => 'ترخيص بناء جديد',
        'normalized' => 'ترخيص بناء جديد',
        'phrases' => ['بناء سكني جديد'],
        'normalizedPhrases' => ['بناء سكني جديد'],
    ]);
    $tokens = $this->tokenizer->tokenize('بدي بناء سكني جديد');
    $match = $this->scorer->score($doc, $tokens, 'بدي بناء سكني جديد');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.86);
    expect($match->matchedBy)->toBe('contained_phrase');
});

// ===================================================
// 7. Contained Citizen Expression
// ===================================================

it('scores contained citizen expression at 0.78', function (): void {
    $doc = buildDoc(['citizenExprs' => ['بدي أبني بيت كبير'], 'normalizedCitizenExprs' => ['بدي ابني بيت كبير']]);
    $tokens = $this->tokenizer->tokenize('بدي أبني بيت كبير في الحارة');
    $match = $this->scorer->score($doc, $tokens, 'بدي ابني بيت كبير في الحاره');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.78);
    expect($match->matchedBy)->toBe('citizen_expression_contained');
});

// ===================================================
// 8. Contained Alias
// ===================================================

it('scores contained alias at 0.82', function (): void {
    $doc = buildDoc(['aliases' => ['تصريح بناء'], 'normalizedAliases' => ['تصريح بناء']]);
    $tokens = $this->tokenizer->tokenize('بدي تصريح بناء');
    $match = $this->scorer->score($doc, $tokens, 'بدي تصريح بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.82);
    expect($match->matchedBy)->toBe('contained_alias');
});

// ===================================================
// 9. Keyword Exact Match
// ===================================================

it('scores keyword match at 0.72', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('بناء');
    $match = $this->scorer->score($doc, $tokens, 'بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.72);
    expect($match->matchedBy)->toBe('keyword');
});

// ===================================================
// 10. Token Overlap
// ===================================================

it('scores token overlap for partial keyword match', function (): void {
    $doc = buildDoc(['keywords' => ['بناء', 'دار'], 'normalizedKeywords' => ['بناء', 'دار']]);
    $tokens = $this->tokenizer->tokenize('ابني تصريح');
    $match = $this->scorer->score($doc, $tokens, 'ابني تصريح');

    expect($match)->not->toBeNull();
    expect($match->matchedBy)->toBe('token_overlap');
    expect($match->score)->toBeGreaterThan(0.0);
    expect($match->score)->toBeLessThanOrEqual(0.65);
});

it('higher token overlap yields higher score', function (): void {
    $doc = buildDoc();
    $tokens1 = $this->tokenizer->tokenize('ابني');
    $match1 = $this->scorer->score($doc, $tokens1, 'ابني');

    $tokens2 = $this->tokenizer->tokenize('ابني بيت');
    $match2 = $this->scorer->score($doc, $tokens2, 'ابني بيت');

    expect($match2->score)->toBeGreaterThan($match1->score);
});

// ===================================================
// 11. Description Overlap
// ===================================================

it('scores description overlap', function (): void {
    $doc = buildDoc(['desc' => 'ترخيص بناء جديد', 'normalizedDesc' => 'ترخيص بناء جديده']);
    $tokens = $this->tokenizer->tokenize('ترخيص جديد');
    $match = $this->scorer->score($doc, $tokens, 'ترخيص جديد');

    expect($match)->not->toBeNull();
    expect($match->score)->toBeGreaterThan(0.0);
});

// ===================================================
// 12. Category Overlap
// ===================================================

it('scores category overlap', function (): void {
    $doc = buildDoc(['category' => 'رخص البناء', 'normalizedCategory' => 'رخص البناء']);
    $tokens = $this->tokenizer->tokenize('رخص بناء');
    $match = $this->scorer->score($doc, $tokens, 'رخص بناء');

    expect($match)->not->toBeNull();
    expect($match->score)->toBeGreaterThan(0.0);
});

// ===================================================
// 13. Context Boost
// ===================================================

it('applies context boost when service matches', function (): void {
    $doc = buildDoc(['id' => 1]);
    $tokens = $this->tokenizer->tokenize('بناء');

    $matchWithout = $this->scorer->score($doc, $tokens, 'بناء');
    $matchWith = $this->scorer->score($doc, $tokens, 'بناء', currentServiceId: 1);

    expect($matchWithout)->not->toBeNull();
    expect($matchWith)->not->toBeNull();
    expect($matchWith->score)->toBeGreaterThan($matchWithout->score);
});

// ===================================================
// 14. Priority Boost
// ===================================================

it('applies priority boost', function (): void {
    $docLow = buildDoc(['priority' => 0]);
    $docHigh = buildDoc(['id' => 2, 'priority' => 50]);
    $tokens = $this->tokenizer->tokenize('بناء');

    $matchLow = $this->scorer->score($docLow, $tokens, 'بناء');
    $matchHigh = $this->scorer->score($docHigh, $tokens, 'بناء');

    expect($matchHigh->score)->toBeGreaterThan($matchLow->score);
});

// ===================================================
// Score Clamp
// ===================================================

it('clamps score between 0.0 and 1.0', function (): void {
    $doc = buildDoc(['priority' => 100]);
    $tokens = $this->tokenizer->tokenize('رخصة بناء');

    $match = $this->scorer->score($doc, $tokens, 'رخصه بناء');
    expect($match->score)->toBeGreaterThanOrEqual(0.0);
    expect($match->score)->toBeLessThanOrEqual(1.0);
});

// ===================================================
// No Match
// ===================================================

it('returns null for no match', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('مستشفى');
    $match = $this->scorer->score($doc, $tokens, 'مستشفى');

    expect($match)->toBeNull();
});

// ===================================================
// Explanation
// ===================================================

it('includes explanation in match result', function (): void {
    $doc = buildDoc();
    $tokens = $this->tokenizer->tokenize('بناء');
    $match = $this->scorer->score($doc, $tokens, 'بناء');

    expect($match)->not->toBeNull();
    expect($match->explanation)->not->toBeEmpty();
    expect($match->explanation)->toContain('Score:');
});

// ===================================================
// Multiple Query Tokens vs Single
// ===================================================

it('better match score with more overlapping tokens', function (): void {
    $doc = buildDoc([
        'keywords' => ['بناء', 'بيت', 'دار'],
        'normalizedKeywords' => ['بناء', 'بيت', 'دار'],
    ]);
    $tokens1 = $this->tokenizer->tokenize('بيت');
    $match1 = $this->scorer->score($doc, $tokens1, 'بيت');

    $tokens2 = $this->tokenizer->tokenize('ابني بيت');
    $match2 = $this->scorer->score($doc, $tokens2, 'ابني بيت');

    expect($match1)->not->toBeNull();
    expect($match2)->not->toBeNull();
    expect($match2->score)->toBeGreaterThanOrEqual($match1->score);
});
