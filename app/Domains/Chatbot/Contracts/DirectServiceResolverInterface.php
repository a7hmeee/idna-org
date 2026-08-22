<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ResolvedServiceData;

interface DirectServiceResolverInterface
{
    public function resolve(string $normalizedMessage): ?ResolvedServiceData;
}
