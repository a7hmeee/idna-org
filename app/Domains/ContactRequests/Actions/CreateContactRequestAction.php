<?php

declare(strict_types=1);

namespace App\Domains\ContactRequests\Actions;

use App\Domains\ContactRequests\Contracts\ContactRequestRepositoryInterface;
use App\Domains\ContactRequests\DTOs\CreateContactRequestData;
use App\Domains\ContactRequests\Models\ContactRequest;

final readonly class CreateContactRequestAction
{
    public function __construct(
        private ContactRequestRepositoryInterface $repository,
    ) {}

    public function execute(CreateContactRequestData $dto): ContactRequest
    {
        return $this->repository->create($dto);
    }
}
