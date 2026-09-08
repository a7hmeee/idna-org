<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domains\Footer\Models\FooterItem;
use App\Domains\Jobs\Models\EmploymentTypeLabel;
use App\Domains\Navigation\Models\NavigationItem;
use App\Domains\Seo\Models\SeoSetting;
use App\Domains\WaterSchedule\Models\WaterStatusLabel;
use App\Domains\WebsiteSettings\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

final class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedNavigation();
        $this->seedFooter();
        $this->seedSeo();
        $this->seedWaterStatusLabels();
        $this->seedEmploymentTypeLabels();
        $this->seedWebsiteSettings();
    }

    private function seedNavigation(): void
    {
        NavigationItem::truncate();

        // Main navigation - Desktop
        $home = NavigationItem::create(['label' => 'الرئيسية', 'route_name' => 'home', 'sort_order' => 0, 'section' => 'main']);
        $about = NavigationItem::create(['label' => 'عن البلدية', 'route_name' => 'public.municipality.about', 'sort_order' => 1, 'section' => 'main']);

        // Services dropdown
        $services = NavigationItem::create(['label' => 'الخدمات', 'sort_order' => 2, 'section' => 'main', 'icon' => 'chevron-down']);
        NavigationItem::create(['label' => 'جميع الخدمات', 'route_name' => 'public.services.index', 'parent_id' => $services->id, 'sort_order' => 0, 'section' => 'main']);
        NavigationItem::create(['label' => 'تصنيفات الخدمات', 'route_name' => 'public.services.index', 'parent_id' => $services->id, 'sort_order' => 1, 'section' => 'main']);

        // Council dropdown
        $council = NavigationItem::create(['label' => 'المجلس البلدي', 'sort_order' => 3, 'section' => 'main', 'icon' => 'chevron-down']);
        NavigationItem::create(['label' => 'أعضاء المجلس', 'route_name' => 'public.council.index', 'parent_id' => $council->id, 'sort_order' => 0, 'section' => 'main']);
        NavigationItem::create(['label' => 'قرارات المجلس', 'route_name' => 'public.council.decisions.index', 'parent_id' => $council->id, 'sort_order' => 1, 'section' => 'main']);

        NavigationItem::create(['label' => 'الأقسام', 'route_name' => 'public.departments.index', 'sort_order' => 4, 'section' => 'main']);
        NavigationItem::create(['label' => 'المرافق العامة', 'route_name' => 'public.facilities.index', 'sort_order' => 5, 'section' => 'main']);
        NavigationItem::create(['label' => 'الوظائف', 'route_name' => 'public.jobs.index', 'sort_order' => 6, 'section' => 'main']);

        // More dropdown
        $more = NavigationItem::create(['label' => 'المزيد', 'sort_order' => 7, 'section' => 'main', 'icon' => 'chevron-down']);
        NavigationItem::create(['label' => 'المكاتب الهندسية', 'route_name' => 'public.engineering-offices.index', 'parent_id' => $more->id, 'sort_order' => 0, 'section' => 'main']);
        NavigationItem::create(['label' => 'الأخبار', 'route_name' => 'public.news.index', 'parent_id' => $more->id, 'sort_order' => 1, 'section' => 'main']);
        NavigationItem::create(['label' => 'الإعلانات', 'route_name' => 'public.announcements.index', 'parent_id' => $more->id, 'sort_order' => 2, 'section' => 'main']);
        NavigationItem::create(['label' => 'المشاريع', 'route_name' => 'public.projects.index', 'parent_id' => $more->id, 'sort_order' => 3, 'section' => 'main']);
        NavigationItem::create(['label' => 'المناقصات', 'route_name' => 'public.tenders.index', 'parent_id' => $more->id, 'sort_order' => 4, 'section' => 'main']);
        NavigationItem::create(['label' => 'جدول المياه', 'route_name' => 'public.water-schedule', 'parent_id' => $more->id, 'sort_order' => 5, 'section' => 'main']);
        NavigationItem::create(['label' => 'البيانات المفتوحة', 'route_name' => 'public.open-data.index', 'parent_id' => $more->id, 'sort_order' => 6, 'section' => 'main']);

        // Complaints sub-dropdown under More
        $complaintsGroup = NavigationItem::create(['label' => 'الشكاوى', 'sort_order' => 7, 'section' => 'main', 'parent_id' => $more->id, 'icon' => 'chevron-down']);
        NavigationItem::create(['label' => 'تقديم شكوى', 'route_name' => 'public.complaints.submit', 'parent_id' => $complaintsGroup->id, 'sort_order' => 0, 'section' => 'main']);
        NavigationItem::create(['label' => 'تتبع شكوى', 'route_name' => 'public.complaints.track', 'parent_id' => $complaintsGroup->id, 'sort_order' => 1, 'section' => 'main']);
    }

    private function seedFooter(): void
    {
        FooterItem::truncate();

        // Quick Links column
        $ql = 'quick_links';
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'الرئيسية', 'route_name' => 'home', 'sort_order' => 0]);
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'عن البلدية', 'route_name' => 'public.municipality.about', 'sort_order' => 1]);
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'الخدمات', 'route_name' => 'public.services.index', 'sort_order' => 2]);
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'المجلس البلدي', 'route_name' => 'public.council.index', 'sort_order' => 3]);
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'الأقسام', 'route_name' => 'public.departments.index', 'sort_order' => 4]);
        FooterItem::create(['column_key' => $ql, 'column_title' => 'روابط سريعة', 'label' => 'اتصل بنا', 'url' => '#contact', 'sort_order' => 5]);

        // E-Services column
        $es = 'services';
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'بوابة الخدمات', 'url' => config('app.portal_url', '#'), 'sort_order' => 0, 'is_external' => true]);
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'جميع الخدمات', 'route_name' => 'public.services.index', 'sort_order' => 1]);
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'جدول توزيع المياه', 'route_name' => 'public.water-schedule', 'sort_order' => 2]);
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'الوظائف', 'route_name' => 'public.jobs.index', 'sort_order' => 3]);
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'المرافق العامة', 'route_name' => 'public.facilities.index', 'sort_order' => 4]);
        FooterItem::create(['column_key' => $es, 'column_title' => 'خدمات إلكترونية', 'label' => 'الإعلانات', 'route_name' => 'public.announcements.index', 'sort_order' => 5]);
    }

    private function seedSeo(): void
    {
        SeoSetting::truncate();

        SeoSetting::create([
            'page_key' => 'global',
            'title' => 'بلدية إذنا',
            'meta_description' => 'بلدية إذنا - الموقع الرسمي لبلدية إذنا. تعرف على خدمات البلدية والأخبار والمشاريع والمناقصات.',
            'og_title' => 'بلدية إذنا',
            'og_description' => 'الموقع الرسمي لبلدية إذنا - خدمات إلكترونية وأخبار ومشاريع',
            'robots' => 'index,follow',
            'author' => 'بلدية إذنا',
        ]);

        SeoSetting::create([
            'page_key' => 'home',
            'title' => 'بلدية إذنا - الصفحة الرئيسية',
            'meta_description' => 'مرحباً بكم في موقع بلدية إذنا. تعرف على خدماتنا وأخبارنا ومشاريعنا.',
            'og_title' => 'بلدية إذنا',
            'og_description' => 'الموقع الرسمي لبلدية إذنا',
        ]);

        SeoSetting::create([
            'page_key' => 'about',
            'title' => 'عن البلدية | بلدية إذنا',
            'meta_description' => 'تعرف على تاريخ بلدية إذنا ورؤيتها ورسالتها وأعضاء المجلس البلدي.',
        ]);

        SeoSetting::create([
            'page_key' => 'services',
            'title' => 'الخدمات الإلكترونية | بلدية إذنا',
            'meta_description' => 'تصفح الخدمات الإلكترونية المتاحة من بلدية إذنا لتسهيل معاملات المواطنين.',
        ]);

        SeoSetting::create([
            'page_key' => 'news',
            'title' => 'الأخبار | بلدية إذنا',
            'meta_description' => 'تابع آخر أخبار بلدية إذنا والفعاليات والأنشطة البلدية.',
        ]);

        SeoSetting::create([
            'page_key' => 'jobs',
            'title' => 'الوظائف | بلدية إذنا',
            'meta_description' => 'تصفح الوظائف الشاغرة والفرص الوظيفية في بلدية إذنا.',
        ]);

        SeoSetting::create([
            'page_key' => 'tenders',
            'title' => 'المناقصات | بلدية إذنا',
            'meta_description' => 'تصفح المناقصات والعطاءات المعلنة من بلدية إذنا.',
        ]);

        SeoSetting::create([
            'page_key' => 'projects',
            'title' => 'المشاريع | بلدية إذنا',
            'meta_description' => 'تصفح مشاريع بلدية إذنا الحالية والمنجزة.',
        ]);
    }

    private function seedWaterStatusLabels(): void
    {
        WaterStatusLabel::truncate();

        WaterStatusLabel::create(['status_key' => 'available', 'label_ar' => 'متوفر', 'color' => '#176B32', 'bg_color' => '#EAF5EE', 'dot_color' => '#176B32', 'sort_order' => 0]);
        WaterStatusLabel::create(['status_key' => 'low_pressure', 'label_ar' => 'ضغط منخفض', 'color' => '#B45309', 'bg_color' => '#FEF3C7', 'dot_color' => '#B45309', 'sort_order' => 1]);
        WaterStatusLabel::create(['status_key' => 'maintenance', 'label_ar' => 'صيانة', 'color' => '#B45309', 'bg_color' => '#FEF3C7', 'dot_color' => '#B45309', 'sort_order' => 2]);
        WaterStatusLabel::create(['status_key' => 'emergency', 'label_ar' => 'طارئ', 'color' => '#DC2626', 'bg_color' => '#FEE2E2', 'dot_color' => '#DC2626', 'sort_order' => 3]);
        WaterStatusLabel::create(['status_key' => 'no_water', 'label_ar' => 'مقطوع', 'color' => '#6B7280', 'bg_color' => '#F3F4F6', 'dot_color' => '#D1D5DB', 'sort_order' => 4]);
    }

    private function seedEmploymentTypeLabels(): void
    {
        EmploymentTypeLabel::truncate();

        EmploymentTypeLabel::create(['type_key' => 'full_time', 'label_ar' => 'دوام كامل', 'sort_order' => 0]);
        EmploymentTypeLabel::create(['type_key' => 'part_time', 'label_ar' => 'دوام جزئي', 'sort_order' => 1]);
        EmploymentTypeLabel::create(['type_key' => 'contract', 'label_ar' => 'عقد', 'sort_order' => 2]);
        EmploymentTypeLabel::create(['type_key' => 'temporary', 'label_ar' => 'مؤقت', 'sort_order' => 3]);
        EmploymentTypeLabel::create(['type_key' => 'volunteer', 'label_ar' => 'تطوع', 'sort_order' => 4]);
        EmploymentTypeLabel::create(['type_key' => 'internship', 'label_ar' => 'تدريب', 'sort_order' => 5]);
    }

    private function seedWebsiteSettings(): void
    {
        WebsiteSetting::truncate();

        WebsiteSetting::set('facebook_enabled', 'true', 'boolean', 'social');
        WebsiteSetting::set('facebook_page_url', 'https://www.facebook.com/100064888802457/', 'text', 'social');
        WebsiteSetting::set('facebook_plugin_width', '500', 'integer', 'social');
        WebsiteSetting::set('facebook_plugin_height', '1100', 'integer', 'social');
        WebsiteSetting::set('footer_copyright_text', 'جميع الحقوق محفوظة', 'text', 'footer');
        WebsiteSetting::set('footer_privacy_url', '', 'text', 'footer');
        WebsiteSetting::set('footer_terms_url', '', 'text', 'footer');
    }
}
