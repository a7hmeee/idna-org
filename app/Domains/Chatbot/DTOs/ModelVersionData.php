<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class ModelVersionData
{
    public function __construct(
        public string $version,
        public string $status = 'inactive',
        public ?string $path = null,
        public ?string $metadata = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            version: $data['version'],
            status: $data['status'] ?? 'inactive',
            path: $data['path'] ?? null,
            metadata: $data['metadata'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'version' => $this->version,
            'status' => $this->status,
            'path' => $this->path,
            'metadata' => $this->metadata,
        ];
    }
}
