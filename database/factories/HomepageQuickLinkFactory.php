<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Homepage\Models\HomepageQuickLink;
use Illuminate\Database\Eloquent\Factories\Factory;

final class HomepageQuickLinkFactory extends Factory
{
    protected $model = HomepageQuickLink::class;

    public function definition(): array
    {
        return [
            'title' => fake()->word(),
            'description' => fake()->optional()->sentence(),
            'icon' => fake()->randomElement(['building-2', 'hard-hat', 'file-text', 'phone', 'laptop']),
            'url' => fake()->optional()->url(),
            'type' => fake()->randomElement(['internal', 'external', 'service', 'portal']),
            'is_external' => fake()->boolean(30),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }
}
