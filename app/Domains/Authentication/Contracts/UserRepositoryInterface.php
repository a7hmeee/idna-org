<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Contracts;

use App\Domains\Authentication\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function updateLastLogin(int $userId): void;

    public function updatePassword(int $userId, string $hashedPassword): void;

    public function getActiveSessions(int $userId): Collection;

    public function incrementLoginAttempts(int $userId): int;

    public function resetLoginAttempts(int $userId): void;

    public function isAccountLocked(int $userId): bool;

    public function getLockoutTimeRemaining(int $userId): int;
}
