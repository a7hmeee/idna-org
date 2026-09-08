<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Repositories;

use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Enums\ContactRequestStatus;
use App\Domains\ContactRequests\Models\ContactRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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

    public function paginateDashboard(?string $search = null, ?string $status = null): LengthAwarePaginator
    {
        $query = ContactRequest::query();

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('tracking_number', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function markAsRead(int $id): bool
    {
        return (bool) ContactRequest::where('id', $id)->whereNull('resolved_at')->update([
            'resolved_at' => now(),
        ]);
    }
}
