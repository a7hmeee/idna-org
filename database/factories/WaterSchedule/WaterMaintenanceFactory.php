<?php

declare(strict_types=1);

namespace Database\Factories\WaterSchedule;

use App\Domains\WaterSchedule\Models\WaterMaintenance;
use Illuminate\Database\Eloquent\Factories\Factory;

final class WaterMaintenanceFactory extends Factory
{
    protected $model = WaterMaintenance::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'starts_at' => now()->subHour(),
            'ends_at' => now()->addHours(3),
            'status' => 'active',
            'affected_areas' => [fake()->city(), fake()->city()],
            'is_public' => true,
        ];
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(3),
        ]);
    }

    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(2),
            'ends_at' => now()->subDays(2)->addHours(3),
            'status' => 'completed',
        ]);
    }
}
