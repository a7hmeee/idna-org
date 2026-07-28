<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SuperAdminSeeder::class,
        ]);

        $this->call([
            PageCarouselSeeder::class,
        ]);

        if (! app()->environment('production')) {
            $this->call([
                MunicipalityDemoSeeder::class,
                DepartmentSeeder::class,
                ElectronicServicesSeeder::class,
                EngineeringOfficeSeeder::class,
                CouncilDecisionSeeder::class,
                CouncilMemberSeeder::class,
                HomepageSeeder::class,
                JobSeeder::class,
                AnnouncementSeeder::class,
                NewsSeeder::class,
                ProjectSeeder::class,
                TenderSeeder::class,
                ComplaintSeeder::class,
                WaterScheduleSeeder::class,
                PublicFacilitySeeder::class,
            ]);
        }
    }
}
