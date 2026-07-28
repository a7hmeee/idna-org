<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Complaints\Models\Complaint;
use Illuminate\Database\Seeder;

final class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        Complaint::factory()->count(5)->create();
        Complaint::factory()->count(3)->underReview()->create();
        Complaint::factory()->count(2)->resolved()->create();
        Complaint::factory()->count(2)->urgent()->create();
    }
}
