<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\News\Models\NewsItem;
use Illuminate\Database\Seeder;

final class NewsSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        NewsItem::factory()->count(10)->create();
        NewsItem::factory()->count(3)->draft()->create();
        NewsItem::factory()->count(2)->archived()->create();
        NewsItem::factory()->count(3)->featured()->create();
    }
}
