<?php

declare(strict_types=1);

namespace Database\Factories\WaterSchedule;

use App\Domains\WaterSchedule\Enums\WaterScheduleStatus;
use App\Domains\WaterSchedule\Models\WaterArea;
use App\Domains\WaterSchedule\Models\WaterSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

final class WaterScheduleFactory extends Factory
{
    protected $model = WaterSchedule::class;

    public function definition(): array
    {
        return [
            'water_area_id' => WaterArea::factory(),
            'schedule_date' => fake()->date(),
            'start_time' => fake()->time('H:i'),
            'end_time' => fake()->time('H:i'),
            'status' => fake()->randomElement(WaterScheduleStatus::cases())->value,
            'notes' => fake()->sentence(),
            'display_order' => 0,
            'is_public' => true,
        ];
    }

    public function forDate(string $date): static
    {
        return $this->state(fn (array $attributes) => [
            'schedule_date' => $date,
        ]);
    }
}
