<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Announcements\Models\Announcement;
use Illuminate\Database\Seeder;

final class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        Announcement::factory()->count(15)->create();
        Announcement::factory()->count(3)->draft()->create();
        Announcement::factory()->count(2)->archived()->create();
        Announcement::factory()->count(3)->featured()->create();
        Announcement::factory()->count(2)->urgent()->create();
    }
}
