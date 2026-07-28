<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\DTOs\ForgotPasswordDTO;
use App\Domains\Authentication\Events\PasswordResetRequested;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class ForgotPasswordAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(ForgotPasswordDTO $dto): string
    {
        $user = $this->userRepository->findByEmail((string) $dto->email);

        if ($user === null) {
            return Password::INVALID_USER;
        }

        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => (string) $dto->email],
            ['email' => (string) $dto->email, 'token' => bcrypt($token), 'created_at' => now()],
        );

        $user->sendPasswordResetNotification($token);

        event(new PasswordResetRequested(
            userId: $user->id,
            email: (string) $dto->email,
            token: $token,
        ));

        return Password::RESET_LINK_SENT;
    }
}
