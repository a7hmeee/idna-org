<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\DTOs\HomepageSlideData;
use App\Domains\Homepage\Models\HomepageSlide;
use Illuminate\Support\Facades\Storage;

final readonly class UpdateHomepageSlideAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(int $id, HomepageSlideData $dto): HomepageSlide
    {
        $slide = $this->repository->findSlide($id);

        if (!$slide) {
            throw new \RuntimeException("Slide {$id} not found");
        }

        $data = $dto->toArray();

        if ($dto->imagePath && $slide->image_path) {
            Storage::disk('public')->delete($slide->image_path);
        }

        if ($dto->mobileImagePath && $slide->mobile_image_path) {
            Storage::disk('public')->delete($slide->mobile_image_path);
        }

        $slide = $this->repository->updateSlide($id, $data);

        CacheForgetHomepageDataAction::execute();

        $pageKey = $dto->pageKey ?? $slide->page_key ?? 'home';
        CacheForgetPageCarouselAction::execute($pageKey);

        return $slide;
    }
}