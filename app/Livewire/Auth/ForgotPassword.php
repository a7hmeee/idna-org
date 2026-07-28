<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Domains\Authentication\Actions\ForgotPasswordAction;
use App\Domains\Authentication\DTOs\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.auth')]
final class ForgotPassword extends Component
{
    #[Rule(['required', 'string', 'email', 'max:255', 'exists:users,email'])]
    public string $email = '';

    public ?string $statusMessage = null;
    public ?string $errorMessage = null;

    public function submit(ForgotPasswordAction $forgotPasswordAction): void
    {
        $this->validate();

        $dto = ForgotPasswordDTO::fromRequest($this->all());

        $status = $forgotPasswordAction->execute($dto);

        if ($status === Password::RESET_LINK_SENT) {
            $this->statusMessage = __('auth.messages.password_reset_link_sent');
            $this->email = '';
        } else {
            $this->errorMessage = __('auth.messages.unable_to_send_reset_link');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
