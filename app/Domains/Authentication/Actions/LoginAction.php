<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\DTOs\LoginDTO;
use App\Domains\Authentication\Events\LoginAttemptFailed;
use App\Domains\Authentication\Events\UserLoggedIn;
use App\Domains\Authentication\Exceptions\AccountLockedException;
use App\Domains\Authentication\Exceptions\InvalidCredentialsException;
use App\Domains\Authentication\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

final class LoginAction
{
    private const int MAX_ATTEMPTS = 5;

    private const int LOCKOUT_MINUTES = 15;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(LoginDTO $dto): User
    {
        $user = $this->userRepository->findByEmail((string) $dto->email);

        if ($user === null) {
            event(new LoginAttemptFailed(
                email: (string) $dto->email,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                reason: 'account_not_found',
            ));

            throw new InvalidCredentialsException;
        }

        if ($user->isLocked()) {
            $remaining = $this->userRepository->getLockoutTimeRemaining($user->id);

            event(new LoginAttemptFailed(
                email: (string) $dto->email,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                reason: 'account_locked',
                userId: $user->id,
            ));

            throw new AccountLockedException(max(1, $remaining));
        }

        if (! Auth::attempt([
            'email' => (string) $dto->email,
            'password' => $dto->password,
        ], $dto->rememberMe)) {
            $attempts = $this->userRepository->incrementLoginAttempts($user->id);
            $remainingAttempts = self::MAX_ATTEMPTS - $attempts;

            $reason = $remainingAttempts <= 0
                ? 'max_attempts_reached'
                : 'invalid_password';

            event(new LoginAttemptFailed(
                email: (string) $dto->email,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                reason: $reason,
                userId: $user->id,
            ));

            throw new InvalidCredentialsException;
        }

        DB::transaction(function () use ($user, $dto): void {
            $this->userRepository->resetLoginAttempts($user->id);

            session()->regenerate();

            event(new UserLoggedIn(
                userId: $user->id,
                ipAddress: $dto->ipAddress,
                userAgent: $dto->userAgent,
                sessionId: Session::getId(),
                rememberMe: $dto->rememberMe,
            ));
        });

        return $user;
    }
}
