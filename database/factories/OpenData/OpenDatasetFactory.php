<?php

declare(strict_types=1);

namespace Database\Factories\OpenData;

use App\Domains\Authentication\Models\User;
use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;
use App\Domains\OpenData\Models\OpenDataset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class OpenDatasetFactory extends Factory
{
    protected $model = OpenDataset::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'type' => OpenDataType::Dataset,
            'category' => fake()->optional()->randomElement(['إحصاءات', 'تقارير', 'خرائط', 'مستندات']),
            'description' => fake()->optional()->paragraph(),
            'file_size' => fake()->optional()->numberBetween(1000, 10000000),
            'file_format' => fake()->optional()->randomElement(['pdf', 'csv', 'xlsx', 'json', 'xml']),
            'status' => OpenDataStatus::Published,
            'is_featured' => fake()->boolean(20),
            'display_order' => 0,
            'published_at' => fake()->dateTimeThisYear(),
            'created_by' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OpenDataStatus::Draft,
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OpenDataStatus::Published,
            'published_at' => now(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OpenDataStatus::Archived,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
