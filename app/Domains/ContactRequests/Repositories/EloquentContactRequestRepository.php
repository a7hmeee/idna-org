<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Repositories;

use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Enums\ContactRequestStatus;
use App\Domains\ContactRequests\Models\ContactRequest;

final readonly class EloquentContactRequestRepository implements ContactRequestRepositoryInterface
{
    public function create(CreateContactRequestData $data): ContactRequest
    {
        return ContactRequest::create($data->toArray());
    }

    public function markResolved(int $id): bool
    {
        return (bool) ContactRequest::where('id', $id)->update([
            'status' => ContactRequestStatus::Resolved,
            'resolved_at' => now(),
        ]);
    }

    public function findById(int $id): ?ContactRequest
    {
        return ContactRequest::find($id);
    }

    public function findByTrackingNumber(string $trackingNumber): ?ContactRequest
    {
        return ContactRequest::where('tracking_number', $trackingNumber)->first();
    }
}
