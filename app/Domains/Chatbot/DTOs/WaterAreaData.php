<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class WaterAreaData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $slug = null,
        public ?string $description = null,
        public bool $isActive = true,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'isActive' => $this->isActive,
        ];
    }
}
