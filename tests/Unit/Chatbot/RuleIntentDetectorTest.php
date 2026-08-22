<?php

declare(strict_types=1);

use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\RuleIntentDetector;

beforeEach(function (): void {
    $this->normalizer = new ArabicTextNormalizer;
    $this->detector = new RuleIntentDetector($this->normalizer);
});

it('detects greeting intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::Greeting);
})->with([
    'مرحبا',
    'السلام عليكم',
    'سلام',
    'هلا',
    'اهلين',
    'صباح الخير',
    'مساء الخير',
]);

it('detects thanks intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::Thanks);
})->with([
    'شكرا',
    'يعطيك العافيه',
    'يسلمو',
    'مشكور',
    'بارك الله فيك',
]);

it('detects service application steps intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceApplicationSteps);
})->with([
    'كيف اقدم',
    'كيف اقدم طلب',
    'خطوات التقديم',
    'شو الخطوات',
    'من وين ابلش',
    'كيف اسجل',
    'طريقه التقديم',
]);

it('detects requirements intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceRequirements);
})->with([
    'المتطلبات',
    'شو مطلوب',
    'الاوراق المطلوبه',
    'الوثائق',
    'المستندات',
    'شو اجيب معي',
]);

it('detects fees intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceFees);
})->with([
    'الرسوم',
    'التكلفه',
    'كم بتكلف',
    'قديش الرسوم',
    'كم السعر',
    'كم ادفع',
]);

it('detects duration intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceDuration);
})->with([
    'المده',
    'كم بتاخذ',
    'قديش بتطول',
    'متى تخلص',
    'ايام العمل',
]);

it('detects location intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceLocation);
})->with([
    'وين اقدم',
    'مكان التقديم',
    'اي قسم',
    'وين اروح',
]);

it('detects online link intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceOnlineLink);
})->with([
    'رابط التقديم',
    'اقدم اونلاين',
    'تقديم الكتروني',
    'ابدا الخدمه',
    'رابط الخدمه',
]);

it('detects overview intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceOverview);
})->with([
    'شو هي',
    'احكيلي عن',
    'معلومات عن',
    'تفاصيل الخدمه',
    'شرح الخدمه',
]);

it('detects service search intent', function (string $message): void {
    $normalized = $this->normalizer->normalize($message);
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceSearch);
})->with([
    'بدي خدمه',
    'ابحث عن خدمه',
    'خدمات البلديه',
    'شو في خدمات',
    'دورلي على',
]);

it('specific intent takes precedence over service search', function (): void {
    $normalized = $this->normalizer->normalize('بدي اعرف المتطلبات');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceRequirements);

    $normalized = $this->normalizer->normalize('بدي اعرف الرسوم');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceFees);

    $normalized = $this->normalizer->normalize('بدي خطوات التقديم');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::ServiceApplicationSteps);
});

it('returns unknown for empty message', function (): void {
    expect($this->detector->detect(''))->toBe(ChatbotIntent::Unknown);
});

it('returns unknown for unrelated message', function (): void {
    $normalized = $this->normalizer->normalize('السماء زرقاء اليوم');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::Unknown);

    $normalized = $this->normalizer->normalize('12345');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::Unknown);

    $normalized = $this->normalizer->normalize('hello world');
    expect($this->detector->detect($normalized))->toBe(ChatbotIntent::Unknown);
});
