<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use Illuminate\Support\Facades\Cache;

final readonly class CacheForgetPageCarouselAction
{
    public static function execute(string $pageKey): void
    {
        Cache::forget('page-carousel:'.$pageKey);
    }
}
