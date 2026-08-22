<?php

declare(strict_types=1);

namespace App\Domains\Chatbot\Contracts;

use App\Domains\Chatbot\DTOs\ResolvedServiceData;

interface EntityResolverInterface
{
    public function resolve(string $normalizedMessage, ?string $currentServiceName = null): ?ResolvedServiceData;

    public function resolveMultiple(string $normalizedMessage): array;
}
