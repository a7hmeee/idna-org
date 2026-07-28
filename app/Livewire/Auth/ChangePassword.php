<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Domains\Authentication\Actions\ChangePasswordAction;
use App\Domains\Authentication\DTOs\ChangePasswordDTO;
use App\Domains\Authentication\Exceptions\AuthenticationException;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.dashboard')]
final class ChangePassword extends Component
{
    #[Rule(['required', 'string'])]
    public string $current_password = '';

    #[Rule(['required', 'string', 'min:8', 'confirmed', 'different:current_password'])]
    public string $new_password = '';

    #[Rule(['required', 'string'])]
    public string $new_password_confirmation = '';

    public ?string $errorMessage = null;

    public function submit(ChangePasswordAction $changePasswordAction): void
    {
        $this->validate();

        $dto = ChangePasswordDTO::fromRequest([
            'current_password' => $this->current_password,
            'new_password' => $this->new_password,
            'new_password_confirmation' => $this->new_password_confirmation,
        ]);

        try {
            $changePasswordAction->execute($dto);

            Session::flash('success', __('auth.messages.password_changed_successful'));

            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        } catch (AuthenticationException $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
