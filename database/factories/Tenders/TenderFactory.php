<?php

declare(strict_types=1);

namespace Database\Factories\Tenders;

use App\Domains\Tenders\Enums\TenderStatus;
use App\Domains\Tenders\Models\Tender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class TenderFactory extends Factory
{
    protected $model = Tender::class;

    public function definition(): array
    {
        $titleAr = fake()->unique()->company() . ' مناقصة';

        return [
            'tender_number' => 'م-' . fake()->year() . '-' . fake()->unique()->randomNumber(3),
            'title_ar' => $titleAr,
            'title_en' => Str::slug($titleAr),
            'slug' => Str::slug($titleAr) . '-' . fake()->unique()->randomNumber(4),
            'summary' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'category' => fake()->randomElement(['أشغال', 'توريدات', 'خدمات', 'استشارات', 'إنشاءات']),
            'issuing_department' => fake()->company(),
            'publication_date' => now()->subDay()->toDateString(),
            'submission_deadline' => now()->addMonth()->toDateString(),
            'opening_date' => now()->addMonth()->addDay()->toDateString(),
            'status' => TenderStatus::Open->value,
            'eligibility_requirements' => [fake()->sentence(), fake()->sentence()],
            'application_instructions' => [fake()->sentence(), fake()->sentence()],
            'contact_info' => fake()->address(),
            'contact_phone' => '022...',
            'contact_email' => 'procurement@idhna.ps',
            'tender_documents' => ['كراسة الشروط', 'المواصفات الفنية'],
            'result_documents' => null,
            'budget' => fake()->randomElement(['50000', '100000', '200000', '500000']),
            'budget_currency' => 'ILS',
            'is_featured' => fake()->boolean(20),
            'is_public' => true,
            'display_order' => 0,
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenderStatus::Draft->value,
            'is_public' => false,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenderStatus::Closed->value,
            'submission_deadline' => now()->subDay()->toDateString(),
        ]);
    }

    public function awarded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenderStatus::Awarded->value,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenderStatus::Cancelled->value,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TenderStatus::Archived->value,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
