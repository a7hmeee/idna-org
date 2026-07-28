<?php

declare(strict_types=1);

namespace Database\Factories\Jobs;

use App\Domains\Department\Models\Department;
use App\Domains\Jobs\Enums\ApplicationMethod;
use App\Domains\Jobs\Enums\EmploymentType;
use App\Domains\Jobs\Enums\JobStatus;
use App\Domains\Jobs\Models\Job;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            'department_id' => Department::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->unique()->randomNumber(4),
            'job_number' => 'ج-' . fake()->year() . '-' . fake()->unique()->randomNumber(3),
            'employment_type' => fake()->randomElement(EmploymentType::cases())->value,
            'location' => 'إذنا',
            'salary' => fake()->randomElement(['3000-5000 شيكل', 'حسب المؤهلات', null]),
            'vacancies' => fake()->numberBetween(1, 5),
            'summary' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'requirements' => [fake()->sentence(), fake()->sentence(), fake()->sentence()],
            'responsibilities' => [fake()->sentence(), fake()->sentence()],
            'benefits' => fake()->randomElement([[fake()->sentence()], null]),
            'required_documents' => ['السيرة الذاتية', 'صورة الهوية', 'الشهادات العلمية'],
            'application_method' => fake()->randomElement(ApplicationMethod::cases())->value,
            'application_url' => fake()->url(),
            'application_email' => 'hr@idhna.ps',
            'application_phone' => '022...',
            'attachment_path' => null,
            'publish_at' => now()->subDay()->toDateString(),
            'closing_at' => now()->addMonth()->toDateString(),
            'status' => JobStatus::Published->value,
            'is_public' => true,
            'is_featured' => fake()->boolean(20),
            'display_order' => 0,
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobStatus::Draft->value,
            'is_public' => false,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobStatus::Closed->value,
            'closing_at' => now()->subDay()->toDateString(),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => JobStatus::Archived->value,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
