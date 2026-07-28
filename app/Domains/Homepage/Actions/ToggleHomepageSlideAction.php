<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSlide;

final readonly class ToggleHomepageSlideAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id): HomepageSlide
    {
        $slide = $this->repository->toggleSlide($id);

        CacheForgetHomepageDataAction::execute();
        CacheForgetPageCarouselAction::execute($slide->page_key);

        return $slide;
    }
}
