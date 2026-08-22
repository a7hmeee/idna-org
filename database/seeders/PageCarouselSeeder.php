<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Homepage\Models\HomepageSlide;
use Illuminate\Database\Seeder;

final class PageCarouselSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'page_key' => 'services',
                'title' => 'الخدمات الإلكترونية',
                'description' => 'استعرض جميع الخدمات الإلكترونية التي تقدمها بلدية إذنا، وأنجز معاملاتك إلكترونياً بكل سهولة.',
                'badge_text' => 'الخدمات الإلكترونية',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'departments',
                'title' => 'دوائر البلدية',
                'description' => 'تعرف على الدوائر والأقسام في بلدية إذنا، واطلع على خدماتها ومعلومات الاتصال بها.',
                'badge_text' => 'دوائر البلدية',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'facilities',
                'title' => 'المرافق العامة',
                'description' => 'استعرض جميع المرافق العامة التي تديرها البلدية، وتعرف على الخدمات التي تقدمها.',
                'badge_text' => 'المرافق العامة',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'jobs',
                'title' => 'الوظائف',
                'description' => 'تصفح الفرص الوظيفية المتاحة في بلدية إذنا، وقدم طلبك إلكترونياً.',
                'badge_text' => 'الوظائف',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'engineering-offices',
                'title' => 'المكاتب الهندسية',
                'description' => 'تصفح المكاتب الهندسية المعتمدة من قبل البلدية، وتعرف على خدماتها ومعلومات الاتصال بها.',
                'badge_text' => 'المكاتب الهندسية',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'water-schedule',
                'title' => 'جدول توزيع المياه',
                'description' => 'تفقد جدول الضخ الأسبوعي للمياه في مختلف مناطق بلدية إذنا.',
                'badge_text' => 'جدول المياه',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'page_key' => 'open-data',
                'title' => 'البيانات المفتوحة',
                'description' => 'تصفح البيانات المفتوحة المتاحة من بلدية إذنا، بما في ذلك التقارير والإحصاءات والدراسات.',
                'badge_text' => 'البيانات المفتوحة',
                'is_active' => true,
                'sort_order' => 0,
            ],
        ];

        foreach ($slides as $slide) {
            HomepageSlide::firstOrCreate(
                ['page_key' => $slide['page_key'], 'title' => $slide['title']],
                $slide
            );
        }

        $this->command->info('✓ تم إنشاء '.count($slides).' شريحة لكاروسيل الصفحات');
    }
}
