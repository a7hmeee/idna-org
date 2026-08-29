<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use App\Domains\Homepage\Contracts\HomepageRepositoryInterface;
use App\Domains\Homepage\Models\HomepageSection;

final readonly class ToggleHomepageSectionAction
{
    public function __construct(
        private HomepageRepositoryInterface $repository,
    ) {}

    public function execute(string $key): HomepageSection
    {
        $section = $this->repository->getSections()->firstWhere('key', $key);

        if (! $section) {
            throw new \RuntimeException("Section with key '{$key}' not found.");
        }

        $result = $this->repository->updateSection($key, ['is_enabled' => ! $section->is_enabled]);

        app(CacheForgetHomepageDataAction::class)->execute();

        return $result;
    }
}
