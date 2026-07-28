<?php

declare(strict_types=1);

namespace App\Domains\Authentication\DTOs;

final readonly class ChangePasswordDTO
{
    public function __construct(
        public string $currentPassword,
        public string $newPassword,
        public string $newPasswordConfirmation,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            currentPassword: $validated['current_password'],
            newPassword: $validated['new_password'],
            newPasswordConfirmation: $validated['new_password_confirmation'],
        );
    }
}
