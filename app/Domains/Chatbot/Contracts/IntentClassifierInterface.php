<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\IntentPredictionData;

interface IntentClassifierInterface
{
    public function predict(string $normalizedMessage): IntentPredictionData;
}
