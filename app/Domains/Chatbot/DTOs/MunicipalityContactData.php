<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class MunicipalityContactData
{
    public function __construct(
        public string $type,
        public string $value,
        public ?string $label = null,
        public ?string $url = null,
        public ?string $icon = null,
        public bool $isActive = true,
    ) {}
}
