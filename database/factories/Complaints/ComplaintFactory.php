<?php

declare(strict_types=1);

namespace Database\Factories\Complaints;

use App\Domains\Complaints\Enums\ComplaintCategory;
use App\Domains\Complaints\Enums\ComplaintPriority;
use App\Domains\Complaints\Enums\ComplaintStatus;
use App\Domains\Complaints\Models\Complaint;
use App\Domains\Department\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
        return [
            'complaint_number' => 'ش-'.fake()->year().'-'.fake()->unique()->randomNumber(4),
            'tracking_number' => 'CMP-'.strtoupper(Str::random(10)),
            'citizen_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional()->email(),
            'national_id' => null,
            'category' => fake()->randomElement(ComplaintCategory::cases())->value,
            'department_id' => Department::factory(),
            'subject' => fake()->sentence(8),
            'description' => fake()->paragraphs(3, true),
            'location' => fake()->optional()->address(),
            'latitude' => fake()->optional()->latitude(),
            'longitude' => fake()->optional()->longitude(),
            'attachments' => null,
            'priority' => fake()->randomElement(ComplaintPriority::cases())->value,
            'status' => ComplaintStatus::Submitted->value,
            'internal_notes' => null,
            'public_response' => null,
            'assigned_to' => null,
            'submitted_by' => null,
            'submitted_at' => now()->subHours(fake()->numberBetween(1, 168)),
            'resolution_at' => null,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    public function underReview(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::UnderReview->value,
        ]);
    }

    public function assigned(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::Assigned->value,
            'assigned_to' => 1,
        ]);
    }

    public function inProgress(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::InProgress->value,
            'assigned_to' => 1,
        ]);
    }

    public function resolved(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::Resolved->value,
            'public_response' => fake()->paragraph(),
            'resolution_at' => now(),
        ]);
    }

    public function rejected(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::Rejected->value,
        ]);
    }

    public function closed(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ComplaintStatus::Closed->value,
        ]);
    }

    public function urgent(): self
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => ComplaintPriority::Urgent->value,
        ]);
    }

    public function high(): self
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => ComplaintPriority::High->value,
        ]);
    }
}
