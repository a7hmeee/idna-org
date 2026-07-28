<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteHomepageSlideAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id): bool
    {
        $slide = $this->repository->findSlide($id);
        $pageKey = $slide?->page_key ?? 'home';

        if ($slide) {
            if ($slide->image_path && Storage::disk('public')->exists($slide->image_path)) {
                Storage::disk('public')->delete($slide->image_path);
            }
            if ($slide->mobile_image_path && Storage::disk('public')->exists($slide->mobile_image_path)) {
                Storage::disk('public')->delete($slide->mobile_image_path);
            }
        }

        $result = $this->repository->deleteSlide($id);

        CacheForgetHomepageDataAction::execute();
        CacheForgetPageCarouselAction::execute($pageKey);

        return $result;
    }
}