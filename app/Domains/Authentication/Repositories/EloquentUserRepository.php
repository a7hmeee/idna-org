<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Repositories;

use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updateLastLogin(int $userId): void
    {
        User::withoutTimestamps(function () use ($userId): void {
            User::where('id', $userId)->update([
                'last_login_at' => Carbon::now(),
                'last_login_ip' => request()->ip(),
            ]);
        });
    }

    public function updatePassword(int $userId, string $hashedPassword): void
    {
        User::where('id', $userId)->update([
            'password' => $hashedPassword,
        ]);
    }

    public function getActiveSessions(int $userId): Collection
    {
        return DB::table('sessions')
            ->where('user_id', $userId)
            ->get();
    }

    public function incrementLoginAttempts(int $userId): int
    {
        $attempts = (int) User::where('id', $userId)->value('login_attempts');
        $attempts = $attempts + 1;

        $updateData = ['login_attempts' => $attempts];

        if ($attempts >= 5) {
            $updateData['locked_until'] = Carbon::now()->addMinutes(15);
        }

        User::withoutTimestamps(function () use ($userId, $updateData): void {
            User::where('id', $userId)->update($updateData);
        });

        return $attempts;
    }

    public function resetLoginAttempts(int $userId): void
    {
        User::withoutTimestamps(function () use ($userId): void {
            User::where('id', $userId)->update([
                'login_attempts' => 0,
                'locked_until' => null,
            ]);
        });
    }

    public function isAccountLocked(int $userId): bool
    {
        $lockedUntil = User::where('id', $userId)->value('locked_until');

        if ($lockedUntil === null) {
            return false;
        }

        return Carbon::parse($lockedUntil)->isFuture();
    }

    public function getLockoutTimeRemaining(int $userId): int
    {
        $lockedUntil = User::where('id', $userId)->value('locked_until');

        if ($lockedUntil === null) {
            return 0;
        }

        return (int) Carbon::now()->diffInMinutes(Carbon::parse($lockedUntil), false);
    }
}
