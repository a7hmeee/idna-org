<?php

declare(strict_types=1);

namespace App\Domains\UserManagement\DTOs;

final readonly class CreateUserDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
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
            password: $validated['password'],
            phone: $validated['phone'] ?? null,
            departmentId: $validated['department_id'] ?? null,
            role: $validated['role'] ?? null,
            status: $validated['status'] ?? 'active',
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'phone' => $this->phone,
            'department_id' => $this->departmentId,
            'status' => $this->status,
            'email_verified_at' => now(),
        ];
    }
}
