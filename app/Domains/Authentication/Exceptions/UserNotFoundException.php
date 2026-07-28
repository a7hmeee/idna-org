<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Exceptions;

final class UserNotFoundException extends AuthenticationException
{
    public function __construct()
    {
        parent::__construct('No account found with the provided credentials.', 404);
    }
}
