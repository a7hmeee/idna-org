<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\DTOs\ChangePasswordDTO;
use App\Domains\Authentication\Events\PasswordChanged;
use App\Domains\Authentication\Exceptions\AuthenticationException;
use App\Domains\Authentication\ValueObjects\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

final class ChangePasswordAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(ChangePasswordDTO $dto): void
    {
        $user = Auth::user();

        if ($user === null) {
            throw new AuthenticationException('User must be authenticated to change password.');
        }

        if (! Hash::check($dto->currentPassword, $user->password)) {
            throw new AuthenticationException('Current password is incorrect.');
        }

        $hashedPassword = Password::fromPlainText($dto->newPassword);

        DB::transaction(function () use ($user, $hashedPassword): void {
            $this->userRepository->updatePassword($user->id, (string) $hashedPassword);

            event(new PasswordChanged(
                userId: $user->id,
                ipAddress: request()->ip(),
            ));
        });
    }
}
