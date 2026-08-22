<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->stopWords = ['بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'شو', 'ايش', 'كيف', 'خدمة', 'معلومات'];
    $this->tokenizer = new ServiceSearchTokenizer(
        normalizer: $this->normalizer,
        stopWords: $this->stopWords,
        minimumTokenLength: 2,
    );
});

it('tokenizes normal Arabic text', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أبني بيت');
    expect($tokens)->toHaveCount(2);
    expect($tokens)->toContain('ابني');
    expect($tokens)->toContain('بيت');
});

it('removes stop words', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي خدمة بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
    expect($tokens)->not->toContain('بدي');
    expect($tokens)->not->toContain('خدمة');
});

it('removes duplicate tokens', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي بناء بناء بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
});

it('preserves numbers', function (): void {
    $tokens = $this->tokenizer->tokenize('رقم ١٢٣');
    expect($tokens)->toContain('١٢٣');
});

it('rejects tokens shorter than minimum length', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أ ب');
    expect($tokens)->toBeEmpty();
});

it('ignores empty input', function (): void {
    expect($this->tokenizer->tokenize(''))->toBeEmpty();
    expect($this->tokenizer->tokenize('   '))->toBeEmpty();
});

it('tokenizes without removing stop words when asked', function (): void {
    $tokens = $this->tokenizer->tokenizePreserveStopWords('بدي بناء');
    expect($tokens)->toHaveCount(2);
    expect($tokens)->toContain('بدي');
    expect($tokens)->toContain('بناء');
});

it('handles Arabic normalisation via normalizer', function (): void {
    $tokens = $this->tokenizer->tokenize('أريد تصريح بناء');
    expect($tokens)->not->toContain('اريد');
    expect($tokens)->not->toContain('أريد');
    expect($tokens)->toContain('تصريح');
    expect($tokens)->toContain('بناء');
});
