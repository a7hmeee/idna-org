<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Enums\ChatbotIntent;

/**
 * Deterministic detection of explicit service-property follow-ups.
 *
 * This detector maps evidence like "المتطلبات", "شو متطلباتها", "قديش رسومها",
 * "وين بقدمها" to the matching service intent. It is only consulted when a
 * current service exists as passive context; it must never be used to block
 * a message that is a greeting, thanks, or an explicit municipality domain.
 *
 * All patterns must be written in ArabicTextNormalizer-normalized form
 * (أ/إ/آ => ا, ى => ي, terminal ه => ة) because the input is normalized
 * before matching.
 */
final readonly class ServicePropertyIntentDetector
{
    private const PROPERTY_PATTERNS = [
        ChatbotIntent::ServiceRequirements->value => [
            '/^شو\s*المطلوب/u',
            '/^ما\s*المطلوب/u',
            '/المطلوب/u',
            '/الاوراق\s*المطلوبة/u',
            '/الوثائق/u',
            '/المستندات/u',
            '/متطلباتها/u',
            '/متطلبات/u',
            '/شروط/u',
        ],
        ChatbotIntent::ServiceFees->value => [
            '/رسومها/u',
            '/التكلفة/u',
            '/تكلفتها/u',
            '/اسعارها/u',
            '/قديش\s*الرسوم/u',
            '/قديش\s*التكلفة/u',
            '/قديش\s*بتكلف/u',
            '/قديش\s*بدفع/u',
            '/كم\s*بتكلف/u',
            '/كم\s*تكلفة/u',
            '/كم\s*السعر/u',
            '/كم\s*سعر/u',
            '/كم\s*ادفع/u',
            '/رسوم/u',
        ],
        ChatbotIntent::ServiceApplicationSteps->value => [
            '/خطواتها/u',
            '/خطوات\s*التقديم/u',
            '/شو\s*الخطوات/u',
            '/الخطوات/u',
            '/كيف\s*اقدم/u',
            '/كيف\s*بقدم/u',
            '/طريقة\s*التقديم/u',
            '/بتقدمها/u',
            '/باقدمها/u',
            '/من\s*وين\s*ابلش/u',
        ],
        ChatbotIntent::ServiceDuration->value => [
            '/مدتها/u',
            '/مدة\s*الخدمة/u',
            '/المدة/u',
            '/كم\s*يوم/u',
            '/كم\s*بتاخذ/u',
            '/قديش\s*بتاخذ/u',
            '/قديش\s*بتطول/u',
        ],
        ChatbotIntent::ServiceLocation->value => [
            '/مكان\s*التقديم/u',
            '/وين\s*اقدم/u',
            '/وين\s*بقدم/u',
            '/بقدمها/u',
            '/وين\s*بروح/u',
            '/وين\s*اروح/u',
            '/فين\s*اقدم/u',
            '/اين\s*اقدم/u',
        ],
        ChatbotIntent::ServiceOnlineLink->value => [
            '/رابط\s*التقديم/u',
            '/رابط\s*الخدمة/u',
            '/وين\s*الرابط/u',
            '/الرابط/u',
            '/اقدم\s*اونلاين/u',
            '/تقديم\s*الكتروني/u',
        ],
        ChatbotIntent::ServiceOverview->value => [
            '/احكيلي\s*عنها/u',
            '/معلومات\s*عنها/u',
            '/نبذة\s*عنها/u',
            '/تفاصيلها/u',
            '/معلومات\s*عن\s*الخدمة/u',
        ],
    ];

    public function __construct(
        private readonly ArabicTextNormalizer $normalizer,
    ) {}

    /**
     * Returns the service intent for an explicit service-property follow-up,
     * or null when there is no such evidence.
     */
    public function detect(string $message): ?ChatbotIntent
    {
        $normalized = $this->normalizer->normalize($message);

        if ($normalized === '') {
            return null;
        }

        foreach (self::PROPERTY_PATTERNS as $intentValue => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $normalized)) {
                    return ChatbotIntent::tryFrom($intentValue);
                }
            }
        }

        return null;
    }
}
