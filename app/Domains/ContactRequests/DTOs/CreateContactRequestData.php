<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\DTOs;

use App\Domains\ContactRequests\Enums\ContactRequestStatus;

final readonly class CreateContactRequestData
{
    public function __construct(
        public string $name,
        public string $phone,
        public string $message,
        public ?string $email = null,
        public string $source = 'chatbot',
        public ?string $department = null,
        public ?string $sessionId = null,
        public ?int $userId = null,
        public ContactRequestStatus $status = ContactRequestStatus::Pending,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'message' => $this->message,
            'status' => $this->status->value,
            'source' => $this->source,
            'department' => $this->department,
            'session_id' => $this->sessionId,
            'user_id' => $this->userId,
        ];
    }
}
