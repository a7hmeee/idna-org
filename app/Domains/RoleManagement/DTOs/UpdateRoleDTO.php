<?php

declare(strict_types=1);

namespace App\Domains\RoleManagement\DTOs;

final readonly class UpdateRoleDTO
{
    public function __construct(
        public string $name,
        public array $permissions = [],
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            permissions: $validated['permissions'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'permissions' => $this->permissions,
        ];
    }
}
