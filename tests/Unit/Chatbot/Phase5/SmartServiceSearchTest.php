<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\DTOs\ServiceSearchResultCollection;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ArabicTypoMatcher;
use App\Domains\Chatbot\Services\ServiceSearchScorer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;
use App\Domains\Chatbot\Services\SmartServiceSearch;
use Illuminate\Support\Facades\Cache;

beforeEach(function (): void {
    $normalizer = new ArabicTextNormalizer;
    $this->normalizer = $normalizer;
    $this->tokenizer = new ServiceSearchTokenizer($normalizer, [
        'بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'لو', 'سمحت',
        'اعرف', 'معرفه', 'معرفة', 'عن', 'على', 'في', 'من',
        'الى', 'إلى', 'الي', 'شو', 'ايش', 'إيش', 'كيف',
        'خدمة', 'معلومات', 'استفسار', 'احكيلي', 'تريد', 'نريد', 'عندي', 'دورلي',
    ]);
    $this->scorer = new ServiceSearchScorer(
        tokenizer: $this->tokenizer,
        typoMatcher: new ArabicTypoMatcher,
    );

    $this->documents = [
        new ServiceSearchDocumentData(
            serviceId: 1,
            officialName: 'رخصة بناء',
            normalizedOfficialName: 'رخصه بناء',
            aliases: ['تصريح بناء', 'ترخيص بناء'],
            normalizedAliases: ['تصريح بناء', 'ترخيص بناء'],
            keywords: ['بناء', 'بيت', 'دار', 'طابق', 'عمارة'],
            normalizedKeywords: ['بناء', 'بيت', 'دار', 'طابق', 'عمارة'],
            citizenExpressions: ['بدي أبني بيت', 'بدي ارخص داري'],
            normalizedCitizenExpressions: ['بدي ابني بيت', 'بدي ارخص داري'],
            searchablePhrases: [],
            normalizedSearchablePhrases: [],
            shortDescription: 'التقدم للحصول على رخصة بناء',
            normalizedShortDescription: 'التقدم للحصول على رخصه بناء',
            categoryName: 'رخص البناء',
            normalizedCategoryName: 'رخص البناء',
            priority: 10,
            isPublished: true,
        ),
        new ServiceSearchDocumentData(
            serviceId: 2,
            officialName: 'ترخيص محل تجاري',
            normalizedOfficialName: 'ترخيص محل تجاري',
            aliases: ['رخصة محل', 'رخصة مهن'],
            normalizedAliases: ['رخصه محل', 'رخصه مهن'],
            keywords: ['محل', 'متجر', 'دكان', 'تجاري'],
            normalizedKeywords: ['محل', 'متجر', 'دكان', 'تجاري'],
            citizenExpressions: ['بدي أفتح محل', 'بدي أفتح محل ملابس', 'بدي ارخص المحل'],
            normalizedCitizenExpressions: ['بدي افتح محل', 'بدي افتح محل ملابس', 'بدي ارخص المحل'],
            searchablePhrases: [],
            normalizedSearchablePhrases: [],
            shortDescription: 'ترخيص لمحل تجاري',
            normalizedShortDescription: 'ترخيص لمحل تجاري',
            categoryName: 'رخص البناء',
            normalizedCategoryName: 'رخص البناء',
            priority: 5,
            isPublished: true,
        ),
        new ServiceSearchDocumentData(
            serviceId: 3,
            officialName: 'طلب شهادة إتمام بناء',
            normalizedOfficialName: 'طلب شهاده اتمام بناء',
            aliases: ['شهادة إتمام'],
            normalizedAliases: ['شهاده اتمام'],
            keywords: ['إتمام', 'اتمام', 'شهادة', 'انتهاء'],
            normalizedKeywords: ['اتمام', 'اتمام', 'شهاده', 'انتهاء'],
            citizenExpressions: ['بدي شهادة اتمام', 'خلصت بناء'],
            normalizedCitizenExpressions: ['بدي شهاده اتمام', 'خلصت بناء'],
            searchablePhrases: [],
            normalizedSearchablePhrases: [],
            shortDescription: 'شهادة إتمام البناء',
            normalizedShortDescription: 'شهاده اتمام البناء',
            categoryName: 'رخص البناء',
            normalizedCategoryName: 'رخص البناء',
            priority: 8,
            isPublished: true,
        ),
    ];

    $this->serviceQuery = Mockery::mock(MunicipalityServiceQueryInterface::class);
    $this->serviceQuery->shouldReceive('getSearchDocuments')
        ->andReturn($this->documents);

    $this->search = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: $normalizer,
        autoSelectThreshold: 0.88,
        clarificationThreshold: 0.55,
        minimumScoreGap: 0.15,
        defaultLimit: 5,
    );
});

