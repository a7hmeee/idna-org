<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class EngineeringOfficeSummaryData
{
    public function __construct(
        public int $id,
        public string $officeName,
        public ?string $slug = null,
        public ?string $engineerName = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $approvalStatus = null,
        public string $status = 'active',
    ) {}
}
