<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\Enums\ChatbotIntent;

interface RuleIntentDetectorInterface
{
    public function detect(string $normalizedMessage): ChatbotIntent;
}
