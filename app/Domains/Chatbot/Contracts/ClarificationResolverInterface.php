<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ClarificationData;
use App\Domains\Chatbot\DTOs\ConversationStateData;

interface ClarificationResolverInterface
{
    public function needsClarification(string $normalizedMessage, array $candidates): ?ClarificationData;

    public function resolveNumericSelection(string $normalizedMessage, ConversationStateData $state): ?ClarificationData;

    public function resolveOptionSelectionById(int $optionId, ConversationStateData $state): ?ClarificationData;

    public function resolveOptionSelection(string $normalizedMessage, ConversationStateData $state): ?array;

    public function resolveWaterAreaSelection(string $normalizedMessage, ConversationStateData $state): ?ClarificationData;

    public function resolvePronoun(string $normalizedMessage, ConversationStateData $state): ?ClarificationData;

    public function buildClarificationQuestion(array $candidates): ClarificationData;
}
