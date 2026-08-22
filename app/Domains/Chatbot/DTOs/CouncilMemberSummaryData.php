<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class CouncilMemberSummaryData
{
    public function __construct(
        public int $id,
        public string $fullName,
        public ?string $position = null,
        public ?string $qualification = null,
        public ?string $committee = null,
        public string $status = 'active',
    ) {}
}
