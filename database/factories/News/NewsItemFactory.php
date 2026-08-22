<?php

declare(strict_types=1);

namespace Database\Factories\News;

use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use App\Domains\News\Models\NewsItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class NewsItemFactory extends Factory
{
    protected $model = NewsItem::class;

    public function definition(): array
    {
        $titleAr = fake()->sentence(6);

        return [
            'title_ar' => $titleAr,
            'title_en' => fake()->sentence(6),
            'slug' => Str::slug($titleAr),
            'category' => fake()->randomElement(NewsCategory::cases())->value,
            'status' => NewsStatus::Published->value,
            'summary' => fake()->paragraph(2),
            'content' => fake()->paragraphs(5, true),
            'cover_image_path' => null,
            'mobile_image_path' => null,
            'author' => fake()->name(),
            'is_featured' => false,
            'is_public' => true,
            'display_order' => 0,
            'views_count' => fake()->numberBetween(0, 500),
            'publish_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'meta_title' => fake()->sentence(4),
            'meta_description' => fake()->paragraph(1),
            'meta_keywords' => implode(', ', fake()->words(5)),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => NewsStatus::Draft->value,
        ]);
    }

    public function archived(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => NewsStatus::Archived->value,
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }

    public function published(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => NewsStatus::Published->value,
            'publish_at' => now(),
        ]);
    }
}
