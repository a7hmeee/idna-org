<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageQuickLinkData;
use App\Domains\Homepage\Models\HomepageQuickLink;

final readonly class UpdateHomepageQuickLinkAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id, HomepageQuickLinkData $dto): HomepageQuickLink
    {
        $link = $this->repository->updateQuickLink($id, $dto->toArray());

        CacheForgetHomepageDataAction::execute();

        return $link;
    }
}
