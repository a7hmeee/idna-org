<?php

declare(strict_types=1);

use App\Domains\Authentication\Exceptions\AuthenticationException;
use App\Domains\Authentication\ValueObjects\Password;

it('creates password from plain text', function (): void {
    $password = Password::fromPlainText('Test@1234');

    expect($password->hash())->not->toBe('Test@1234');
    expect($password->verify('Test@1234'))->toBeTrue();
});

it('throws exception for short password', function (): void {
    Password::fromPlainText('Sh@1');
})->throws(AuthenticationException::class);

it('throws exception for password without uppercase', function (): void {
    Password::fromPlainText('lowercase@123');
})->throws(AuthenticationException::class);

it('throws exception for password without lowercase', function (): void {
    Password::fromPlainText('UPPERCASE@123');
})->throws(AuthenticationException::class);

it('throws exception for password without digit', function (): void {
    Password::fromPlainText('NoDigits@!');
})->throws(AuthenticationException::class);

it('throws exception for password without special character', function (): void {
    Password::fromPlainText('NoSpecialChar1');
})->throws(AuthenticationException::class);

it('verifies password correctly', function (): void {
    $password = Password::fromPlainText('Valid@123');

    expect($password->verify('Valid@123'))->toBeTrue();
    expect($password->verify('WrongPass1@'))->toBeFalse();
});

it('creates password from existing hash', function (): void {
    $hash = bcrypt('Test@1234');
    $password = Password::fromHash($hash);

    expect($password->hash())->toBe($hash);
    expect($password->verify('Test@1234'))->toBeTrue();
});
