<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ModelTrainingResultData;

interface IntentModelTrainerInterface
{
    public function train(?string $algorithm = null, int $minimumExamples = 10, bool $activate = false): ModelTrainingResultData;
}
