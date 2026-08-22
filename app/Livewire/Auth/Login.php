<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Domains\Authentication\Actions\LoginAction;
use App\Domains\Authentication\DTOs\LoginDTO;
use App\Domains\Authentication\Exceptions\AccountLockedException;
use App\Domains\Authentication\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.auth')]
final class Login extends Component
{
    #[Rule(['required', 'string', 'email', 'max:255'])]
    public string $email = '';

    #[Rule(['required', 'string', 'min:8'])]
    public string $password = '';

    public bool $remember = false;

    public ?string $errorMessage = null;

    public function submit(LoginAction $loginAction): void
    {
        $this->validate();

        $dto = LoginDTO::fromRequest($this->all());

        try {
            $loginAction->execute($dto);

            Session::flash('success', __('auth.messages.login_successful'));

            $this->redirectIntended(route('dashboard'), navigate: true);
        } catch (InvalidCredentialsException) {
            $this->errorMessage = __('auth.messages.invalid_credentials');
            $this->password = '';
        } catch (AccountLockedException $e) {
            $this->errorMessage = __('auth.messages.account_locked', ['minutes' => $e->minutesRemaining]);
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
