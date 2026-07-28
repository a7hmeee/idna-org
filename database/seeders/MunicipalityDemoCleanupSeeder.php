<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Municipality\Models\Municipality;
use App\Domains\SharedKernel\Models\BusinessHour;
use App\Domains\SharedKernel\Models\EmergencyContact;
use App\Domains\SharedKernel\Models\Media;
use Illuminate\Database\Seeder;

final class MunicipalityDemoCleanupSeeder extends Seeder
{
    private const DEMO_CODE = 'IDNA-001';

    public function run(): void
    {
        $municipality = Municipality::where('municipality_code', self::DEMO_CODE)->first();

        if ($municipality === null) {
            $this->command?->warn('No demo municipality found to clean up.');

            return;
        }

        $municipalityClass = Municipality::class;

        Media::where('mediable_id', $municipality->id)
            ->where('mediable_type', $municipalityClass)
            ->delete();

        BusinessHour::where('hourable_id', $municipality->id)
            ->where('hourable_type', $municipalityClass)
            ->delete();

        EmergencyContact::where('contactable_id', $municipality->id)
            ->where('contactable_type', $municipalityClass)
            ->delete();

        $municipality->delete();
    }
}
