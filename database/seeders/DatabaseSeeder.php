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
                IdnaMagazineSeeder::class,
                ElectronicServicesIdnaSeeder::class,
                EngineeringOfficeSeeder::class,
                TenderSeeder::class,
                ComplaintSeeder::class,
                PublicFacilitySeeder::class,
            ]);
        }
    }
}