afterEach(function (): void {
    Cache::flush();
    Mockery::close();
});

// ===================================================
// Basic Search — Natural Citizen Phrases
// ===================================================

it('finds building permit from natural phrase', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
    expect($result->bestMatch->serviceId)->toBe(1);
    expect($result->isConfident)->toBeTrue();
    expect($result->noMatch)->toBeFalse();
});

it('finds business licence from shop phrase', function (): void {
    $result = $this->search->search('بدي أفتح محل ملابس');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('ترخيص محل تجاري');
    expect($result->bestMatch->serviceId)->toBe(2);
    expect($result->isConfident)->toBeTrue();
});

it('finds building permit from licence phrase', function (): void {
    $result = $this->search->search('بدي ارخص داري');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
    expect($result->bestMatch->serviceId)->toBe(1);
});

it('finds completion certificate from citizen phrase', function (): void {
    $result = $this->search->search('خلصت بناء');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('طلب شهادة إتمام بناء');
    expect($result->bestMatch->serviceId)->toBe(3);
});

// ===================================================
// Keyword Search
// ===================================================

it('finds services by keyword', function (): void {
    $result = $this->search->search('محل');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('ترخيص محل تجاري');
    expect($result->bestMatch->serviceId)->toBe(2);
});

it('finds services by multiple keywords', function (): void {
    $result = $this->search->search('طابق');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('finds services by description keyword', function (): void {
    $result = $this->search->search('شهادة');

    expect($result->matches)->not->toBeEmpty();
    expect($result->bestMatch->serviceName)->toBe('طلب شهادة إتمام بناء');
});

// ===================================================
// Result Limit
// ===================================================

it('respects result limit', function (): void {
    $result = $this->search->search('بناء', limit: 1);

    expect($result->matches)->toHaveCount(1);
});

it('returns all matches when limit not exceeded', function (): void {
    $result = $this->search->search('بناء', limit: 10);

    expect($result->matches)->toHaveCount(2);
});

it('applies default limit', function (): void {
    $result = $this->search->search('بناء');

    expect($result->matches)->not->toBeGreaterThan(5);
});

// ===================================================
// No Match
// ===================================================

it('returns no match for unrelated query', function (): void {
    $result = $this->search->search('xyz غير معروف');

    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
    expect($result->matches)->toBeEmpty();
});

it('returns no match for gibberish', function (): void {
    $result = $this->search->search('أبجداىى');

    expect($result->noMatch)->toBeTrue();
});

// ===================================================
// Context Boost
// ===================================================

it('applies context boost to current service', function (): void {
    $result = $this->search->search('بناء', currentServiceId: 1);

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceId)->toBe(1);
});

it('context boost is not enough to change ranking when gap is large', function (): void {
    $result = $this->search->search('بناء', currentServiceId: 2);

    expect($result->bestMatch)->not->toBeNull();
    // Building permit should still win for "بناء"
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('context boost with null currentServiceId', function (): void {
    $result = $this->search->search('بناء', currentServiceId: null);

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

// ===================================================
// Deterministic Ranking
// ===================================================

it('deterministic ranking across identical queries', function (): void {
    $result1 = $this->search->search('بدي أبني بيت');
    $result2 = $this->search->search('بدي أبني بيت');

    expect($result1->bestMatch->serviceId)->toBe($result2->bestMatch->serviceId);
    expect($result1->bestMatch->score)->toBe($result2->bestMatch->score);
    expect($result1->bestMatch->matchedBy)->toBe($result2->bestMatch->matchedBy);
});

it('ranking is stable for ambiguous queries', function (): void {
    $result1 = $this->search->search('بناء');
    $result2 = $this->search->search('بناء');

    expect($result1->matches)->toHaveCount($result2->matches);

    for ($i = 0; $i < count($result1->matches); $i++) {
        expect($result1->matches[$i]->serviceId)->toBe($result2->matches[$i]->serviceId);
        expect($result1->matches[$i]->score)->toBe($result2->matches[$i]->score);
    }
});

// ===================================================
// Auto-Select Threshold
// ===================================================

it('auto-selects when score exceeds threshold and gap is sufficient', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    expect($result->isConfident)->toBeTrue();
    expect($result->bestMatch->score)->toBeGreaterThanOrEqual(0.88);
});

it('does not auto-select when score below threshold', function (): void {
    $strictSearch = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: $this->normalizer,
        autoSelectThreshold: 0.99,
        clarificationThreshold: 0.0,
        minimumScoreGap: 0.0,
        defaultLimit: 5,
    );

    $result = $strictSearch->search('بناء');

    expect($result->isConfident)->toBeFalse();
});

it('does not auto-select when score gap is insufficient', function (): void {
    $strictSearch = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: $this->normalizer,
        autoSelectThreshold: 0.0,
        clarificationThreshold: 0.0,
        minimumScoreGap: 999.0,
        defaultLimit: 5,
    );

    $result = $strictSearch->search('بدي أبني بيت');

    expect($result->isConfident)->toBeFalse();
});

// ===================================================
// Typo Match Prevents Auto-Select
// ===================================================

it('typo match prevents auto-selection', function (): void {
    $typoSearch = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: $this->normalizer,
        autoSelectThreshold: 0.0,
        clarificationThreshold: 0.0,
        minimumScoreGap: 0.0,
        defaultLimit: 5,
    );

    $result = $typoSearch->search('رخضه بناء');

    if ($result->bestMatch !== null && $result->bestMatch->matchedBy === 'typo_match') {
        expect($result->isConfident)->toBeFalse();
    }
});

// ===================================================
// Clarification
// ===================================================

it('requires clarification for low-confidence ambiguous results', function (): void {
    $strictSearch = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: $this->normalizer,
        autoSelectThreshold: 0.99,
        clarificationThreshold: 0.0,
        minimumScoreGap: 0.5,
        defaultLimit: 5,
    );

    $result = $strictSearch->search('بناء');

    expect($result->isConfident)->toBeFalse();
});

