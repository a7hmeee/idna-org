<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ClarificationData
{
    public function __construct(
        public bool $needsClarification = false,
        public string $message = '',
        public array $options = [],
        public string $type = 'service',
        public ?string $domain = null,
        public ?string $entityType = null,
        public ?int $entityId = null,
        public ?int $selectedOption = null,
        public ?string $selectedServiceName = null,
        public ?int $selectedServiceId = null,
        public ?int $selectedAreaId = null,
        public ?string $selectedAreaName = null,
    ) {}

    public function toArray(): array
    {
        return [
            'needs_clarification' => $this->needsClarification,
            'message' => $this->message,
            'options' => $this->options,
            'type' => $this->type,
            'domain' => $this->domain,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
            'selected_option' => $this->selectedOption,
            'selected_service_name' => $this->selectedServiceName,
            'selected_service_id' => $this->selectedServiceId,
            'selected_area_id' => $this->selectedAreaId,
            'selected_area_name' => $this->selectedAreaName,
        ];
    }
}
