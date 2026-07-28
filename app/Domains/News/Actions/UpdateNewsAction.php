<?php

declare(strict_types=1);

namespace App\Domains\News\Actions;

use App\Domains\News\Contracts\NewsRepositoryInterface;
use App\Domains\News\DTOs\NewsData;
use App\Domains\News\Models\NewsItem;

final readonly class UpdateNewsAction
{
    public function __construct(
        private NewsRepositoryInterface $repository,
    ) {}

    public function execute(int $id, NewsData $dto): NewsItem
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
