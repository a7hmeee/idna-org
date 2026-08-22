<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

use Illuminate\Support\Facades\Cache;

final class CacheForgetHomepageDataAction
{
    public static function execute(?string $section = null): void
    {
        Cache::forget('homepage.public.data');

        if ($section === 'council-decisions') {
            Cache::forget('public.council-decisions.years');
            Cache::forget('public.council-decisions.statistics');
        }
    }
}
