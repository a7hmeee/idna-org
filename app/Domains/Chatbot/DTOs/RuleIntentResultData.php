<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

use App\Domains\Chatbot\Enums\ChatbotIntent;

final readonly class RuleIntentResultData
{
    public function __construct(
        public ChatbotIntent $intent,
        public bool $matched,
        public ?string $matchedRule = null,
    ) {}

    public function toArray(): array
    {
        return [
            'intent' => $this->intent->value,
            'matched' => $this->matched,
            'matched_rule' => $this->matchedRule,
        ];
    }
}
