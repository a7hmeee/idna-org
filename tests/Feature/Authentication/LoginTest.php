<?php

declare(strict_types=1);

use App\Domains\Authentication\Actions\LoginAction;
use App\Domains\Authentication\DTOs\LoginDTO;
use App\Domains\Authentication\Exceptions\AccountLockedException;
use App\Domains\Authentication\Exceptions\InvalidCredentialsException;
use App\Domains\Authentication\Models\User;
use App\Domains\Authentication\ValueObjects\Email;
use App\Domains\Authentication\ValueObjects\IpAddress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    Event::fake();

    $this->user = User::factory()->create([
        'email' => 'admin@idhna.ps',
        'password' => Hash::make('Admin@12345'),
        'login_attempts' => 0,
    ]);
});

it('can login with valid credentials', function (): void {
    $loginAction = app(LoginAction::class);

    $dto = new LoginDTO(
        email: Email::fromString('admin@idhna.ps'),
        password: 'Admin@12345',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test',
        rememberMe: false,
    );

    $result = $loginAction->execute($dto);

    expect($result)->toBeInstanceOf(User::class);
    expect($result->id)->toBe($this->user->id);
    expect(auth()->check())->toBeTrue();
});

it('throws exception with invalid password', function (): void {
    $loginAction = app(LoginAction::class);

    $dto = new LoginDTO(
        email: Email::fromString('admin@idhna.ps'),
        password: 'WrongPassword123!',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test',
        rememberMe: false,
    );

    $loginAction->execute($dto);
})->throws(InvalidCredentialsException::class);

it('throws exception with non-existent email', function (): void {
    $loginAction = app(LoginAction::class);

    $dto = new LoginDTO(
        email: Email::fromString('nonexistent@idhna.ps'),
        password: 'Admin@12345',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test',
        rememberMe: false,
    );

    $loginAction->execute($dto);
})->throws(InvalidCredentialsException::class);

it('locks account after 5 failed attempts', function (): void {
    $loginAction = app(LoginAction::class);

    for ($i = 0; $i < 5; $i++) {
        try {
            $loginAction->execute(new LoginDTO(
                email: Email::fromString('admin@idhna.ps'),
                password: 'WrongPassword123!',
                ipAddress: IpAddress::fromString('127.0.0.1'),
                userAgent: 'Test',
            ));
        } catch (InvalidCredentialsException) {
            // Expected
        }
    }

    $loginAction->execute(new LoginDTO(
        email: Email::fromString('admin@idhna.ps'),
        password: 'WrongPassword123!',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test',
    ));
})->throws(AccountLockedException::class);

it('resets login attempts on successful login', function (): void {
    $loginAction = app(LoginAction::class);

    // Fail once
    try {
        $loginAction->execute(new LoginDTO(
            email: Email::fromString('admin@idhna.ps'),
            password: 'WrongPassword123!',
            ipAddress: IpAddress::fromString('127.0.0.1'),
            userAgent: 'Test',
        ));
    } catch (InvalidCredentialsException) {
        // Expected
    }

    // Login successfully
    $loginAction->execute(new LoginDTO(
        email: Email::fromString('admin@idhna.ps'),
        password: 'Admin@12345',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test',
    ));

    $this->user->refresh();
    expect($this->user->login_attempts)->toBe(0);
});

it('logs login activity', function (): void {
    $loginAction = app(LoginAction::class);

    $loginAction->execute(new LoginDTO(
        email: Email::fromString('admin@idhna.ps'),
        password: 'Admin@12345',
        ipAddress: IpAddress::fromString('127.0.0.1'),
        userAgent: 'Test Browser',
        rememberMe: false,
    ));

    Event::assertDispatched(\App\Domains\Authentication\Events\UserLoggedIn::class);
});
