<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Contracts;

use App\Domains\Authentication\Models\LoginActivity;
use Illuminate\Database\Eloquent\Collection;

interface LoginActivityRepositoryInterface
{
    public function create(array $data): LoginActivity;

    public function getLatestForUser(int $userId, int $limit = 10): Collection;

    public function getRecentFailedAttempts(string $email, int $minutes = 15): int;

    public function getLastLogin(int $userId): ?LoginActivity;
}
