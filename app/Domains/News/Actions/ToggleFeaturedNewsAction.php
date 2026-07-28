<?php

declare(strict_types=1);

namespace App\Domains\News\Actions;

use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\Models\NewsItem;

final readonly class ToggleFeaturedNewsAction
{
    public function __construct(
        private NewsRepositoryInterface $repository,
    ) {}

    public function execute(int $id): NewsItem
    {
        return $this->repository->toggleFeatured($id);
    }
}
