<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

use App\Domains\Chatbot\Enums\ConversationState;

final readonly class ConversationStateData
{
    public function __construct(
        public ConversationState $state = ConversationState::Normal,
        public ?int $currentServiceId = null,
        public ?string $currentServiceName = null,
        public ?int $currentCategoryId = null,
        public ?string $currentCategoryName = null,
        public ?string $lastIntent = null,
        public ?string $previousIntent = null,
        public bool $needsClarification = false,
        public ?string $pendingField = null,
        public ?int $pendingSelectedOption = null,
        public array $clarificationOptions = [],
        public ?\DateTimeImmutable $lastInteractionAt = null,
        public bool $expired = false,
        public ?string $clientMessage = null,
        public ?string $currentDomain = null,
        public ?int $currentAreaId = null,
        public ?string $currentAreaName = null,
        public ?string $workflowDraftId = null,
        public ?string $workflowType = null,
        public bool $resetPending = false,
        public int $fallbackCount = 0,
    ) {}

    public function toMetadata(): array
    {
        return [
            'state' => $this->state->value,
            'current_service_id' => $this->currentServiceId,
            'current_service_name' => $this->currentServiceName,
            'current_category_id' => $this->currentCategoryId,
            'current_category_name' => $this->currentCategoryName,
            'last_intent' => $this->lastIntent,
            'previous_intent' => $this->previousIntent,
            'needs_clarification' => $this->needsClarification,
            'pending_field' => $this->pendingField,
            'pending_selected_option' => $this->pendingSelectedOption,
            'clarification_options' => $this->clarificationOptions,
            'last_interaction_at' => $this->lastInteractionAt?->format('Y-m-d H:i:s'),
            'current_domain' => $this->currentDomain,
            'current_area_id' => $this->currentAreaId,
            'current_area_name' => $this->currentAreaName,
            'workflow_draft_id' => $this->workflowDraftId,
            'workflow_type' => $this->workflowType,
            'reset_pending' => $this->resetPending,
            'fallback_count' => $this->fallbackCount,
        ];
    }

    public static function fromMetadata(?array $metadata, ?string $clientMessage = null): self
    {
        if ($metadata === null) {
            return new self(clientMessage: $clientMessage);
        }

        $lastInteractionAt = null;
        if (isset($metadata['last_interaction_at'])) {
            $dt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $metadata['last_interaction_at']);
            if ($dt !== false) {
                $lastInteractionAt = $dt;
            }
        }

        return new self(
            state: ConversationState::tryFrom($metadata['state'] ?? 'normal') ?? ConversationState::Normal,
            currentServiceId: $metadata['current_service_id'] ?? null,
            currentServiceName: $metadata['current_service_name'] ?? null,
            currentCategoryId: $metadata['current_category_id'] ?? null,
            currentCategoryName: $metadata['current_category_name'] ?? null,
            lastIntent: $metadata['last_intent'] ?? null,
            previousIntent: $metadata['previous_intent'] ?? null,
            needsClarification: $metadata['needs_clarification'] ?? false,
            pendingField: $metadata['pending_field'] ?? null,
            pendingSelectedOption: $metadata['pending_selected_option'] ?? null,
            clarificationOptions: $metadata['clarification_options'] ?? [],
            lastInteractionAt: $lastInteractionAt,
            clientMessage: $clientMessage,
            currentDomain: $metadata['current_domain'] ?? null,
            currentAreaId: $metadata['current_area_id'] ?? null,
            currentAreaName: $metadata['current_area_name'] ?? null,
            workflowDraftId: $metadata['workflow_draft_id'] ?? null,
            workflowType: $metadata['workflow_type'] ?? null,
            resetPending: $metadata['reset_pending'] ?? false,
            fallbackCount: (int) ($metadata['fallback_count'] ?? 0),
        );
    }
}
