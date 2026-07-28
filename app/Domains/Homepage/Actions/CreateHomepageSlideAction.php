<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSlideData;
use App\Domains\Homepage\Models\HomepageSlide;

final readonly class CreateHomepageSlideAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(HomepageSlideData $dto): HomepageSlide
    {
        $slide = $this->repository->createSlide($dto->toArray());

        CacheForgetHomepageDataAction::execute();

        $pageKey = $dto->pageKey ?? 'home';
        CacheForgetPageCarouselAction::execute($pageKey);

        return $slide;
    }
}
