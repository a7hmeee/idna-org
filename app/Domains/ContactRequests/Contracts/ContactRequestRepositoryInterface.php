<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Contracts;

use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Models\ContactRequest;

interface ContactRequestRepositoryInterface
{
    public function create(CreateContactRequestData $data): ContactRequest;

    public function markResolved(int $id): bool;

    public function findById(int $id): ?ContactRequest;

    public function findByTrackingNumber(string $trackingNumber): ?ContactRequest;
}
