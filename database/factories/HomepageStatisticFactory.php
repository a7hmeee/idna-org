<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Homepage\Models\HomepageStatistic;
use Illuminate\Database\Eloquent\Factories\Factory;

final class HomepageStatisticFactory extends Factory
{
    protected $model = HomepageStatistic::class;

    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'value' => (string) fake()->numberBetween(100, 100000),
            'suffix' => fake()->optional()->randomElement(['نسمة', 'مشروع', 'خدمة', 'مكتب']),
            'icon' => fake()->randomElement(['users', 'folder-kanban', 'laptop', 'hard-hat']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
