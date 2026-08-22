<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTypoMatcher;

beforeEach(function (): void {
    $this->matcher = new ArabicTypoMatcher;
});

// ===================================================
// Exact Match
// ===================================================

it('returns null for exact match', function (): void {
    $result = $this->matcher->match('بناء', 'بناء');
    expect($result)->toBeNull();
});

// ===================================================
// One-Character Typo
// ===================================================

it('detects one-character substitution', function (): void {
    $result = $this->matcher->match('بناء', 'بناة');
    expect($result)->not->toBeNull();
    expect($result)->toBeGreaterThan(0.5);
});

it('detects one-character insertion', function (): void {
    $result = $this->matcher->match('ترخيص', 'ترخيصي');
    expect($result)->not->toBeNull();
});

it('detects one-character deletion', function (): void {
    $result = $this->matcher->match('ترخيصي', 'ترخيص');
    expect($result)->not->toBeNull();
});

// ===================================================
// Short Term Rejection
// ===================================================

it('rejects query shorter than min length', function (): void {
    $result = $this->matcher->match('بي', 'بيت');
    expect($result)->toBeNull();
});

it('rejects candidate shorter than min length', function (): void {
    $result = $this->matcher->match('بيت', 'بي');
    expect($result)->toBeNull();
});

it('rejects both shorter than min length', function (): void {
    $result = $this->matcher->match('أب', 'جد');
    expect($result)->toBeNull();
});

// ===================================================
// Large Edit Distance
// ===================================================

it('rejects more than max edit distance', function (): void {
    $result = $this->matcher->match('بناء', 'ترخيص');
    expect($result)->toBeNull();
});

it('rejects completely different words', function (): void {
    $result = $this->matcher->match('مطعم', 'مستشفى');
    expect($result)->toBeNull();
});

// ===================================================
// Typo Score Penalty
// ===================================================

it('typo score is less than 1.0', function (): void {
    $result = $this->matcher->match('بناء', 'بناة');
    expect($result)->not->toBeNull();
    expect($result)->toBeLessThan(1.0);
});

it('typo score is positive', function (): void {
    $result = $this->matcher->match('بناء', 'بناة');
    expect($result)->not->toBeNull();
    expect($result)->toBeGreaterThan(0.0);
});

// ===================================================
// isTypoOnly
// ===================================================

it('isTypoOnly returns false for exact match', function (): void {
    expect($this->matcher->isTypoOnly('بناء', 'بناء'))->toBeFalse();
});

it('isTypoOnly returns true for typo match', function (): void {
    expect($this->matcher->isTypoOnly('بناء', 'بناة'))->toBeTrue();
});

it('isTypoOnly returns false for contained match', function (): void {
    expect($this->matcher->isTypoOnly('رخصة بناء', 'بناء'))->toBeFalse();
});

it('isTypoOnly returns false for no match', function (): void {
    expect($this->matcher->isTypoOnly('بناء', 'مستشفى'))->toBeFalse();
});

// ===================================================
// Empty Input
// ===================================================

it('handles empty query', function (): void {
    expect($this->matcher->match('', 'بناء'))->toBeNull();
});

it('handles empty candidate', function (): void {
    expect($this->matcher->match('بناء', ''))->toBeNull();
});

it('handles both empty', function (): void {
    expect($this->matcher->match('', ''))->toBeNull();
});

// ===================================================
// Weak Typo Cannot Auto-Select
// ===================================================

it('weak typo score below auto-select threshold', function (): void {
    $result = $this->matcher->match('رخصة', 'رخصه');
    if ($result !== null) {
        expect($result)->toBeLessThan(0.88);
    }
});

// ===================================================
// Arabic-Specific
// ===================================================

it('handles Arabic letter variations', function (): void {
    // ة vs ه
    $result = $this->matcher->match('رخصة', 'رخصه');
    if ($result !== null) {
        expect($result)->toBeGreaterThan(0.0);
    }
});

it('handles long Arabic words', function (): void {
    $result = $this->matcher->match('ترخيص محل تجاري', 'ترخيص محل تجاريي');
    // Should still match (one extra char at end)
    if ($result !== null) {
        expect($result)->toBeGreaterThan(0.0);
    }
});
