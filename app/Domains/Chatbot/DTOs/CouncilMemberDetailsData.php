<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class CouncilMemberDetailsData
{
    public function __construct(
        public int $id,
        public string $fullName,
        public ?string $slug = null,
        public ?string $position = null,
        public ?string $qualification = null,
        public ?string $profession = null,
        public ?string $bio = null,
        public ?string $committee = null,
        public ?string $termStart = null,
        public ?string $termEnd = null,
        public ?string $yearsOfExperience = null,
        public string $status = 'active',
        public bool $isPublic = true,
    ) {}
}
