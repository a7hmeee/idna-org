<?php

declare(strict_types=1);

namespace App\Domains\EngineeringOffices\DTOs;

final readonly class EngineeringOfficeData
{
    public function __construct(
        public ?int $id = null,
        public string $officeName = '',
        public ?string $slug = null,
        public ?string $engineerName = null,
        public ?string $licenseNumber = null,
        public ?string $phone = null,
        public ?string $mobile = null,
        public ?string $email = null,
        public ?string $address = null,
        public ?array $specializations = null,
        public string $approvalStatus = 'approved',
        public string $status = 'active',
        public ?string $notes = null,
        public bool $isPublic = false,
        public int $sortOrder = 0,
        public ?int $createdBy = null,
        public ?int $updatedBy = null,
        public ?string $approvedAt = null,
        public ?string $suspendedAt = null,
        public ?string $expiresAt = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            id: isset($validated['id']) ? (int) $validated['id'] : null,
            officeName: $validated['office_name'],
            slug: $validated['slug'] ?? null,
            engineerName: $validated['engineer_name'] ?? null,
            licenseNumber: $validated['license_number'] ?? null,
            phone: $validated['phone'] ?? null,
            mobile: $validated['mobile'] ?? null,
            email: $validated['email'] ?? null,
            address: $validated['address'] ?? null,
            specializations: $validated['specializations'] ?? null,
            approvalStatus: $validated['approval_status'] ?? 'approved',
            status: $validated['status'] ?? 'active',
            notes: $validated['notes'] ?? null,
            isPublic: (bool) ($validated['is_public'] ?? false),
            sortOrder: (int) ($validated['sort_order'] ?? 0),
            createdBy: isset($validated['created_by']) ? (int) $validated['created_by'] : null,
            updatedBy: isset($validated['updated_by']) ? (int) $validated['updated_by'] : null,
            approvedAt: $validated['approved_at'] ?? null,
            suspendedAt: $validated['suspended_at'] ?? null,
            expiresAt: $validated['expires_at'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'office_name' => $this->officeName,
            'slug' => $this->slug,
            'engineer_name' => $this->engineerName,
            'license_number' => $this->licenseNumber,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'address' => $this->address,
            'specializations' => $this->specializations,
            'approval_status' => $this->approvalStatus,
            'status' => $this->status,
            'notes' => $this->notes,
            'is_public' => $this->isPublic,
            'sort_order' => $this->sortOrder,
            'created_by' => $this->createdBy,
            'updated_by' => $this->updatedBy,
            'approved_at' => $this->approvedAt,
            'suspended_at' => $this->suspendedAt,
            'expires_at' => $this->expiresAt,
        ];
    }
}
