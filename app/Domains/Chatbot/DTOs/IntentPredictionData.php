<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class IntentPredictionData
{
    public function __construct(
        public ChatbotIntent $intent,
        public float $confidence,
        public string $source,
        public ?int $modelVersionId = null,
        public ?string $modelVersion = null,
        public ?string $matchedRule = null,
        public array $classProbabilities = [],
        public bool $accepted = true,
        public ?string $rejectionReason = null,
    ) {}

    public function toArray(): array
    {
        return [
            'intent' => $this->intent->value,
            'confidence' => $this->confidence,
            'source' => $this->source,
            'model_version_id' => $this->modelVersionId,
            'model_version' => $this->modelVersion,
            'matched_rule' => $this->matchedRule,
            'class_probabilities' => $this->classProbabilities,
            'accepted' => $this->accepted,
            'rejection_reason' => $this->rejectionReason,
        ];
    }
}
