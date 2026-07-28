<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Domains\Authentication\Actions\ResetPasswordAction;
use App\Domains\Authentication\DTOs\ResetPasswordDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.auth')]
final class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';

    #[Rule(['required', 'string', 'min:8', 'confirmed'])]
    public string $password = '';

    #[Rule(['required', 'string'])]
    public string $password_confirmation = '';

    public ?string $errorMessage = null;

    public function mount(string $token, string $email): void
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function submit(ResetPasswordAction $resetPasswordAction): void
    {
        $this->validate();

        $dto = ResetPasswordDTO::fromRequest([
            'token' => $this->token,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
        ]);

        $status = $resetPasswordAction->execute($dto);

        if ($status === Password::PASSWORD_RESET) {
            Session::flash('success', __('auth.messages.password_reset_successful'));

            $this->redirectRoute('login', navigate: true);
        } else {
            $this->errorMessage = __('auth.messages.invalid_reset_token');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
