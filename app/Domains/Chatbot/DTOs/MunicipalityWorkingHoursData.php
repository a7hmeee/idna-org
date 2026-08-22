<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class MunicipalityWorkingHoursData
{
    public function __construct(
        public string $day,
        public ?string $openTime = null,
        public ?string $closeTime = null,
        public ?string $notes = null,
        public bool $isClosed = false,
    ) {}
}
