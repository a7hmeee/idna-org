<?php

declare(strict_types=1);

namespace Database\Factories\Projects;

use App\Domains\Projects\Enums\ProjectCategory;
use App\Domains\Projects\Enums\ProjectStatus;
use App\Domains\Projects\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $nameAr = fake()->sentence(4);

        return [
            'name_ar' => $nameAr,
            'name_en' => fake()->sentence(4),
            'slug' => Str::slug($nameAr) . '-' . fake()->unique()->randomNumber(4),
            'category' => fake()->randomElement(ProjectCategory::cases())->value,
            'project_status' => fake()->randomElement([ProjectStatus::Planned->value, ProjectStatus::InProgress->value, ProjectStatus::Completed->value]),
            'status' => ProjectStatus::Completed->value,
            'summary' => fake()->paragraph(2),
            'description' => fake()->paragraphs(5, true),
            'start_date' => now()->subMonths(fake()->numberBetween(1, 24))->toDateString(),
            'expected_completion_date' => now()->addMonths(fake()->numberBetween(1, 12))->toDateString(),
            'actual_completion_date' => null,
            'location' => 'إذنا',
            'budget' => fake()->randomFloat(2, 10000, 5000000),
            'budget_currency' => 'ILS',
            'implementation_percentage' => fake()->numberBetween(0, 100),
            'contractor' => fake()->company(),
            'funding_entity' => fake()->randomElement(['وزارة الحكم المحلي', 'بلدية إذنا', 'المانحون الدوليون', null]),
            'cover_image_path' => null,
            'gallery' => null,
            'documents' => null,
            'is_featured' => fake()->boolean(20),
            'is_public' => true,
            'display_order' => 0,
            'views_count' => fake()->numberBetween(0, 500),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::Planned->value,
            'is_public' => false,
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (array $attributes): array => [
            'project_status' => ProjectStatus::InProgress->value,
        ]);
    }

    public function completed(): self
    {
        return $this->state(fn (array $attributes): array => [
            'project_status' => ProjectStatus::Completed->value,
            'actual_completion_date' => now()->toDateString(),
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }
}
