<?php

declare(strict_types=1);

namespace App\Domains\Authentication\Exceptions;

use RuntimeException;

class AuthenticationException extends RuntimeException
{
    public function __construct(string $message = 'Authentication failed.', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
