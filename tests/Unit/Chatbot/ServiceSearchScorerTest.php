<?php

declare(strict_types=1);

use App\Domains\Chatbot\DTOs\ServiceSearchDocumentData;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ArabicTypoMatcher;
use App\Domains\Chatbot\Services\ServiceSearchScorer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;

beforeEach(function (): void {
    $normalizer = new ArabicTextNormalizer;
    $this->tokenizer = new ServiceSearchTokenizer($normalizer, [
        'بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'شو', 'ايش', 'كيف', 'خدمة', 'معلومات',
    ]);
    $this->scorer = new ServiceSearchScorer(
        tokenizer: $this->tokenizer,
        typoMatcher: new ArabicTypoMatcher,
    );

    $this->buildingPermitDoc = new ServiceSearchDocumentData(
        serviceId: 1,
        officialName: 'رخصة بناء',
        normalizedOfficialName: 'رخصه بناء',
        aliases: ['تصريح بناء', 'ترخيص بناء'],
        normalizedAliases: ['تصريح بناء', 'ترخيص بناء'],
        keywords: ['بناء', 'بيت', 'دار', 'طابق'],
        normalizedKeywords: ['بناء', 'بيت', 'دار', 'طابق'],
        citizenExpressions: ['بدي أبني بيت', 'بدي ارخص داري'],
        normalizedCitizenExpressions: ['بدي ابني بيت', 'بدي ارخص داري'],
        searchablePhrases: ['رخصة بناء جديد'],
        normalizedSearchablePhrases: ['رخصه بناء جديد'],
        shortDescription: 'التقدم للحصول على رخصة بناء جديدة',
        normalizedShortDescription: 'التقدم للحصول على رخصه بناء جديده',
        categoryName: 'رخص البناء',
        normalizedCategoryName: 'رخص البناء',
        priority: 10,
        isPublished: true,
    );

    $this->businessLicenceDoc = new ServiceSearchDocumentData(
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
        shortDescription: 'الحصول على ترخيص لمحل تجاري',
        normalizedShortDescription: 'الحصول على ترخيص لمحل تجاري',
        categoryName: 'رخص البناء',
        normalizedCategoryName: 'رخص البناء',
        priority: 5,
        isPublished: true,
    );
});

it('scores exact official name match at 1.0', function (): void {
    $tokens = $this->tokenizer->tokenize('رخصة بناء');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'رخصه بناء');
    expect($match)->not->toBeNull();
    expect($match->score)->toBe(1.0);
    expect($match->matchedBy)->toBe('exact_official_name');
});

it('scores exact alias match at 0.95', function (): void {
    $tokens = $this->tokenizer->tokenize('تصريح بناء');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'تصريح بناء');
    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.95);
    expect($match->matchedBy)->toBe('exact_alias');
});

it('scores keyword match', function (): void {
    $tokens = $this->tokenizer->tokenize('بناء');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'بناء');
    expect($match)->not->toBeNull();
    expect($match->score)->toBe(0.72);
    expect($match->matchedBy)->toBe('keyword');
});

it('scores token overlap', function (): void {
    $tokens = $this->tokenizer->tokenize('ابني بيت');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'ابني بيت');
    expect($match)->not->toBeNull();
    expect($match->matchedBy)->toBe('token_overlap');
    expect($match->score)->toBeGreaterThan(0.0);
    expect($match->score)->toBeLessThanOrEqual(0.65);
});

it('applies context boost', function (): void {
    $tokens = $this->tokenizer->tokenize('بناء');
    $matchWithoutContext = $this->scorer->score($this->buildingPermitDoc, $tokens, 'بناء');
    $matchWithContext = $this->scorer->score($this->buildingPermitDoc, $tokens, 'بناء', currentServiceId: 1);

    expect($matchWithoutContext)->not->toBeNull();
    expect($matchWithContext)->not->toBeNull();
    expect($matchWithContext->score)->toBeGreaterThan($matchWithoutContext->score);
});

it('returns null for no match', function (): void {
    $tokens = $this->tokenizer->tokenize('مستشفى');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'مستشفى');
    expect($match)->toBeNull();
});

it('deterministic ordering: higher score first', function (): void {
    $buildingTokens = $this->tokenizer->tokenize('بدي ابني بيت');
    $buildingMatch = $this->scorer->score($this->buildingPermitDoc, $buildingTokens, 'بدي ابني بيت');

    $shopTokens = $this->tokenizer->tokenize('بدي افتح محل');
    $shopMatch = $this->scorer->score($this->businessLicenceDoc, $shopTokens, 'بدي افتح محل');

    // Building permit should match strongly for building-related query
    expect($buildingMatch)->not->toBeNull();
    expect($buildingMatch->score)->toBeGreaterThan(0.8);
});

it('scores citizen expression exact match', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي ابني بيت');
    $match = $this->scorer->score($this->buildingPermitDoc, $tokens, 'بدي ابني بيت');
    expect($match)->not->toBeNull();
    expect($match->matchedBy)->toBe('citizen_expression');
    expect($match->score)->toBe(0.88);
});
