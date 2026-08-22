<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Authentication\Models\User;
use App\Domains\Homepage\Models\HomepageQuickLink;
use App\Domains\Homepage\Models\HomepageSection;
use App\Domains\Homepage\Models\HomepageSetting;
use App\Domains\Homepage\Models\HomepageSlide;
use App\Domains\Homepage\Models\HomepageStatistic;
use Illuminate\Database\Seeder;

final class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::first()?->id;

        $this->seedSettings($adminId);
        $this->seedSlides($adminId);
        $this->seedSections();
        $this->seedQuickLinks($adminId);
        $this->seedStatistics($adminId);
    }

    private function seedSettings(?int $adminId): void
    {
        HomepageSetting::updateOrCreate(
            ['id' => 1],
            [
                'site_title' => 'بلدية إذنا',
                'site_subtitle' => 'بلدية إذنا - الخدمات الإلكترونية',
                'portal_url' => 'https://i.palexpand.ps/portal',
                'primary_button_text' => 'الدخول إلى البوابة',
                'secondary_button_text' => 'تعرف على البلدية',
                'secondary_button_url' => '#municipality-intro',
                'welcome_title' => 'مرحباً بكم في بلدية إذنا',
                'welcome_description' => 'بلدية إذنا هي الجهة الرسمية المعنية بتقديم الخدمات البلدية للمواطنين في مدينة إذنا. نسعى لتقديم أفضل الخدمات الإلكترونية لتسهيل حياة المواطنين وتحسين جودة الخدمة.',
                'mayor_message_title' => 'رسالة رئيس البلدية',
                'mayor_message' => 'نرحب بكم في بوابة الخدمات الإلكترونية لبلدية إذنا. نسعى دائماً لتقديم أفضل الخدمات للمواطنين وتحسين جودة الحياة في بلدتنا الحبيبة.',
                'show_mayor_message' => true,
                'contact_cta_title' => 'تواصل معنا',
                'contact_cta_description' => 'نحن هنا لمساعدتك. لا تتردد في التواصل معنا لأي استفسار أو ملاحظة.',
                'contact_cta_button_text' => 'تواصل معنا',
                'contact_cta_button_url' => '#contact',
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]
        );
    }

    private function seedSlides(?int $adminId): void
    {
        $slides = [
            [
                'title' => 'مرحباً بكم في بلدية إذنا',
                'subtitle' => 'خدمات إلكترونية متكاملة لخدمة المواطن',
                'description' => 'استمتع بتجربة سلسة للحصول على الخدمات البلدية عبر البوابة الإلكترونية.',
                'badge_text' => 'خدمات إلكترونية',
                'button_text' => 'الدخول إلى البوابة',
                'button_url' => 'https://i.palexpand.ps/portal',
                'secondary_button_text' => 'تعرف علينا',
                'secondary_button_url' => '#municipality-intro',
                'sort_order' => 1,
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'title' => 'خدمات متنوعة لاحتياجاتك',
                'subtitle' => 'رخص، تصاريح، استشارات، والمزيد',
                'description' => 'خدمات إلكترونية تشمل جميع احتياجاتك البلدية من رخص بناء وتصاريح تجارية واستشارات قانونية.',
                'badge_text' => 'خدمات متنوعة',
                'button_text' => 'تصفح الخدمات',
                'button_url' => '/public-services',
                'sort_order' => 2,
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
            [
                'title' => 'مشاريع البلدية',
                'subtitle' => 'مشاريع تطويرية لخدمة المجتمع',
                'description' => 'تابع أحدث المشاريع التنموية والتطويرية التي تنفذها البلدية لتحسين بنية التحتية وخدمة المواطنين.',
                'badge_text' => 'مشاريع تنموية',
                'button_text' => 'تابع المشاريع',
                'button_url' => '#projects',
                'sort_order' => 3,
                'is_active' => true,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ],
        ];

        foreach ($slides as $index => $slide) {
            HomepageSlide::updateOrCreate(
                ['sort_order' => $slide['sort_order']],
                $slide
            );
        }
    }

    private function seedSections(): void
    {
        $sections = [
            ['key' => 'hero', 'title' => 'البانر الرئيسي', 'is_enabled' => true, 'sort_order' => 1, 'items_limit' => null],
            ['key' => 'quick_links', 'title' => 'الروابط السريعة', 'is_enabled' => true, 'sort_order' => 2, 'items_limit' => 6],
            ['key' => 'municipality_intro', 'title' => 'نبذة عن البلدية', 'is_enabled' => true, 'sort_order' => 3, 'items_limit' => null],
            ['key' => 'statistics', 'title' => 'الإحصائيات', 'is_enabled' => true, 'sort_order' => 4, 'items_limit' => 4],
            ['key' => 'services', 'title' => 'الخدمات الإلكترونية', 'is_enabled' => true, 'sort_order' => 5, 'items_limit' => 6],
            ['key' => 'departments', 'title' => 'أقسام البلدية', 'is_enabled' => true, 'sort_order' => 6, 'items_limit' => 6],
            ['key' => 'facilities', 'title' => 'المرافق العامة', 'is_enabled' => true, 'sort_order' => 7, 'items_limit' => 4],
            ['key' => 'council_members', 'title' => 'أعضاء المجلس البلدي', 'is_enabled' => true, 'sort_order' => 8, 'items_limit' => 8],
            ['key' => 'council_decisions', 'title' => 'قرارات المجلس البلدي', 'is_enabled' => true, 'sort_order' => 9, 'items_limit' => 5],
            ['key' => 'engineering_offices', 'title' => 'المكاتب الهندسية', 'is_enabled' => true, 'sort_order' => 10, 'items_limit' => 6],
            ['key' => 'tenders', 'title' => 'المناقصات', 'is_enabled' => false, 'sort_order' => 11, 'items_limit' => 4],
            ['key' => 'latest_news', 'title' => 'آخر الأخبار', 'is_enabled' => false, 'sort_order' => 12, 'items_limit' => 3],
            ['key' => 'projects', 'title' => 'المشاريع', 'is_enabled' => false, 'sort_order' => 13, 'items_limit' => 3],
            ['key' => 'announcements', 'title' => 'الإعلانات', 'is_enabled' => false, 'sort_order' => 14, 'items_limit' => 3],
            ['key' => 'contact_cta', 'title' => 'تواصل معنا', 'is_enabled' => true, 'sort_order' => 15, 'items_limit' => null],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }

    private function seedQuickLinks(?int $adminId): void
    {
        $links = [
            [
                'title' => 'الخدمات الإلكترونية',
                'url' => '/public-services',
                'icon' => 'monitor',
                'type' => 'service',
                'is_external' => false,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'جدول توزيع المياه',
                'url' => '/water-schedule',
                'icon' => 'droplet',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'المكاتب الهندسية',
                'url' => '#engineering-offices',
                'icon' => 'hard-hat',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'الوظائف المتناوبة',
                'url' => '/jobs',
                'icon' => 'briefcase',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'المرافق العامة',
                'url' => '/facilities',
                'icon' => 'landmark',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'إعلانات البلدية',
                'url' => '#news',
                'icon' => 'megaphone',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'تواصل معنا',
                'url' => '#contact',
                'icon' => 'phone',
                'type' => 'link',
                'is_external' => false,
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        foreach ($links as $link) {
            HomepageQuickLink::updateOrCreate(
                ['sort_order' => $link['sort_order']],
                $link
            );
        }
    }

    private function seedStatistics(?int $adminId): void
    {
        $stats = [
            [
                'label' => 'عدد السكان',
                'value' => '45000',
                'suffix' => '+',
                'icon' => 'users',
                'description' => 'مواطن مسجل في بلدية إذنا',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'label' => 'المساحة',
                'value' => '85',
                'suffix' => 'كم²',
                'icon' => 'map-pin',
                'description' => 'مساحة النطاق الإداري للبلدية',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'label' => 'الخدمات الإلكترونية',
                'value' => '50',
                'suffix' => '+',
                'icon' => 'laptop',
                'description' => 'خدمة إلكترونية متاحة عبر البوابة',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'label' => 'سنوات الخدمة',
                'value' => '30',
                'suffix' => '+',
                'icon' => 'calendar',
                'description' => 'سنة من خدمة المجتمع المحلي',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($stats as $stat) {
            HomepageStatistic::updateOrCreate(
                ['sort_order' => $stat['sort_order']],
                $stat
            );
        }
    }
}
