<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
final class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'short_description' => fake()->optional()->sentence(),
            'description' => fake()->optional()->paragraphs(2, true),
            'icon' => fake()->randomElement(['building-2', 'shield', 'graduation-cap', 'stethoscope', 'wallet', 'wrench']),
            'cover_image_path' => null,
            'manager_name' => fake()->optional()->name(),
            'manager_position' => fake()->optional()->jobTitle(),
            'phone' => fake()->optional()->phoneNumber(),
            'extension' => fake()->optional()->numerify('###'),
            'mobile' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->companyEmail(),
            'office_location' => fake()->optional()->address(),
            'working_hours' => fake()->optional()->sentence(),
            'vision' => fake()->optional()->paragraph(),
            'mission' => fake()->optional()->paragraph(),
            'responsibilities' => fake()->optional()->paragraphs(3, true),
            'status' => 'active',
            'display_order' => fake()->numberBetween(0, 100),
            'is_public' => true,
            'is_featured' => fake()->boolean(20),
        ];
    }
}