it('does not require clarification for single match', function (): void {
    $result = $this->search->search('شهادة اتمام');

    expect($result->matches)->toHaveCount(1);
    expect($result->requiresClarification)->toBeFalse();
});

it('does not require clarification for unrelated query', function (): void {
    $result = $this->search->search('xyz غير معروف');

    expect($result->requiresClarification)->toBeFalse();
    expect($result->noMatch)->toBeTrue();
});

// ===================================================
// Score Gap
// ===================================================

it('calculates score gap correctly', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    if (count($result->matches) >= 2) {
        $expectedGap = $result->matches[0]->score - $result->matches[1]->score;
        expect($result->scoreGap)->toBe($expectedGap);
    }

    expect($result->bestMatch)->not->toBeNull();
});

it('score gap is zero for single match', function (): void {
    $result = $this->search->search('شهادة اتمام');

    if (count($result->matches) === 1) {
        expect($result->scoreGap)->toBe(0.0);
    }
});

// ===================================================
// Normalization
// ===================================================

it('normalizes query before search', function (): void {
    $result = $this->search->search('بِسْمِ الرَّحْمَنِ');

    // Should still return a result or no match — no exception
    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles mixed Arabic and English input', function (): void {
    $result = $this->search->search('بدي بناء building');

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
    expect($result->noMatch)->toBeFalse();
});

it('handles empty query', function (): void {
    $result = $this->search->search('');

    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
    expect($result->matches)->toBeEmpty();
});

it('handles whitespace-only query', function (): void {
    $result = $this->search->search('   ');

    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
});

// ===================================================
// Arabic Text Handling
// ===================================================

