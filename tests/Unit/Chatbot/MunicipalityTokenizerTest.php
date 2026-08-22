<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\MunicipalityTokenizer;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->tokenizer = new MunicipalityTokenizer($this->normalizer);
});

it('tokenizes a simple Arabic sentence', function (): void {
    $tokens = $this->tokenizer->tokenize('مرحبا كيف حالك');

    expect($tokens)->toBe(['مرحبا', 'كيف', 'حالك']);
});

it('tokenizes with normalized forms', function (): void {
    $tokens = $this->tokenizer->tokenize('أهلاً وسهلاً');

    expect($tokens)->toContain('اهلا');
    expect($tokens)->toContain('وسهلا');
});

it('returns empty array for empty string', function (): void {
    $tokens = $this->tokenizer->tokenize('');

    expect($tokens)->toBe([]);
});

it('returns empty array for whitespace only', function (): void {
    $tokens = $this->tokenizer->tokenize('   ');

    expect($tokens)->toBe([]);
});

it('handles mixed Arabic and numbers', function (): void {
    $tokens = $this->tokenizer->tokenize('رخصة 2024');

    expect($tokens)->toContain('رخصة');
    expect($tokens)->toContain('2024');
});

it('builds vocabulary from multiple samples', function (): void {
    $samples = ['مرحبا بك', 'كيف حالك'];

    $vocab = $this->tokenizer->getVocabulary($samples);

    expect($vocab)->toContain('مرحبا');
    expect($vocab)->toContain('بك');
    expect($vocab)->toContain('كيف');
    expect($vocab)->toContain('حالك');
});

it('vocabulary has unique tokens', function (): void {
    $samples = ['مرحبا مرحبا', 'مرحبا بك'];

    $vocab = $this->tokenizer->getVocabulary($samples);

    $counts = array_count_values($vocab);

    foreach ($counts as $count) {
        expect($count)->toBe(1);
    }
});

it('handles special characters in tokenization', function (): void {
    $tokens = $this->tokenizer->tokenize('كم سعر؟');

    expect($tokens)->toContain('كم');
    expect($tokens)->toContain('سعر');
    expect($tokens)->not->toContain('سعر؟');
});
