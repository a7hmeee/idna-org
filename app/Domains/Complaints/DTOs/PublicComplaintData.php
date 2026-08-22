<?php

declare(strict_types=1);

namespace App\Domains\Complaints\DTOs;

use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;

final readonly class PublicComplaintData
{
    public function __construct(
        public string $citizenName,
        public string $phone,
        public ComplaintCategory $category,
        public string $subject,
        public string $description,
        public ?string $email = null,
        public ?string $nationalId = null,
        public ?string $location = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?array $attachments = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            citizenName: $data['citizenName'] ?? $data['citizen_name'] ?? '',
            phone: $data['phone'] ?? '',
            category: $data['category'] instanceof ComplaintCategory ? $data['category'] : ComplaintCategory::from($data['category']),
            subject: $data['subject'] ?? '',
            description: $data['description'] ?? '',
            email: $data['email'] ?? null,
            nationalId: $data['nationalId'] ?? $data['national_id'] ?? null,
            location: $data['location'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            attachments: $data['attachments'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'citizen_name' => $this->citizenName,
            'phone' => $this->phone,
            'email' => $this->email,
            'national_id' => $this->nationalId,
            'category' => $this->category->value,
            'subject' => $this->subject,
            'description' => $this->description,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'attachments' => $this->attachments,
            'priority' => ComplaintPriority::Medium,
            'status' => ComplaintStatus::Submitted,
            'submitted_at' => now(),
        ];
    }
}
