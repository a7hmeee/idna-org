<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Municipality\Models\CouncilDecision;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CouncilDecision>
 */
final class CouncilDecisionFactory extends Factory
{
    protected $model = CouncilDecision::class;

    public function definition(): array
    {
        return [
            'decision_number' => 'ق-'.fake()->unique()->year().'-'.fake()->unique()->numerify('###'),
            'title' => fake()->sentence(6),
            'summary' => fake()->optional()->paragraph(),
            'content' => fake()->optional()->paragraphs(3, true),
            'type' => fake()->randomElement(['administrative', 'financial', 'regulatory', 'service', 'infrastructure', 'public']),
            'status' => 'draft',
            'decision_date' => fake()->optional()->date(),
            'session_number' => fake()->optional()->numerify('جلسة-###'),
            'attachment_path' => null,
            'is_public' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
