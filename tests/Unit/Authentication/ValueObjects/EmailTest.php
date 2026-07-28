<?php

declare(strict_types=1);

use App\Domains\Authentication\Exceptions\AuthenticationException;
use App\Domains\Authentication\ValueObjects\Email;

it('creates valid email', function (): void {
    $email = Email::fromString('test@idhna.ps');

    expect($email->value())->toBe('test@idhna.ps');
});

it('normalizes email to lowercase', function (): void {
    $email = Email::fromString('TEST@IDHNA.PS');

    expect($email->value())->toBe('test@idhna.ps');
});

it('trims whitespace from email', function (): void {
    $email = Email::fromString('  test@idhna.ps  ');

    expect($email->value())->toBe('test@idhna.ps');
});

it('throws exception for invalid email', function (): void {
    Email::fromString('not-an-email');
})->throws(AuthenticationException::class);

it('throws exception for empty email', function (): void {
    Email::fromString('');
})->throws(AuthenticationException::class);

it('compares two emails correctly', function (): void {
    $email1 = Email::fromString('test@idhna.ps');
    $email2 = Email::fromString('test@idhna.ps');
    $email3 = Email::fromString('other@idhna.ps');

    expect($email1->equals($email2))->toBeTrue();
    expect($email1->equals($email3))->toBeFalse();
});
