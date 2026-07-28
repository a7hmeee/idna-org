<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterMaintenance;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Illuminate\Database\Seeder;

final class WaterScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $user = \App\Domains\Authentication\Models\User::first();

        // ==========================================
        // Water Areas
        // ==========================================
        $areas = [
            ['name' => 'حي البلد', 'description' => 'المنطقة المركزية القديمة', 'display_order' => 1],
            ['name' => 'حي الشرقية', 'description' => 'الجهة الشرقية من البلدة', 'display_order' => 2],
            ['name' => 'حي الغربية', 'description' => 'الجهة الغربية من البلدة', 'display_order' => 3],
            ['name' => 'حي الشمالية', 'description' => 'الجهة الشمالية', 'display_order' => 4],
            ['name' => 'حي الجنوبية', 'description' => 'الجهة الجنوبية', 'display_order' => 5],
            ['name' => 'شارع القدس', 'description' => 'شارع القدس الرئيسي', 'display_order' => 6],
            ['name' => 'شارع الخليل', 'description' => 'شارع الخليل والمحيط', 'display_order' => 7],
            ['name' => 'منطقة الصناعية', 'description' => 'المنطقة الصناعية', 'display_order' => 8],
        ];

        foreach ($areas as $area) {
            $slug = \Illuminate\Support\Str::slug($area['name']);
            WaterArea::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $area['name'],
                    'slug' => $slug,
                    'description' => $area['description'],
                    'display_order' => $area['display_order'],
                    'is_active' => true,
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]
            );
        }

        // ==========================================
        // Water Schedules - Today
        // ==========================================
        $today = now()->toDateString();
        $todaySchedules = [
            ['area_name' => 'حي البلد', 'start' => '08:00', 'end' => '16:00', 'status' => WaterScheduleStatus::Available, 'notes' => 'الضخ منتظم'],
            ['area_name' => 'حي الشرقية', 'start' => '08:30', 'end' => '15:30', 'status' => WaterScheduleStatus::Available, 'notes' => null],
            ['area_name' => 'حي الغربية', 'start' => '09:00', 'end' => '17:00', 'status' => WaterScheduleStatus::LowPressure, 'notes' => 'قد يكون الضغط منخفضاً بعض الشيء'],
            ['area_name' => 'حي الشمالية', 'start' => '10:00', 'end' => '18:00', 'status' => WaterScheduleStatus::Available, 'notes' => null],
            ['area_name' => 'حي الجنوبية', 'start' => '06:00', 'end' => '14:00', 'status' => WaterScheduleStatus::Available, 'notes' => null],
            ['area_name' => 'شارع القدس', 'start' => '07:00', 'end' => '15:00', 'status' => WaterScheduleStatus::Available, 'notes' => null],
            ['area_name' => 'شارع الخليل', 'start' => '09:00', 'end' => '17:00', 'status' => WaterScheduleStatus::Maintenance, 'notes' => 'صيانة مبرمجة للخط الرئيسي'],
            ['area_name' => 'منطقة الصناعية', 'start' => '08:00', 'end' => '16:00', 'status' => WaterScheduleStatus::Available, 'notes' => null],
        ];

        foreach ($todaySchedules as $schedule) {
            $area = WaterArea::where('name', $schedule['area_name'])->first();
            if ($area) {
                WaterSchedule::firstOrCreate(
                    ['water_area_id' => $area->id, 'schedule_date' => $today],
                    [
                        'water_area_id' => $area->id,
                        'schedule_date' => $today,
                        'start_time' => $schedule['start'],
                        'end_time' => $schedule['end'],
                        'status' => $schedule['status']->value,
                        'notes' => $schedule['notes'],
                        'display_order' => $area->display_order,
                        'is_public' => true,
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]
                );
            }
        }

        // ==========================================
        // Water Schedules - Yesterday (for copy test)
        // ==========================================
        $yesterday = now()->subDay()->toDateString();
        $yesterdaySchedules = [
            ['area_name' => 'حي البلد', 'start' => '08:00', 'end' => '16:00'],
            ['area_name' => 'حي الشرقية', 'start' => '08:30', 'end' => '15:30'],
            ['area_name' => 'حي الغربية', 'start' => '09:00', 'end' => '17:00'],
            ['area_name' => 'حي الشمالية', 'start' => '10:00', 'end' => '18:00'],
            ['area_name' => 'حي الجنوبية', 'start' => '06:00', 'end' => '14:00'],
            ['area_name' => 'شارع القدس', 'start' => '07:00', 'end' => '15:00'],
            ['area_name' => 'شارع الخليل', 'start' => '09:00', 'end' => '17:00'],
            ['area_name' => 'منطقة الصناعية', 'start' => '08:00', 'end' => '16:00'],
        ];

        foreach ($yesterdaySchedules as $schedule) {
            $area = WaterArea::where('name', $schedule['area_name'])->first();
            if ($area) {
                WaterSchedule::firstOrCreate(
                    ['water_area_id' => $area->id, 'schedule_date' => $yesterday],
                    [
                        'water_area_id' => $area->id,
                        'schedule_date' => $yesterday,
                        'start_time' => $schedule['start'],
                        'end_time' => $schedule['end'],
                        'status' => WaterScheduleStatus::Available->value,
                        'notes' => null,
                        'display_order' => $area->display_order,
                        'is_public' => true,
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]
                );
            }
        }

        // ==========================================
        // Active Maintenance
        // ==========================================
        WaterMaintenance::firstOrCreate(
            ['title' => 'صيانة على الخط الرئيسي'],
            [
                'title' => 'صيانة على الخط الرئيسي',
                'description' => 'تحديث وصيانة شبكة المياه الرئيسية في المنطقة الغربية. قد ينخفض ضخ المياه مؤقتاً.',
                'starts_at' => now()->subHour(),
                'ends_at' => now()->addHours(5),
                'status' => 'active',
                'affected_areas' => ['حي الغربية', 'شارع الخليل'],
                'is_public' => true,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]
        );

        WaterMaintenance::firstOrCreate(
            ['title' => 'صيانة دورية - محطة الضخ الشمالية'],
            [
                'title' => 'صيانة دورية - محطة الضخ الشمالية',
                'description' => 'صيانة دورية لمحطة الضخ.',
                'starts_at' => now()->addDays(3)->setHour(8),
                'ends_at' => now()->addDays(3)->setHour(17),
                'status' => 'active',
                'affected_areas' => ['حي الشمالية'],
                'is_public' => true,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]
        );

        $this->command?->info('✅ Water Schedule seeded successfully.');
    }
}
