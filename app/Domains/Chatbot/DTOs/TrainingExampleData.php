<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class TrainingExampleData
{
    public function __construct(
        public int $intentId,
        public string $text,
        public string $normalizedText,
        public string $source = 'seed',
        public string $locale = 'ar',
        public bool $isActive = true,
        public bool $isVerified = true,
        public float $weight = 1.0,
        public ?string $notes = null,
        public ?int $createdBy = null,
    ) {}

    public function toArray(): array
    {
        return [
            'chat_intent_id' => $this->intentId,
            'text' => $this->text,
            'normalized_text' => $this->normalizedText,
            'source' => $this->source,
            'locale' => $this->locale,
            'is_active' => $this->isActive,
            'is_verified' => $this->isVerified,
            'weight' => $this->weight,
            'notes' => $this->notes,
            'created_by' => $this->createdBy,
        ];
    }
}
