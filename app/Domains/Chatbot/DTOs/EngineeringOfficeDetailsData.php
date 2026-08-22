<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class EngineeringOfficeDetailsData
{
    public function __construct(
        public int $id,
        public string $officeName,
        public ?string $slug = null,
        public ?string $engineerName = null,
        public ?string $licenseNumber = null,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $address = null,
        public array $specializations = [],
        public ?string $approvalStatus = null,
        public string $status = 'active',
        public ?string $expiresAt = null,
    ) {}
}
