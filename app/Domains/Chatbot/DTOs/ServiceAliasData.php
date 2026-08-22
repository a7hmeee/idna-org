<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ServiceAliasData
{
    public function __construct(
        public string $alias,
        public string $serviceKey,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            alias: $data['alias'],
            serviceKey: $data['service_key'] ?? $data['serviceKey'],
            description: $data['description'] ?? null,
            isActive: (bool) ($data['is_active'] ?? $data['isActive'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'alias' => $this->alias,
            'service_key' => $this->serviceKey,
            'description' => $this->description,
            'is_active' => $this->isActive,
        ];
    }
}
