<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Services;

use App\Domains\Authentication\Actions\ChangePasswordAction;
use App\Domains\Authentication\Actions\ForgotPasswordAction;
use App\Domains\Authentication\Actions\LoginAction;
use App\Domains\Authentication\Actions\LogoutAction;
use App\Domains\Authentication\Actions\ResetPasswordAction;
use App\Domains\Authentication\DTOs\ChangePasswordDTO;
use App\Domains\Authentication\DTOs\ForgotPasswordDTO;
use App\Domains\Authentication\DTOs\LoginDTO;
use App\Domains\Authentication\DTOs\ResetPasswordDTO;
use App\Domains\Authentication\Models\User;

final readonly class AuthenticationService
{
    public function __construct(
        private LoginAction $loginAction,
        private LogoutAction $logoutAction,
        private ForgotPasswordAction $forgotPasswordAction,
        private ResetPasswordAction $resetPasswordAction,
        private ChangePasswordAction $changePasswordAction,
    ) {}

    public function login(LoginDTO $dto): User
    {
        return $this->loginAction->execute($dto);
    }

    public function logout(): void
    {
        $this->logoutAction->execute();
    }

    public function sendResetLink(ForgotPasswordDTO $dto): string
    {
        return $this->forgotPasswordAction->execute($dto);
    }

    public function resetPassword(ResetPasswordDTO $dto): string
    {
        return $this->resetPasswordAction->execute($dto);
    }

    public function changePassword(ChangePasswordDTO $dto): void
    {
        $this->changePasswordAction->execute($dto);
    }
}
