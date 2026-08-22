<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\DTOs;

final readonly class ContactRequestData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $phone,
        public string $message,
        public ?string $department = null,
    ) {}
}
