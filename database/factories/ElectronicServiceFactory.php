<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ElectronicService>
 */
final class ElectronicServiceFactory extends Factory
{
    protected $model = ElectronicService::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(3),
            'summary' => fake()->optional()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'eligibility' => fake()->optional()->sentence(),
            'requirements' => [
                ['title' => 'صورة الهوية', 'description' => 'صورة واضحة عن الهوية', 'is_required' => true],
            ],
            'documents' => [
                ['name' => 'سند ملكية', 'description' => 'لإثبات الملكية', 'is_required' => true],
            ],
            'steps' => [
                ['title' => 'تعبئة الطلب', 'description' => 'قم بتعبئة البيانات'],
                ['title' => 'إرفاق الملفات', 'description' => 'أرفق المستندات المطلوبة'],
            ],
            'fees' => [
                ['title' => 'رسوم الخدمة', 'amount' => '0', 'currency' => 'ILS', 'notes' => 'حسب النوع'],
            ],
            'processing_time' => fake()->randomElement(['5-10 أيام عمل', '15-30 يوماً', 'فوري']),
            'portal_url' => 'https://portal.idhna.ps/apply',
            'requires_login' => true,
            'status' => fake()->randomElement(['draft', 'active']),
            'is_public' => true,
            'is_featured' => fake()->boolean(20),
            'views_count' => fake()->numberBetween(0, 500),
            'portal_clicks_count' => fake()->numberBetween(0, 100),
        ];
    }
}
