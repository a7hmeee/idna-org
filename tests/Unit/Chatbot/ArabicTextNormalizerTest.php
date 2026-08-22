<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTextNormalizer;

it('normalizes alef variants to ا', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('أحمد'))->toBe('احمد');
    expect($normalizer->normalize('إيمان'))->toBe('ايمان');
    expect($normalizer->normalize('آدم'))->toBe('ادم');
    expect($normalizer->normalize('ٱلف'))->toBe('الف');
});

it('normalizes ى to ي', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مستشفى'))->toBe('مستشفي');
    expect($normalizer->normalize('كرى'))->toBe('كري');
});

it('normalizes ؤ to و', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مؤمن'))->toBe('مومن');
});

it('normalizes ئ to ي', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('سئال'))->toBe('سيال');
    expect($normalizer->normalize('مسئول'))->toBe('مسيول');
});

it('removes Arabic diacritics', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مُحَمَّد'))->toBe('محمد');
    expect($normalizer->normalize('كَيْفَ'))->toBe('كيف');
    expect($normalizer->normalize('فَعَّال'))->toBe('فعال');
});

it('removes punctuation', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مرحبا! كيف حالك؟'))->toBe('مرحبا كيف حالك');
    expect($normalizer->normalize('الخدمات: ١, ٢, ٣'))->toBe('الخدمات ١ ٢ ٣');
});

it('collapses repeated whitespace', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مرحبا    كيف     حالك'))->toBe('مرحبا كيف حالك');
    expect($normalizer->normalize('  مساء  الخير  '))->toBe('مساء الخير');
});

it('preserves numbers', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('الرسوم 100 شيكل'))->toBe('الرسوم 100 شيكل');
    expect($normalizer->normalize('الخدمة رقم 5'))->toBe('الخدمة رقم 5');
});

it('preserves Latin letters and lowercases them', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('Service ABC'))->toBe('service abc');
    expect($normalizer->normalize('WATER SCHEDULE'))->toBe('water schedule');
});

it('handles empty input safely', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize(''))->toBe('');
    expect($normalizer->normalize('   '))->toBe('');
});

it('does not convert ة to ه', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مكتبة'))->toBe('مكتبة');
    expect($normalizer->normalize('رؤية'))->toBe('روية');
});

it('preserves Arabic letters correctly', function (): void {
    $normalizer = new ArabicTextNormalizer;
    expect($normalizer->normalize('مرحبا بالعالم'))->toBe('مرحبا بالعالم');
    expect($normalizer->normalize('خدمات البلدية'))->toBe('خدمات البلدية');
});
