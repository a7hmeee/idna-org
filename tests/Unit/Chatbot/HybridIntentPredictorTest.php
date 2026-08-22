<?php

declare(strict_types=1);

use App\Domains\Chatbot\Contracts\IntentClassifierInterface;
use App\Domains\Chatbot\Contracts\RuleIntentDetectorInterface;
use App\Domains\Chatbot\DTOs\IntentPredictionData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use App\Domains\Chatbot\Services\ArabicTextNormalizer;
use App\Domains\Chatbot\Services\HybridIntentPredictor;

beforeEach(function (): void {
    $this->ruleDetector = Mockery::mock(RuleIntentDetectorInterface::class);
    $this->classifier = Mockery::mock(IntentClassifierInterface::class);
    $this->normalizer = new ArabicTextNormalizer;
});

it('strong rule returns immediately for greeting', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Greeting);

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('مرحبا');

    expect($result->intent)->toBe(ChatbotIntent::Greeting);
    expect($result->confidence)->toBe(1.0);
    expect($result->source)->toBe('rule');
    expect($result->accepted)->toBeTrue();
    expect($result->matchedRule)->toBe('greeting');
});

it('strong rule returns immediately for thanks', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Thanks);

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('شكرا');

    expect($result->intent)->toBe(ChatbotIntent::Thanks);
    expect($result->confidence)->toBe(1.0);
    expect($result->source)->toBe('rule');
});

it('specific rule with non-weak match returns rule result', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceFees);

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('كم رسوم رخصة البناء');

    expect($result->intent)->toBe(ChatbotIntent::ServiceFees);
    expect($result->confidence)->toBe(0.85);
    expect($result->source)->toBe('rule');
    expect($result->accepted)->toBeTrue();
});

it('weak generic phrase with rule match falls back to ML', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceSearch);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::ServiceSearch,
            confidence: 0.85,
            source: 'ml',
            accepted: true,
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('بدي خدمة');

    expect($result->intent)->toBe(ChatbotIntent::ServiceSearch);
    expect($result->source)->toBe('ml');
    expect($result->accepted)->toBeTrue();
});

it('weak generic phrase with low confidence ML falls back to weak rule', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceSearch);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::ServiceSearch,
            confidence: 0.40,
            source: 'ml',
            accepted: true,
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('بدي خدمة');

    expect($result->intent)->toBe(ChatbotIntent::ServiceSearch);
    expect($result->source)->toBe('rule');
    expect($result->accepted)->toBeTrue();
});

it('ML with high confidence wins over weak rule', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceSearch);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::ServiceFees,
            confidence: 0.85,
            source: 'ml',
            accepted: true,
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('بدي خدمة');

    expect($result->intent)->toBe(ChatbotIntent::ServiceFees);
    expect($result->source)->toBe('ml');
    expect($result->accepted)->toBeTrue();
});

it('ML low confidence with no rule match returns unknown', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Unknown);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::Unknown,
            confidence: 0.20,
            source: 'ml',
            accepted: false,
            rejectionReason: 'Confidence below threshold',
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('xyz something random');

    expect($result->intent)->toBe(ChatbotIntent::Unknown);
    expect($result->accepted)->toBeFalse();
});

it('empty message returns unknown', function (): void {
    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('');

    expect($result->intent)->toBe(ChatbotIntent::Unknown);
    expect($result->confidence)->toBe(0.0);
    expect($result->accepted)->toBeFalse();
});

it('ML disabled mode falls back to rules', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceFees);

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
        mlEnabled: false,
    );

    $result = $predictor->predict('كم الرسوم');

    expect($result->intent)->toBe(ChatbotIntent::ServiceFees);
    expect($result->source)->toBe('rule');
    expect($result->accepted)->toBeTrue();
});

it('ML disabled with no rule match returns unknown', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Unknown);

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
        mlEnabled: false,
    );

    $result = $predictor->predict('xyz not understood');

    expect($result->intent)->toBe(ChatbotIntent::Unknown);
    expect($result->confidence)->toBe(0.0);
    expect($result->accepted)->toBeFalse();
    expect($result->rejectionReason)->toContain('ML is disabled');
});

it('ML prediction of Unknown with high confidence still returns Unknown', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Unknown);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::Unknown,
            confidence: 0.90,
            source: 'ml',
            accepted: true,
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('سعر الذهب اليوم');

    expect($result->intent)->toBe(ChatbotIntent::Unknown);
    expect($result->confidence)->toBe(0.90);
    expect($result->source)->toBe('ml');
});

it('uses custom threshold from constructor', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::Unknown);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::ServiceFees,
            confidence: 0.50,
            source: 'ml',
            accepted: true,
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
        defaultThreshold: 0.30,
    );

    $result = $predictor->predict('سعر');

    expect($result->intent)->toBe(ChatbotIntent::ServiceFees);
    expect($result->source)->toBe('ml');
    expect($result->accepted)->toBeTrue();
});

it('classifier failure does not crash predictor', function (): void {
    $this->ruleDetector->shouldReceive('detect')
        ->once()
        ->andReturn(ChatbotIntent::ServiceSearch);

    $this->classifier->shouldReceive('predict')
        ->once()
        ->andReturn(new IntentPredictionData(
            intent: ChatbotIntent::Unknown,
            confidence: 0.0,
            source: 'fallback',
            accepted: false,
            rejectionReason: 'No active model version found.',
        ));

    $predictor = new HybridIntentPredictor(
        ruleDetector: $this->ruleDetector,
        classifier: $this->classifier,
        normalizer: $this->normalizer,
    );

    $result = $predictor->predict('بدي خدمة');

    expect($result->intent)->toBe(ChatbotIntent::ServiceSearch);
    expect($result->source)->toBe('rule');
    expect($result->accepted)->toBeTrue();
});
