<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\HybridIntentPredictorInterface;
use App\Domains\Chatbot\Contracts\IntentClassifierInterface;
use App\Domains\Chatbot\Contracts\RuleIntentDetectorInterface;
use App\Domains\Chatbot\DTOs\IntentPredictionData;
use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class HybridIntentPredictor implements HybridIntentPredictorInterface
{
    private const STRONG_RULES = [
        ChatbotIntent::Greeting,
        ChatbotIntent::Thanks,
    ];

    private const WEAK_GENERIC_PHRASES = [
        'خدمة', 'معلومات', 'بدي', 'احكيلي', 'استفسار',
    ];

    public function __construct(
        private RuleIntentDetectorInterface $ruleDetector,
        private IntentClassifierInterface $classifier,
        private ArabicTextNormalizer $normalizer,
        private float $defaultThreshold = 0.70,
        private bool $mlEnabled = true,
        private bool $fallbackToRules = true,
    ) {}

    public function predict(string $normalizedMessage): IntentPredictionData
    {
        $message = trim($normalizedMessage);

        if ($message === '') {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'rule',
                accepted: false,
                rejectionReason: 'Empty message.',
            );
        }

        // Step 1: Strong rules
        $ruleIntent = $this->ruleDetector->detect($message);

        if (in_array($ruleIntent, self::STRONG_RULES, true)) {
            return new IntentPredictionData(
                intent: $ruleIntent,
                confidence: 1.0,
                source: 'rule',
                matchedRule: $ruleIntent->value,
                accepted: true,
            );
        }

        // Determine if the rule match is specific or weak/generic
        $isWeakRule = $this->isWeakMatch($message, $ruleIntent);

        if (! $isWeakRule && $ruleIntent !== ChatbotIntent::Unknown) {
            $threshold = $this->getRuleThreshold($ruleIntent);

            return new IntentPredictionData(
                intent: $ruleIntent,
                confidence: $threshold,
                source: 'rule',
                matchedRule: $ruleIntent->value,
                accepted: true,
            );
        }

        // Step 2: ML classification
        if ($this->mlEnabled) {
            $mlResult = $this->classifier->predict($message);

            if ($mlResult->accepted && $mlResult->confidence >= $this->defaultThreshold) {
                if ($mlResult->intent !== ChatbotIntent::Unknown) {
                    return new IntentPredictionData(
                        intent: $mlResult->intent,
                        confidence: $mlResult->confidence,
                        source: 'ml',
                        modelVersionId: $mlResult->modelVersionId,
                        modelVersion: $mlResult->modelVersion,
                        classProbabilities: $mlResult->classProbabilities,
                        accepted: true,
                    );
                }
            }

            // ML result below threshold — check if a weak rule result exists
            if ($this->fallbackToRules && $ruleIntent !== ChatbotIntent::Unknown) {
                return new IntentPredictionData(
                    intent: $ruleIntent,
                    confidence: $mlResult->confidence,
                    source: 'rule',
                    matchedRule: $ruleIntent->value,
                    accepted: true,
                );
            }

            // ML low confidence, no fallback
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: $mlResult->confidence,
                source: 'ml',
                modelVersionId: $mlResult->modelVersionId,
                modelVersion: $mlResult->modelVersion,
                accepted: false,
                rejectionReason: 'Confidence below threshold: '.$mlResult->confidence,
            );
        }

        // ML disabled
        if ($ruleIntent !== ChatbotIntent::Unknown) {
            return new IntentPredictionData(
                intent: $ruleIntent,
                confidence: 0.5,
                source: 'rule',
                matchedRule: $ruleIntent->value,
                accepted: true,
            );
        }

        return new IntentPredictionData(
            intent: ChatbotIntent::Unknown,
            confidence: 0.0,
            source: 'rule',
            accepted: false,
            rejectionReason: 'No rule matched and ML is disabled.',
        );
    }

    private function isWeakMatch(string $message, ChatbotIntent $intent): bool
    {
        if ($intent === ChatbotIntent::Unknown) {
            return true;
        }

        foreach (self::WEAK_GENERIC_PHRASES as $phrase) {
            if (str_contains($message, $phrase)) {
                return true;
            }
        }

        return false;
    }

    private function getRuleThreshold(ChatbotIntent $intent): float
    {
        return in_array($intent, self::STRONG_RULES, true) ? 1.0 : 0.85;
    }
}
