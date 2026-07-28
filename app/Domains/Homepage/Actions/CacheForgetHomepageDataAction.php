<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Actions;

final class CacheForgetHomepageDataAction
{
    public static function execute(?string $section = null): void
    {
        \Illuminate\Support\Facades\Cache::forget('homepage.public.data');

        if ($section === 'council-decisions') {
            \Illuminate\Support\Facades\Cache::forget('public.council-decisions.years');
            \Illuminate\Support\Facades\Cache::forget('public.council-decisions.statistics');
        }
    }
}
