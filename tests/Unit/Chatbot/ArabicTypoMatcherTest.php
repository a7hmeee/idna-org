<?php

declare(strict_types=1);

use App\Domains\Chatbot\Services\ArabicTypoMatcher;

beforeEach(function (): void {
    $this->matcher = new ArabicTypoMatcher;
});

it('detects one-character typo', function (): void {
    $result = $this->matcher->match('بناء', 'بناة');
    expect($result)->not->toBeNull();
    expect($result)->toBeGreaterThan(0.0);
});

it('returns null for exact match (handled elsewhere)', function (): void {
    $result = $this->matcher->match('بناء', 'بناء');
    expect($result)->toBeNull();
});

it('rejects very short terms', function (): void {
    $result = $this->matcher->match('بي', 'بيت');
    expect($result)->toBeNull();
});

it('rejects large edit distance', function (): void {
    $result = $this->matcher->match('بناء', 'ترخيص');
    expect($result)->toBeNull();
});

it('exact match beats typo', function (): void {
    $typoResult = $this->matcher->match('بناء', 'بناة');
    expect($typoResult)->not->toBeNull();
    // Typo should have a penalty compared to exact
    expect($typoResult)->toBeLessThan(1.0);
    expect($typoResult)->toBeGreaterThan(0.0);
});

it('weak typo cannot auto-select', function (): void {
    // A weak typo should have score well below auto-select threshold
    $result = $this->matcher->match('رخصة', 'رخصه');
    if ($result !== null) {
        expect($result)->toBeLessThan(0.88);
    }
});

it('isTypoOnly returns correct values', function (): void {
    expect($this->matcher->isTypoOnly('بناء', 'بناء'))->toBeFalse();
    expect($this->matcher->isTypoOnly('بناء', 'بناة'))->toBeTrue();
    expect($this->matcher->isTypoOnly('بناء', 'ترخيص'))->toBeFalse();
});

it('handles empty input', function (): void {
    expect($this->matcher->match('', 'بناء'))->toBeNull();
    expect($this->matcher->match('بناء', ''))->toBeNull();
});
