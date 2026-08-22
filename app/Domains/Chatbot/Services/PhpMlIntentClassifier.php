<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatbotModelVersionRepositoryInterface;
use App\Domains\Chatbot\Contracts\IntentClassifierInterface;
use App\Domains\Chatbot\DTOs\IntentPredictionData;
use App\Domains\Chatbot\Enums\ChatbotIntent;
use Phpml\Classification\NaiveBayes;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\ModelManager;
use Psr\Log\LoggerInterface;

final class PhpMlIntentClassifier implements IntentClassifierInterface
{
    private ?NaiveBayes $cachedModel = null;

    private ?TokenCountVectorizer $cachedVectorizer = null;

    private ?TfIdfTransformer $cachedTransformer = null;

    private ?array $cachedLabels = null;

    private ?string $cachedVersion = null;

    private ?int $cachedVersionId = null;

    public function __construct(
        private ChatbotModelVersionRepositoryInterface $modelVersionRepository,
        private ArabicTextNormalizer $normalizer,
        private LoggerInterface $logger,
    ) {}

    public function predict(string $normalizedMessage): IntentPredictionData
    {
        try {
            $activeVersion = $this->modelVersionRepository->getActive();
        } catch (\Throwable $e) {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'Model query failed: '.$e->getMessage(),
            );
        }

        if ($activeVersion === null) {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'No active model version found.',
            );
        }

        $artifactPath = $activeVersion->path;
        $modelDir = storage_path('app/private/chatbot/models');
        $fullPath = $modelDir.'/'.basename($artifactPath);

        if (! file_exists($fullPath)) {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'Model artifact not found at: '.$fullPath,
            );
        }

        $version = $activeVersion->version;
        $pipelinePath = $modelDir.'/pipeline-'.$version.'.ser';

        if (! file_exists($pipelinePath)) {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'Pipeline data not found.',
            );
        }

        try {
            if ($this->cachedModel === null || $this->cachedVersion !== $version) {
                $modelManager = new ModelManager;
                $this->cachedModel = $modelManager->restoreFromFile($fullPath);
                $this->cachedVersion = $version;
                $this->cachedVersionId = $activeVersion->id;

                $pipelineData = unserialize(file_get_contents($pipelinePath));
                $this->cachedVectorizer = unserialize($pipelineData['vectorizer']);
                $this->cachedTransformer = unserialize($pipelineData['transformer']);
                $this->cachedLabels = $pipelineData['labels'];
            }
        } catch (\Throwable $e) {
            $this->cachedModel = null;
            $this->cachedVectorizer = null;
            $this->cachedTransformer = null;

            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'Model load failed: '.$e->getMessage(),
            );
        }

        try {
            $sample = [$normalizedMessage];
            $this->cachedVectorizer->transform($sample);
            $this->cachedTransformer->transform($sample);

            $predictedLabel = $this->cachedModel->predict($sample);

            $enumValue = ChatbotIntent::tryFrom($predictedLabel) ?? ChatbotIntent::Unknown;

            $probs = [];
            $score = 0.5;
            if (method_exists($this->cachedModel, 'predictProbabilities')) {
                try {
                    $probs = $this->cachedModel->predictProbabilities($sample);
                    if (isset($probs[$predictedLabel])) {
                        $score = (float) $probs[$predictedLabel];
                    }
                } catch (\Throwable $e) {
                    $score = 0.5;
                }
            }

            return new IntentPredictionData(
                intent: $enumValue,
                confidence: $score,
                source: 'ml',
                modelVersionId: $this->cachedVersionId,
                modelVersion: $this->cachedVersion,
                classProbabilities: $probs,
                accepted: true,
            );
        } catch (\Throwable $e) {
            return new IntentPredictionData(
                intent: ChatbotIntent::Unknown,
                confidence: 0.0,
                source: 'fallback',
                accepted: false,
                rejectionReason: 'Prediction error: '.$e->getMessage(),
            );
        }
    }
}
