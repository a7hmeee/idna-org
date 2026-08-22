<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\MunicipalityServiceQueryInterface;
use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ArabicTypoMatcher;
use App\Domains\Chatbot\Services\ServiceSearchScorer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;
use App\Domains\Chatbot\Services\SmartServiceSearch;

beforeEach(function (): void {
    $normalizer = new ArabicTextNormalizer;
    $this->tokenizer = new ServiceSearchTokenizer($normalizer, [
        'بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'شو', 'ايش', 'كيف', 'خدمة', 'معلومات',
    ]);
    $this->scorer = new ServiceSearchScorer(
        tokenizer: $this->tokenizer,
        typoMatcher: new ArabicTypoMatcher,
    );

    // Mock documents
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
            citizenExpressions: ['بدي أفتح محل', 'بدي ارخص المحل'],
            normalizedCitizenExpressions: ['بدي افتح محل', 'بدي ارخص المحل'],
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

    // Mock the service query interface
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

it('finds building permit from natural phrase', function (): void {
    $result = $this->search->search('بدي أبني بيت');
    expect($result->bestMatch)->not->toBeNull();
    expect($result->isConfident)->toBeTrue();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('finds business licence from shop phrase', function (): void {
    $result = $this->search->search('بدي أفتح محل ملابس');
    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('ترخيص محل تجاري');
});

it('returns multiple candidates when ambiguous', function (): void {
    $result = $this->search->search('بناء');
    expect($result->matches)->toHaveCount(3);
    expect($result->bestMatch)->not->toBeNull();
    expect($result->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('respects result limit', function (): void {
    $result = $this->search->search('بناء', limit: 1);
    expect($result->matches)->toHaveCount(1);
});

it('returns no match for unrelated query', function (): void {
    $result = $this->search->search('xyz غير معروف');
    expect($result->noMatch)->toBeTrue();
    expect($result->bestMatch)->toBeNull();
});

it('applies context boost', function (): void {
    // Search with context pointing to service 2 (business licence)
    $resultWithContext = $this->search->search('بناء', currentServiceId: 2);
    expect($resultWithContext->bestMatch)->not->toBeNull();
    // Building permit should still win for "بناء"
    expect($resultWithContext->bestMatch->serviceName)->toBe('رخصة بناء');
});

it('deterministic ranking', function (): void {
    $result1 = $this->search->search('بدي أبني بيت');
    $result2 = $this->search->search('بدي أبني بيت');

    expect($result1->bestMatch->serviceId)->toBe($result2->bestMatch->serviceId);
    expect($result1->bestMatch->score)->toBe($result2->bestMatch->score);
});

it('requires clarification for low-confidence ambiguous results', function (): void {
    // Create search with very high thresholds to force clarification
    $strictSearch = new SmartServiceSearch(
        serviceQuery: $this->serviceQuery,
        tokenizer: $this->tokenizer,
        scorer: $this->scorer,
        normalizer: new ArabicTextNormalizer,
        autoSelectThreshold: 0.99,
        clarificationThreshold: 0.0,
        minimumScoreGap: 0.5,
        defaultLimit: 5,
    );

    $result = $strictSearch->search('بناء');
    expect($result->isConfident)->toBeFalse();
});
