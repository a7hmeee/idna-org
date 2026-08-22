<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Actions;

use App\Domains\Authentication\Contracts\UserRepositoryInterface;
use App\Domains\Authentication\DTOs\ResetPasswordDTO;
use App\Domains\Authentication\Events\PasswordResetCompleted;
use App\Domains\Authentication\ValueObjects\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password as PasswordFacade;

final class ResetPasswordAction
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function execute(ResetPasswordDTO $dto): string
    {
        $user = $this->userRepository->findByEmail((string) $dto->email);

        if ($user === null) {
            return PasswordFacade::INVALID_USER;
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', (string) $dto->email)
            ->first();

        if ($record === null || ! password_verify($dto->token, $record->token)) {
            return PasswordFacade::INVALID_TOKEN;
        }

        if ($record->created_at->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', (string) $dto->email)->delete();

            return PasswordFacade::INVALID_TOKEN;
        }

        $hashedPassword = Password::fromPlainText($dto->password);

        DB::transaction(function () use ($user, $hashedPassword, $dto): void {
            $this->userRepository->updatePassword($user->id, (string) $hashedPassword);

            DB::table('password_reset_tokens')
                ->where('email', (string) $dto->email)
                ->delete();

            event(new PasswordResetCompleted(userId: $user->id));
        });

        return PasswordFacade::PASSWORD_RESET;
    }
}
