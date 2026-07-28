<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Projects\Models\Project;
use Illuminate\Database\Seeder;

final class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        Project::factory()->count(8)->create();
        Project::factory()->count(2)->draft()->create();
        Project::factory()->count(3)->featured()->create();
    }
}
