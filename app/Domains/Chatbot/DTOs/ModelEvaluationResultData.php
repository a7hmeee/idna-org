<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ModelEvaluationResultData
{
    public function __construct(
        public int $totalExamples = 0,
        public int $correctPredictions = 0,
        public float $accuracy = 0.0,
        public array $perIntentPrecision = [],
        public array $perIntentRecall = [],
        public array $perIntentF1 = [],
        public array $confusionMatrix = [],
        public int $rejectedCount = 0,
        public int $invalidCount = 0,
        public ?string $datasetFingerprint = null,
        public ?string $modelVersion = null,
        public ?string $error = null,
        public bool $reliable = true,
    ) {}

    public function toArray(): array
    {
        return [
            'total_examples' => $this->totalExamples,
            'correct_predictions' => $this->correctPredictions,
            'accuracy' => $this->accuracy,
            'per_intent_precision' => $this->perIntentPrecision,
            'per_intent_recall' => $this->perIntentRecall,
            'per_intent_f1' => $this->perIntentF1,
            'confusion_matrix' => $this->confusionMatrix,
            'rejected_count' => $this->rejectedCount,
            'invalid_count' => $this->invalidCount,
            'dataset_fingerprint' => $this->datasetFingerprint,
            'model_version' => $this->modelVersion,
            'error' => $this->error,
            'reliable' => $this->reliable,
        ];
    }
}
