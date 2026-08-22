<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ModelTrainingResultData
{
    public function __construct(
        public bool $success,
        public string $version,
        public ?string $artifactPath = null,
        public int $examplesCount = 0,
        public int $intentsCount = 0,
        public ?string $datasetFingerprint = null,
        public ?int $trainingDurationMs = null,
        public array $metrics = [],
        public ?string $error = null,
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'version' => $this->version,
            'artifact_path' => $this->artifactPath,
            'examples_count' => $this->examplesCount,
            'intents_count' => $this->intentsCount,
            'dataset_fingerprint' => $this->datasetFingerprint,
            'training_duration_ms' => $this->trainingDurationMs,
            'metrics' => $this->metrics,
            'error' => $this->error,
        ];
    }
}
