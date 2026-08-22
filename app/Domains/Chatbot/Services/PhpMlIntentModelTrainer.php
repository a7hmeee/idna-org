<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatTrainingExampleRepositoryInterface;
use App\Domains\Chatbot\Contracts\IntentModelTrainerInterface;
use App\Domains\Chatbot\DTOs\ModelTrainingResultData;
use App\Domains\Chatbot\Repositories\EloquentChatbotModelVersionRepository;
use Phpml\Classification\NaiveBayes;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\ModelManager;
use Phpml\Tokenization\WhitespaceTokenizer;

final readonly class PhpMlIntentModelTrainer implements IntentModelTrainerInterface
{
    public function __construct(
        private ChatTrainingExampleRepositoryInterface $trainingExampleRepository,
        private ArabicTextNormalizer $normalizer,
        private MunicipalityTokenizer $tokenizer,
        private EloquentChatbotModelVersionRepository $modelVersionRepository,
    ) {}

    public function train(?string $algorithm = null, int $minimumExamples = 10, bool $activate = false): ModelTrainingResultData
    {
        $startTime = microtime(true);

        $examples = $this->trainingExampleRepository->getVerifiedActiveExamples();

        if ($examples->isEmpty()) {
            return new ModelTrainingResultData(
                success: false,
                version: '',
                error: 'Training dataset is empty.',
            );
        }

        $samples = [];
        $labels = [];
        $intentCounts = [];

        foreach ($examples as $example) {
            $normalized = $this->normalizer->normalize($example->text);
            if ($normalized === '') {
                continue;
            }
            $samples[] = $normalized;
            $label = $example->intent->name;
            $labels[] = $label;
            $intentCounts[$label] = ($intentCounts[$label] ?? 0) + 1;
        }

        if (count($samples) === 0) {
            return new ModelTrainingResultData(
                success: false,
                version: '',
                error: 'No valid normalized examples.',
            );
        }

        $uniqueLabels = array_unique($labels);
        if (count($uniqueLabels) < 2) {
            return new ModelTrainingResultData(
                success: false,
                version: '',
                error: 'Need at least 2 distinct intent classes for training.',
            );
        }

        foreach ($intentCounts as $intent => $count) {
            if ($count < $minimumExamples) {
                return new ModelTrainingResultData(
                    success: false,
                    version: '',
                    error: "Intent '{$intent}' has only {$count} examples, minimum {$minimumExamples} required.",
                );
            }
        }

        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer);
        $vectorizer->fit($samples);
        $vectorizer->transform($samples);

        $transformer = new TfIdfTransformer($samples);
        $transformer->transform($samples);

        $classifier = new NaiveBayes;
        $classifier->train($samples, $labels);

        $version = now()->format('YmdHis');

        $modelDir = storage_path('app/private/chatbot/models');
        if (! is_dir($modelDir)) {
            mkdir($modelDir, 0755, true);
        }

        $tempPath = $modelDir.'/intent-model-'.$version.'.tmp';
        $finalPath = $modelDir.'/intent-model-'.$version.'.model';

        try {
            $modelManager = new ModelManager;
            $modelManager->saveToFile($classifier, $tempPath);

            if (! file_exists($tempPath)) {
                return new ModelTrainingResultData(
                    success: false,
                    version: $version,
                    error: 'Serialization failed: temp file not created.',
                );
            }

            $restored = $modelManager->restoreFromFile($tempPath);
            if (! $restored instanceof NaiveBayes) {
                unlink($tempPath);

                return new ModelTrainingResultData(
                    success: false,
                    version: $version,
                    error: 'Serialization verification failed: restored object is not a NaiveBayes instance.',
                );
            }

            rename($tempPath, $finalPath);

            $pipelineData = [
                'vectorizer' => serialize($vectorizer),
                'transformer' => serialize($transformer),
                'labels' => $uniqueLabels,
                'intent_counts' => $intentCounts,
            ];

            $pipelinePath = $modelDir.'/pipeline-'.$version.'.ser';
            file_put_contents($pipelinePath, serialize($pipelineData));
        } catch (\Throwable $e) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }

            return new ModelTrainingResultData(
                success: false,
                version: $version,
                error: 'Training/serialization error: '.$e->getMessage(),
            );
        }

        $fingerprint = $this->trainingExampleRepository->datasetFingerprint();

        $metadata = [
            'algorithm' => 'naive_bayes',
            'dataset_fingerprint' => $fingerprint,
            'examples_count' => count($samples),
            'intents_count' => count($uniqueLabels),
            'intents' => $uniqueLabels,
            'intent_counts' => $intentCounts,
            'training_duration_ms' => (int) round((microtime(true) - $startTime) * 1000),
            'vectorizer' => 'TokenCountVectorizer',
            'transformer' => 'TfIdfTransformer',
        ];

        $modelVersion = $this->modelVersionRepository->create([
            'version' => $version,
            'status' => 'ready',
            'path' => 'intent-model-'.$version.'.model',
            'metadata' => $metadata,
        ]);

        if ($activate) {
            $this->modelVersionRepository->update($modelVersion->id, [
                'status' => 'active',
            ]);
        }

        return new ModelTrainingResultData(
            success: true,
            version: $version,
            artifactPath: $finalPath,
            examplesCount: count($samples),
            intentsCount: count($uniqueLabels),
            datasetFingerprint: $fingerprint,
            trainingDurationMs: $metadata['training_duration_ms'],
            metrics: [
                'intents' => $uniqueLabels,
                'intent_counts' => $intentCounts,
                'algorithm' => 'naive_bayes',
            ],
        );
    }
}
