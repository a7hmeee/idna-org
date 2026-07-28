<?php

declare(strict_types=1);

use App\Domains\Authentication\Actions\ChangePasswordAction;
use App\Domains\Authentication\DTOs\ChangePasswordDTO;
use App\Domains\Authentication\Exceptions\AuthenticationException;
use App\Domains\Authentication\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'password' => Hash::make('Current@123'),
    ]);

    Auth::login($this->user);
});

it('changes password with valid current password', function (): void {
    $changePasswordAction = app(ChangePasswordAction::class);

    $dto = new ChangePasswordDTO(
        currentPassword: 'Current@123',
        newPassword: 'NewPass@123',
        newPasswordConfirmation: 'NewPass@123',
    );

    $changePasswordAction->execute($dto);

    $this->user->refresh();

    expect(Hash::check('NewPass@123', $this->user->password))->toBeTrue();
});

it('throws exception with incorrect current password', function (): void {
    $changePasswordAction = app(ChangePasswordAction::class);

    $dto = new ChangePasswordDTO(
        currentPassword: 'WrongCurrent!',
        newPassword: 'NewPass@123',
        newPasswordConfirmation: 'NewPass@123',
    );

    $changePasswordAction->execute($dto);
})->throws(AuthenticationException::class);

it('throws exception for unauthenticated user', function (): void {
    Auth::logout();

    $changePasswordAction = app(ChangePasswordAction::class);

    $dto = new ChangePasswordDTO(
        currentPassword: 'Current@123',
        newPassword: 'NewPass@123',
        newPasswordConfirmation: 'NewPass@123',
    );

    $changePasswordAction->execute($dto);
})->throws(AuthenticationException::class);
