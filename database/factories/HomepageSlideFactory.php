<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Homepage\Models\HomepageSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

final class HomepageSlideFactory extends Factory
{
    protected $model = HomepageSlide::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'subtitle' => fake()->optional()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'badge_text' => fake()->optional()->word(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }

    public function withDates(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);
    }
}
