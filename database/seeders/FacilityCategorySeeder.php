<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\PublicFacilities\Models\FacilityCategory;
use Illuminate\Database\Seeder;

final class FacilityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'حكومي', 'slug' => 'governmental', 'icon' => 'building-2', 'display_order' => 1],
            ['name' => 'صحي', 'slug' => 'health', 'icon' => 'heart-pulse', 'display_order' => 2],
            ['name' => 'تعليمي', 'slug' => 'education', 'icon' => 'graduation-cap', 'display_order' => 3],
            ['name' => 'رياضي', 'slug' => 'sports', 'icon' => 'trophy', 'display_order' => 4],
            ['name' => 'شبابي', 'slug' => 'youth', 'icon' => 'users', 'display_order' => 5],
            ['name' => 'اجتماعي', 'slug' => 'social', 'icon' => 'hand-heart', 'display_order' => 6],
            ['name' => 'خيري', 'slug' => 'charity', 'icon' => 'heart-handshake', 'display_order' => 7],
            ['name' => 'نسوي', 'slug' => 'women', 'icon' => 'person-standing', 'display_order' => 8],
            ['name' => 'ثقافي', 'slug' => 'cultural', 'icon' => 'book-open', 'display_order' => 9],
            ['name' => 'تراثي', 'slug' => 'heritage', 'icon' => 'landmark', 'display_order' => 10],
            ['name' => 'ترفيهي', 'slug' => 'entertainment', 'icon' => 'trees', 'display_order' => 11],
            ['name' => 'ديني', 'slug' => 'religious', 'icon' => 'mosque', 'display_order' => 12],
            ['name' => 'خدمات', 'slug' => 'services', 'icon' => 'concierge-bell', 'display_order' => 13],
        ];

        foreach ($categories as $data) {
            $category = FacilityCategory::withTrashed()
                ->where(fn ($query) => $query
                    ->where('slug', $data['slug'])
                    ->orWhere('name', $data['name']))
                ->first();

            if ($category) {
                $category->restore();
                $category->update($data);

                continue;
            }

            FacilityCategory::create($data);
        }
    }
}
