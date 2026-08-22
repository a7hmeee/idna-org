<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\ServiceSearchTokenizer;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->stopWords = [
        'بدي', 'اريد', 'أريد', 'حاب', 'ممكن', 'لو', 'سمحت',
        'اعرف', 'معرفه', 'معرفة', 'عن', 'على', 'في', 'من',
        'الى', 'إلى', 'الي', 'شو', 'ايش', 'إيش', 'كيف',
        'خدمة', 'معلومات', 'استفسار', 'احكيلي', 'تريد', 'نريد', 'عندي', 'دورلي',
    ];
    $this->tokenizer = new ServiceSearchTokenizer(
        normalizer: $this->normalizer,
        stopWords: $this->stopWords,
        minimumTokenLength: 2,
    );
});

// ===================================================
// Arabic Normalization
// ===================================================

it('normalizes alef variants', function (): void {
    $tokens = $this->tokenizer->tokenize('أبني إبني آبني');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('ابني');
});

it('normalizes ى to ي', function (): void {
    $tokens = $this->tokenizer->tokenize('رخصى');
    expect($tokens)->toContain('رخصي');
});

it('normalizes ؤ to و', function (): void {
    $tokens = $this->tokenizer->tokenize('مأمور');
    expect($tokens)->toContain('مأمور');
});

it('normalizes ئ to ي', function (): void {
    $tokens = $this->tokenizer->tokenize('أ备ئ');
    expect($tokens)->not->toContain('备ئ');
});

it('removes Arabic diacritics', function (): void {
    $tokens = $this->tokenizer->tokenize('بِسْمِ');
    expect($tokens)->not->toContain('بِسْمِ');
});

it('removes punctuation', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي بناء!');
    expect($tokens)->toContain('ابني');
    expect($tokens)->toContain('بناء');
    expect($tokens)->not->toContain('!');
});

it('collapses repeated whitespace', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي    بناء');
    expect($tokens)->toHaveCount(2);
});

// ===================================================
// Stop Word Removal
// ===================================================

it('removes stop words بدي', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
});

it('removes stop words أريد', function (): void {
    $tokens = $this->tokenizer->tokenize('أريد بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
});

it('removes stop words ممكن', function (): void {
    $tokens = $this->tokenizer->tokenize('ممكن بناء');
    expect($tokens)->toHaveCount(1);
});

it('removes stop words شو', function (): void {
    $tokens = $this->tokenizer->tokenize('شو الرسوم');
    expect($tokens)->toContain('الرسوم');
    expect($tokens)->not->toContain('شو');
});

it('removes stop words كيف', function (): void {
    $tokens = $this->tokenizer->tokenize('كيف أبني');
    expect($tokens)->not->toContain('كيف');
});

it('removes stop words خدمة', function (): void {
    $tokens = $this->tokenizer->tokenize('خدمة بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
});

it('removes multiple stop words together', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي ممكن بناء');
    expect($tokens)->toHaveCount(1);
    expect($tokens)->toContain('بناء');
});

// ===================================================
// Minimum Token Length
// ===================================================

it('rejects tokens shorter than minimum length', function (): void {
    $tokens = $this->tokenizer->tokenize('أ ب ج');
    expect($tokens)->toBeEmpty();
});

it('preserves tokens at minimum length', function (): void {
    $tokens = $this->tokenizer->tokenize('بناء');
    expect($tokens)->toContain('بناء');
});

it('preserves 2-char tokens after stop word removal', function (): void {
    $tokens = $this->tokenizer->tokenize('بيت');
    expect($tokens)->toContain('بيت');
});

// ===================================================
// Number Preservation
// ===================================================

it('preserves Arabic-Indic digits', function (): void {
    $tokens = $this->tokenizer->tokenize('١٢٣');
    expect($tokens)->toContain('١٢٣');
});

it('preserves Western digits', function (): void {
    $tokens = $this->tokenizer->tokenize('123');
    expect($tokens)->toContain('123');
});

it('preserves mixed numbers and text', function (): void {
    $tokens = $this->tokenizer->tokenize('رقم 5');
    expect($tokens)->toContain('5');
    expect($tokens)->toContain('رقم');
});

// ===================================================
// Duplicate Removal
// ===================================================

it('removes duplicate tokens', function (): void {
    $tokens = $this->tokenizer->tokenize('بناء بناء بناء');
    expect($tokens)->toHaveCount(1);
});

it('preserves different tokens', function (): void {
    $tokens = $this->tokenizer->tokenize('بناء ترخيص');
    expect($tokens)->toHaveCount(2);
});

// ===================================================
// Empty Input
// ===================================================

it('handles empty string', function (): void {
    expect($this->tokenizer->tokenize(''))->toBe([]);
});

it('handles whitespace only', function (): void {
    expect($this->tokenizer->tokenize('   '))->toBe([]);
});

it('handles string with only stop words', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي ممكن كيف');
    expect($tokens)->toBe([]);
});

// ===================================================
// tokenizePreserveStopWords
// ===================================================

it('preserves stop words when asked', function (): void {
    $tokens = $this->tokenizer->tokenizePreserveStopWords('بدي بناء');
    expect($tokens)->toHaveCount(2);
    expect($tokens)->toContain('بدي');
    expect($tokens)->toContain('بناء');
});

it('still removes duplicates in preserve mode', function (): void {
    $tokens = $this->tokenizer->tokenizePreserveStopWords('بدي بناء بدي');
    expect($tokens)->toHaveCount(2);
});

// ===================================================
// Complex Arabic Phrases
// ===================================================

it('tokenizes natural citizen phrase بدي أبني بيت', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أبني بيت');
    expect($tokens)->toHaveCount(2);
    expect($tokens)->toContain('ابني');
    expect($tokens)->toContain('بيت');
});

it('tokenizes natural citizen phrase بدي أفتح محل ملابس', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أفتح محل ملابس');
    expect($tokens)->toContain('افتح');
    expect($tokens)->toContain('محل');
    expect($tokens)->toContain('ملابس');
});

it('tokenizes natural citizen phrase بدي ارخص داري', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي ارخص داري');
    expect($tokens)->toContain('ارخص');
    expect($tokens)->toContain('داري');
});

it('tokenizes natural citizen phrase بدي أنقل اشتراك المياه', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أنقل اشتراك المياه');
    expect($tokens)->toContain('انقل');
    expect($tokens)->toContain('اشتراك');
    expect($tokens)->toContain('المياه');
});

it('tokenizes natural citizen phrase بدي أضيف طابق', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أضيف طابق');
    expect($tokens)->toContain('اضيف');
    expect($tokens)->toContain('طابق');
});

it('tokenizes natural citizen phrase بدي أعمل صيانة للدار', function (): void {
    $tokens = $this->tokenizer->tokenize('بدي أعمل صيانة للدار');
    expect($tokens)->toContain('اعمل');
    expect($tokens)->toContain('صيانه');
    expect($tokens)->toContain('للدار');
});
