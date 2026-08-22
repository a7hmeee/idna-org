<?php

declare(strict_types=1);

namespace App\Domains\Complaints\DTOs;

use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;

final readonly class ComplaintData
{
    public function __construct(
        public string $citizenName,
        public string $phone,
        public ComplaintCategory $category,
        public string $subject,
        public string $description,
        public ComplaintPriority $priority,
        public ComplaintStatus $status,
        public ?string $complaintNumber = null,
        public ?string $trackingNumber = null,
        public ?string $email = null,
        public ?string $nationalId = null,
        public ?int $departmentId = null,
        public ?string $location = null,
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?array $attachments = null,
        public ?string $internalNotes = null,
        public ?string $publicResponse = null,
        public ?int $assignedTo = null,
        public ?int $submittedBy = null,
        public ?string $submittedAt = null,
        public ?string $resolutionAt = null,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            citizenName: $data['citizenName'] ?? $data['citizen_name'] ?? '',
            phone: $data['phone'] ?? '',
            category: $data['category'] instanceof ComplaintCategory ? $data['category'] : ComplaintCategory::from($data['category']),
            subject: $data['subject'] ?? '',
            description: $data['description'] ?? '',
            priority: $data['priority'] instanceof ComplaintPriority ? $data['priority'] : ComplaintPriority::from($data['priority']),
            status: $data['status'] instanceof ComplaintStatus ? $data['status'] : ComplaintStatus::from($data['status']),
            complaintNumber: $data['complaintNumber'] ?? $data['complaint_number'] ?? null,
            trackingNumber: $data['trackingNumber'] ?? $data['tracking_number'] ?? null,
            email: $data['email'] ?? null,
            nationalId: $data['nationalId'] ?? $data['national_id'] ?? null,
            departmentId: $data['departmentId'] ?? $data['department_id'] ?? null,
            location: $data['location'] ?? null,
            latitude: $data['latitude'] ?? null,
            longitude: $data['longitude'] ?? null,
            attachments: $data['attachments'] ?? null,
            internalNotes: $data['internalNotes'] ?? $data['internal_notes'] ?? null,
            publicResponse: $data['publicResponse'] ?? $data['public_response'] ?? null,
            assignedTo: $data['assignedTo'] ?? $data['assigned_to'] ?? null,
            submittedBy: $data['submittedBy'] ?? $data['submitted_by'] ?? null,
            submittedAt: $data['submittedAt'] ?? $data['submitted_at'] ?? null,
            resolutionAt: $data['resolutionAt'] ?? $data['resolution_at'] ?? null,
            createdBy: $data['createdBy'] ?? $data['created_by'] ?? null,
            updatedBy: $data['updatedBy'] ?? $data['updated_by'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'complaint_number' => $this->complaintNumber,
            'tracking_number' => $this->trackingNumber,
            'citizen_name' => $this->citizenName,
            'phone' => $this->phone,
            'email' => $this->email,
            'national_id' => $this->nationalId,
            'category' => $this->category->value,
            'department_id' => $this->departmentId,
            'subject' => $this->subject,
            'description' => $this->description,
            'location' => $this->location,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'attachments' => $this->attachments,
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'internal_notes' => $this->internalNotes,
            'public_response' => $this->publicResponse,
            'assigned_to' => $this->assignedTo,
            'submitted_by' => $this->submittedBy,
            'submitted_at' => $this->submittedAt,
            'resolution_at' => $this->resolutionAt,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
        ];
    }
}