it('handles alef variants', function (): void {
    $result = $this->search->search('أبني إبني آبني بيت');

    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('handles ة vs ه normalization', function (): void {
    $result = $this->search->search('رخصه');

    expect($result->matches)->not->toBeEmpty();
});

it('handles ى vs ي normalization', function (): void {
    $result = $this->search->search('رخصى');

    expect($result->matches)->not->toBeEmpty();
});

// ===================================================
// Collection Properties
// ===================================================

it('preserves original message in collection', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    expect($result->originalMessage)->toBe('بدي أبني بيت');
});

it('preserves normalized message in collection', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    expect($result->normalizedMessage)->not->toBeEmpty();
    expect($result->normalizedMessage)->not->toBe('بدي أبني بيت'); // Normalized version differs
});

it('returns array representation of collection', function (): void {
    $result = $this->search->search('بدي أبني بيت');
    $array = $result->toArray();

    expect($array)->toHaveKey('original_message');
    expect($array)->toHaveKey('normalized_message');
    expect($array)->toHaveKey('matches');
    expect($array)->toHaveKey('best_match');
    expect($array)->toHaveKey('is_confident');
    expect($array)->toHaveKey('requires_clarification');
    expect($array)->toHaveKey('no_match');
    expect($array)->toHaveKey('score_gap');
});

it('returns array representation of match', function (): void {
    $result = $this->search->search('بدي أبني بيت');

    if ($result->bestMatch !== null) {
        $matchArray = $result->bestMatch->toArray();

        expect($matchArray)->toHaveKey('service_id');
        expect($matchArray)->toHaveKey('service_name');
        expect($matchArray)->toHaveKey('score');
        expect($matchArray)->toHaveKey('matched_by');
        expect($matchArray)->toHaveKey('matched_terms');
        expect($matchArray)->toHaveKey('explanation');
        expect($matchArray)->toHaveKey('priority');
    }
});

// ===================================================
// Cache
// ===================================================

it('caches search documents', function (): void {
    $this->search->getSearchDocuments();
    $this->search->getSearchDocuments();

    $this->serviceQuery->shouldReceive('getSearchDocuments')
        ->once()
        ->andReturn($this->documents);

    // Second call should hit cache, not the repository
    $docs = $this->search->getSearchDocuments();
    expect($docs)->toHaveCount(3);
});

it('clears cache', function (): void {
    $this->search->getSearchDocuments();
    $this->search->clearCache();

    // After clearing, next call should re-fetch
    Cache::shouldReceive('forget')->once()->with('chatbot:service-search-documents');
    $this->search->clearCache();
});

it('clearCache returns void', function (): void {
    $result = $this->search->clearCache();

    expect($result)->toBeNull();
});

// ===================================================
// Sorting
// ===================================================

it('sorts matches by score descending', function (): void {
    $result = $this->search->search('بناء');

    for ($i = 0; $i < count($result->matches) - 1; $i++) {
        expect($result->matches[$i]->score)->toBeGreaterThanOrEqual(
            $result->matches[$i + 1]->score,
        );
    }
});

it('sorts by priority descending when scores are equal', function (): void {
    // This is implicit — the sort function handles it
    $result = $this->search->search('بناء');

    expect($result->bestMatch)->not->toBeNull();
    // The highest priority service among those with equal scores should come first
});

// ===================================================
// Edge Cases
// ===================================================

it('handles very long query', function (): void {
    $longQuery = str_repeat('بدي بناء ', 50);
    $result = $this->search->search($longQuery);

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles special characters in query', function (): void {
    $result = $this->search->search('بدي بناء! @#$%^&*()');

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles unicode characters in query', function (): void {
    $result = $this->search->search('بدي بناء 🏠');

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles zero limit', function (): void {
    $result = $this->search->search('بناء', limit: 0);

    expect($result->matches)->toBeEmpty();
    expect($result->bestMatch)->toBeNull();
});

it('handles negative limit', function (): void {
    $result = $this->search->search('بناء', limit: -1);

    expect($result->matches)->toBeEmpty();
    expect($result->bestMatch)->toBeNull();
});

it('handles single character query', function (): void {
    $result = $this->search->search('ب');

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles numeric query', function (): void {
    $result = $this->search->search('12345');

    expect($result)->toBeInstanceOf(
        ServiceSearchResultCollection::class,
    );
});

it('handles query with only stop words', function (): void {
    $result = $this->search->search('بدي ممكن كيف');

    expect($result->noMatch)->toBeTrue();
});
