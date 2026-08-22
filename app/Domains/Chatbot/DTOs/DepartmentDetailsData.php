<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\DTOs;

final readonly class DepartmentDetailsData
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $slug = null,
        public ?string $shortDescription = null,
        public ?string $description = null,
        public ?string $managerName = null,
        public ?string $managerPosition = null,
        public ?string $phone = null,
        public ?string $extension = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $officeLocation = null,
        public ?string $workingHours = null,
        public ?string $vision = null,
        public ?string $mission = null,
        public ?string $responsibilities = null,
        public string $status = 'active',
    ) {}
}
