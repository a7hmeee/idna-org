<?php

declare(strict_types=1);

use App\Domains\CitizenWorkflows\Services\ConfirmationFlow;

beforeEach(function (): void {
    $this->confirmationFlow = new ConfirmationFlow;
});

it('detects confirm words', function (string $word): void {
    expect($this->confirmationFlow->isConfirm($word))->toBeTrue();
})->with(['نعم', 'تأكيد', 'أكد', 'اكيد', 'أكيد', 'اي', 'إي', 'yes', 'confirm']);

it('detects cancel words', function (string $word): void {
    expect($this->confirmationFlow->isCancel($word))->toBeTrue();
})->with(['لا', 'الغاء', 'إلغاء', 'يلغي', 'no', 'cancel', 'تراجع', 'رجوع']);

it('returns false for neutral words', function (): void {
    expect($this->confirmationFlow->isConfirm('ربما'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('ربما'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm(''))->toBeFalse();
    expect($this->confirmationFlow->isCancel(''))->toBeFalse();
});

it('returns confirmation actions', function (): void {
    $actions = $this->confirmationFlow->getConfirmationActions();

    expect($actions)->toHaveCount(2);
    expect($actions[0]['value'])->toBe('تأكيد');
    expect($actions[1]['value'])->toBe('إلغاء');
});

it('detects confirm phrases', function (string $phrase): void {
    expect($this->confirmationFlow->isConfirm($phrase))->toBeTrue();
})->with(['نعم صحيح', 'نعم تاكيد', 'تمام اكد', 'نعم بالتأكيد', 'نعم أكيد']);

it('detects a confirm word anywhere as a whole word', function (): void {
    expect($this->confirmationFlow->isConfirm('نعم بالتأكيد أوافق'))->toBeTrue();
    expect($this->confirmationFlow->isConfirm('سأكمل، نعم'))->toBeTrue();
});

it('does not treat confirm words inside other words as confirm', function (): void {
    expect($this->confirmationFlow->isConfirm('الوظائف'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm('وظائف'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm('الخدمات الصحية'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm('الشؤون الإدارية'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm('الخدمات'))->toBeFalse();
    expect($this->confirmationFlow->isConfirm('خدمات'))->toBeFalse();
});

it('does not treat cancel words inside other words as cancel', function (): void {
    expect($this->confirmationFlow->isCancel('الخدمات الإلكترونية'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('الخدمات الصحية'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('الخدمات'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('أعضاء المجلس البلدي'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('البنية التحتية'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('الشؤون الإدارية'))->toBeFalse();
});

it('still treats a standalone لا as cancel', function (): void {
    expect($this->confirmationFlow->isCancel('لا'))->toBeTrue();
    expect($this->confirmationFlow->isCancel('لا أريد'))->toBeTrue();
});

it('treats مش صحيح as cancel (checked before confirm by the engine)', function (): void {
    expect($this->confirmationFlow->isCancel('مش صحيح'))->toBeTrue();
});

it('does not match confirm or cancel words with punctuation attached', function (): void {
    expect($this->confirmationFlow->isConfirm('الوظائف؟'))->toBeFalse();
    expect($this->confirmationFlow->isCancel('الخدمات!'))->toBeFalse();
});

it('matches global cancel patterns', function (): void {
    expect($this->confirmationFlow->isGlobalCancel('إلغاء الطلب'))->toBeTrue();
    expect($this->confirmationFlow->isGlobalCancel('الغاء'))->toBeTrue();
    expect($this->confirmationFlow->isGlobalCancel('الخدمات'))->toBeFalse();
});

it('detects the expanded abandonment cancellations', function (string $word): void {
    expect($this->confirmationFlow->isCancel($word))->toBeTrue();
    expect($this->confirmationFlow->isGlobalCancel($word))->toBeTrue();
})->with([
    'انهاء', 'إنهاء', 'خلاص', 'خلص', 'كفاية', 'ما بد', 'ما بدي',
    'ما بدي اكمل', 'ما بدي أكمل', 'ما بديش', 'ما بديش اكمل', 'بدي انهي',
]);

it('matches the expanded global cancel patterns', function (string $phrase): void {
    expect($this->confirmationFlow->isGlobalCancel($phrase))->toBeTrue();
})->with(['انهاء الطلب', 'انهاء الشكوى', 'ما بدي اكمل', 'مش بدي اكمل', 'بدي انهي']);
