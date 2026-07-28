<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Tenders\Models\Tender;
use Illuminate\Database\Seeder;

final class TenderSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        Tender::factory()->count(5)->create();
        Tender::factory()->count(2)->draft()->create();
        Tender::factory()->count(2)->featured()->create();
    }
}
