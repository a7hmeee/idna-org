<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\DTOs;

final readonly class UpdateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone = null,
        public ?int $departmentId = null,
        public ?string $role = null,
        public string $status = 'active',
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            email: $validated['email'],
            phone: $validated['phone'] ?? null,
            departmentId: $validated['department_id'] ?? null,
            role: $validated['role'] ?? null,
            status: $validated['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'department_id' => $this->departmentId,
            'status' => $this->status,
        ], fn ($v) => $v !== null);
    }
}
