<?php

declare(strict_types=1);

namespace Database\Factories\Announcements;

use App\Domains\Announcements\Enums\AnnouncementPriority;
use App\Domains\Announcements\Enums\AnnouncementStatus;
use App\Domains\Announcements\Enums\AnnouncementType;
use App\Domains\Announcements\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;

final class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'type' => fake()->randomElement(AnnouncementType::cases())->value,
            'priority' => fake()->randomElement(AnnouncementPriority::cases())->value,
            'status' => AnnouncementStatus::Published->value,
            'short_description' => fake()->paragraph(2),
            'content' => fake()->paragraphs(5, true),
            'desktop_image_path' => null,
            'mobile_image_path' => null,
            'is_featured' => false,
            'display_order' => 0,
            'views' => fake()->numberBetween(0, 500),
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    public function draft(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => AnnouncementStatus::Draft->value,
        ]);
    }

    public function archived(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => AnnouncementStatus::Archived->value,
        ]);
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes): array => [
            'is_featured' => true,
        ]);
    }

    public function urgent(): self
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => AnnouncementPriority::Urgent->value,
        ]);
    }


}
