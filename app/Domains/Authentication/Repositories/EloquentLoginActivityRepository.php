<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Repositories;

use App\Domains\Authentication\Contracts\LoginActivityRepositoryInterface;
use App\Domains\Authentication\Models\LoginActivity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

final class EloquentLoginActivityRepository implements LoginActivityRepositoryInterface
{
    public function create(array $data): LoginActivity
    {
        return LoginActivity::create($data);
    }

    public function getLatestForUser(int $userId, int $limit = 10): Collection
    {
        return LoginActivity::where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentFailedAttempts(string $email, int $minutes = 15): int
    {
        return LoginActivity::where('event_type', LoginActivity::EVENT_FAILED)
            ->where('created_at', '>=', Carbon::now()->subMinutes($minutes))
            ->whereHas('user', fn ($query) => $query->where('email', $email))
            ->count();
    }

    public function getLastLogin(int $userId): ?LoginActivity
    {
        return LoginActivity::where('user_id', $userId)
            ->where('event_type', LoginActivity::EVENT_LOGIN)
            ->where('successful', true)
            ->latest()
            ->first();
    }
}
