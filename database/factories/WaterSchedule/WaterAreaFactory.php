<?php

declare(strict_types=1);

namespace Database\Factories\WaterSchedule;

use App\Domains\WaterSchedule\Models\WaterArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class WaterAreaFactory extends Factory
{
    protected $model = WaterArea::class;

    public function definition(): array
    {
        $name = fake()->unique()->city();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'display_order' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
