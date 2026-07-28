<?php

declare(strict_types=1);

namespace Database\Factories\PublicFacilities;

use App\Domains\PublicFacilities\Models\Facility;
use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

final class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'حديقة البلدية',
            'ملعب بلدي',
            'قاعة المؤتمرات',
            'المركز الثقافي',
            'مكتبة عامة',
            'مبنى البلدية',
            'ساحة البلدية',
            'نادي رياضي',
        ]);

        return [
            'facility_category_id' => FacilityCategory::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'summary' => fake()->sentence(10),
            'description' => fake()->paragraphs(3, true),
            'cover_image_path' => null,
            'gallery' => null,
            'phone' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->companyEmail(),
            'address' => fake()->address(),
            'working_hours' => 'الأحد - الخميس: 8:00 ص - 4:00 م',
            'services' => fake()->randomElements(['قاعة اجتماعات', 'مواقف سيارات', 'دورات مياه', 'مصعد', 'إنترنت'], rand(1, 3)),
            'features' => fake()->randomElements(['مكيف', 'ذوي الإعاقة', 'إنترنت', 'مواقف', 'كاميرات مراقبة'], rand(1, 3)),
            'rules' => fake()->randomElements(['يمنع التدخين', 'الالتزام بالنظافة', 'عدم إزعاج الآخرين', 'الحفاظ على الممتلكات'], rand(1, 3)),
            'status' => 'published',
            'is_public' => true,
            'is_featured' => fake()->boolean(20),
            'display_order' => fake()->numberBetween(0, 20),
            'views_count' => fake()->numberBetween(0, 500),
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'is_public' => false,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
