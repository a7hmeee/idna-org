<?php

declare(strict_types=1);

namespace Database\Factories\PublicFacilities;

use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class FacilityCategoryFactory extends Factory
{
    protected $model = FacilityCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'حدائق',
            'ملاعب',
            'قاعات',
            'مراكز ثقافية',
            'مكتبات',
            'مبانٍ بلدية',
            'مرافق عامة',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => fake()->randomElement(['tree-pine', 'zap', 'square', 'landmark', 'book-open', 'building-2', 'globe']),
            'description' => fake()->sentence(),
            'display_order' => fake()->numberBetween(0, 20),
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
