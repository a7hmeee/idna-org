<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Exceptions;

final class InvalidCredentialsException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('Invalid email or password.', 401);
    }
}
