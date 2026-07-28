<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\ElectronicServices\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceCategory>
 */
final class ServiceCategoryFactory extends Factory
{
    protected $model = ServiceCategory::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
            'icon' => fake()->randomElement(['zap', 'droplets', 'route', 'hard-hat', 'building-2', 'wrench']),
            'description' => fake()->optional()->sentence(),
            'status' => 'active',
            'is_public' => true,
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }
}
