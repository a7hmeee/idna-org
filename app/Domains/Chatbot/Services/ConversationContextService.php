<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Services;

use App\Domains\Chatbot\Contracts\ChatbotConversationRepositoryInterface;
use App\Domains\Chatbot\Contracts\ConversationContextInterface;
use App\Domains\Chatbot\DTOs\ConversationStateData;
use App\Domains\Chatbot\Enums\ConversationState;

final readonly class ConversationContextService implements ConversationContextInterface
{
    private const CONTEXT_TTL = 1200;

    public function __construct(
        private ChatbotConversationRepositoryInterface $conversationRepository,
        private int $contextTtl = self::CONTEXT_TTL,
    ) {}

    public function getState(string $sessionId): ConversationStateData
    {
        $conversation = $this->conversationRepository->findActiveBySession($sessionId);

        if ($conversation === null) {
            return new ConversationStateData(expired: false);
        }

        $state = ConversationStateData::fromMetadata([
            'state' => $conversation->metadata['state'] ?? 'normal',
            'current_service_id' => $conversation->current_service_id ?? $conversation->metadata['current_service_id'] ?? null,
            'current_service_name' => $conversation->current_service_name ?? $conversation->metadata['current_service_name'] ?? null,
            'current_category_id' => $conversation->metadata['current_category_id'] ?? null,
            'current_category_name' => $conversation->metadata['current_category_name'] ?? null,
            'last_intent' => $conversation->last_intent ?? $conversation->metadata['last_intent'] ?? null,
            'previous_intent' => $conversation->previous_intent ?? $conversation->metadata['previous_intent'] ?? null,
            'needs_clarification' => $conversation->metadata['needs_clarification'] ?? false,
            'pending_field' => $conversation->metadata['pending_field'] ?? null,
            'pending_selected_option' => $conversation->metadata['pending_selected_option'] ?? null,
            'clarification_options' => $conversation->metadata['clarification_options'] ?? [],
            'last_interaction_at' => $conversation->context_updated_at?->format('Y-m-d H:i:s') ?? $conversation->metadata['last_interaction_at'] ?? null,
            'current_domain' => $conversation->metadata['current_domain'] ?? null,
            'current_area_id' => $conversation->metadata['current_area_id'] ?? null,
            'current_area_name' => $conversation->metadata['current_area_name'] ?? null,
            'workflow_draft_id' => $conversation->metadata['workflow_draft_id'] ?? null,
            'workflow_type' => $conversation->metadata['workflow_type'] ?? $conversation->metadata['workflow_type'] ?? null,
            'reset_pending' => $conversation->metadata['reset_pending'] ?? false,
            'fallback_count' => (int) ($conversation->metadata['fallback_count'] ?? 0),
        ]);

        if ($this->isExpired($sessionId)) {
            return new ConversationStateData(expired: true);
        }

        return $state;
    }

    public function updateState(string $sessionId, array $data): void
    {
        $conversation = $this->conversationRepository->findActiveBySession($sessionId);

        if ($conversation === null) {
            return;
        }

        $metadata = $conversation->metadata ?? [];
        $merged = array_merge($metadata, $data, [
            'last_interaction_at' => now()->format('Y-m-d H:i:s'),
        ]);

        $updateData = ['metadata' => $merged];

        // Also update the dedicated columns
        if (isset($data['current_service_id'])) {
            $updateData['current_service_id'] = $data['current_service_id'];
        }
        if (isset($data['current_service_name'])) {
            $updateData['current_service_name'] = $data['current_service_name'];
        }
        if (isset($data['last_intent'])) {
            $updateData['last_intent'] = $data['last_intent'];
        }
        if (isset($data['previous_intent'])) {
            $updateData['previous_intent'] = $data['previous_intent'];
        }
        $updateData['context_updated_at'] = now();

        $this->conversationRepository->update($conversation->id, $updateData);
    }

    public function expire(string $sessionId): void
    {
        $conversation = $this->conversationRepository->findActiveBySession($sessionId);

        if ($conversation === null) {
            return;
        }

        $metadata = $conversation->metadata ?? [];
        $metadata['expired'] = true;
        $metadata['state'] = ConversationState::Normal->value;

        $this->conversationRepository->update($conversation->id, [
            'metadata' => $metadata,
            'current_service_id' => null,
            'current_service_name' => null,
            'last_intent' => null,
            'previous_intent' => null,
            'context_updated_at' => now(),
        ]);
    }

    public function isExpired(string $sessionId): bool
    {
        $conversation = $this->conversationRepository->findActiveBySession($sessionId);

        if ($conversation === null) {
            return false;
        }

        $contextUpdatedAt = $conversation->context_updated_at;

        if ($contextUpdatedAt === null) {
            return false;
        }

        $diffInSeconds = now()->diffInSeconds($contextUpdatedAt);

        return $diffInSeconds > $this->contextTtl;
    }

    public function reset(string $sessionId): void
    {
        $conversation = $this->conversationRepository->findActiveBySession($sessionId);

        if ($conversation === null) {
            return;
        }

        $this->conversationRepository->update($conversation->id, [
            'metadata' => [
                'state' => ConversationState::Normal->value,
                'last_interaction_at' => now()->format('Y-m-d H:i:s'),
            ],
            'current_service_id' => null,
            'current_service_name' => null,
            'current_category_id' => null,
            'current_category_name' => null,
            'last_intent' => null,
            'previous_intent' => null,
            'context_updated_at' => now(),
        ]);
    }
}
