<?php

declare(strict_types=1);

namespace App\Domains\Homepage\Services;

use App\Domains\Homepage\Models\CarouselConfiguration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

final class CarouselRegistry
{
    /**
     * All known carousels in the project.
     * Each entry: [key, name, page, section, type, defaults...]
     */
    private static array $discovered = [
        [
            'key' => 'homepage-hero',
            'name' => 'الشريط الرئيسي — الرئيسية',
            'title' => 'مرحباً بكم في بلدية إذنا',
            'subtitle' => 'نعمل لخدمة مجتمع إذنا وتحسين جودة الحياة',
            'page' => 'home',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
            'loop' => true,
        ],
        [
            'key' => 'page-carousel-services',
            'name' => 'كاروسيل الخدمات الإلكترونية',
            'title' => 'الخدمات الإلكترونية',
            'subtitle' => 'استكشف خدمات البلدية الإلكترونية وقدم طلبك بسهولة',
            'page' => 'services',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-departments',
            'name' => 'كاروسيل الدوائر',
            'title' => 'الدوائر البلدية',
            'subtitle' => 'تعرف على الدوائر والأقسام البلدية وخدماتها',
            'page' => 'departments',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-facilities',
            'name' => 'كاروسيل المرافق العامة',
            'title' => 'المرافق العامة',
            'subtitle' => 'استكشف المرافق العامة التي تقدمها البلدية',
            'page' => 'facilities',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-jobs',
            'name' => 'كاروسيل الوظائف',
            'title' => 'الوظائف الشاغرة',
            'subtitle' => 'تصفح فرص العمل المتاحة في البلدية',
            'page' => 'jobs',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-council-members',
            'name' => 'كاروسيل المجلس البلدي',
            'title' => 'أعضاء المجلس البلدي',
            'subtitle' => 'تعرّف على أعضاء المجلس البلدي ومعلوماتهم',
            'page' => 'council-members',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-council-decisions',
            'name' => 'كاروسيل قرارات المجلس',
            'title' => 'قرارات المجلس البلدي',
            'subtitle' => 'اطلع على آخر القرارات الصادرة عن المجلس',
            'page' => 'council-decisions',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-engineering-offices',
            'name' => 'كاروسيل المكاتب الهندسية',
            'title' => 'المكاتب الهندسية',
            'subtitle' => 'قائمة المكاتب الهندسية المعتمدة لدى البلدية',
            'page' => 'engineering-offices',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-open-data',
            'name' => 'كاروسيل البيانات المفتوحة',
            'title' => 'البيانات المفتوحة',
            'subtitle' => 'اطلع على البيانات والمعلومات المفتوحة للجمهور',
            'page' => 'open-data',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-water-schedule',
            'name' => 'كاروسيل جدول المياه',
            'title' => 'جدول توزيع المياه',
            'subtitle' => 'تابع أوقات توزيع المياه في منطقتك',
            'page' => 'water-schedule',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-announcements',
            'name' => 'كاروسيل الإعلانات',
            'title' => 'الإعلانات',
            'subtitle' => 'تابع آخر الإعلانات والتنبيهات البلدية',
            'page' => 'announcements',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-about',
            'name' => 'كاروسيل عن البلدية',
            'title' => 'عن بلدية إذنا',
            'subtitle' => 'تعرف على تاريخ بلدية إذنا وخدماتها',
            'page' => 'about',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-news',
            'name' => 'كاروسيل الأخبار',
            'title' => 'آخر الأخبار',
            'subtitle' => 'تابع آخر المستجدات والأخبار البلدية',
            'page' => 'news',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-projects',
            'name' => 'كاروسيل المشاريع',
            'title' => 'مشاريع البلدية',
            'subtitle' => 'تابع مشاريع البنية التحتية والتنمية',
            'page' => 'projects',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'page-carousel-tenders',
            'name' => 'كاروسيل المناقصات',
            'title' => 'المناقصات والعطاءات',
            'subtitle' => 'تصفح المناقصات والمناقصات المفتوحة',
            'page' => 'tenders',
            'section' => 'hero',
            'type' => 'hero',
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'show_navigation' => false,
            'show_pagination' => true,
        ],
        [
            'key' => 'homepage-council-members',
            'name' => 'أعضاء المجلس — الرئيسية',
            'title' => 'أعضاء المجلس البلدي',
            'subtitle' => null,
            'page' => 'home',
            'section' => 'council-members',
            'type' => 'card',
            'desktop_slides' => 3,
            'tablet_slides' => 2,
            'mobile_slides' => 1,
            'autoplay' => false,
            'show_navigation' => true,
            'show_pagination' => true,
        ],
        [
            'key' => 'about-council-members',
            'name' => 'أعضاء المجلس — عن البلدية',
            'title' => 'أعضاء المجلس البلدي',
            'subtitle' => null,
            'page' => 'about',
            'section' => 'council-members',
            'type' => 'card',
            'desktop_slides' => 3,
            'tablet_slides' => 2,
            'mobile_slides' => 1,
            'autoplay' => false,
            'show_navigation' => true,
            'show_pagination' => false,
        ],

        // ─── Homepage Section Carousels (titles for homepage sections) ───
        [
            'key' => 'homepage-services',
            'name' => 'قسم الخدمات — الرئيسية',
            'title' => 'الخدمات الإلكترونية',
            'subtitle' => 'أنجز خدماتك وطلباتك إلكترونيًا بسهولة وسرعة',
            'page' => 'home',
            'section' => 'services',
            'type' => 'section',
            'autoplay' => false,
            'show_navigation' => false,
            'show_pagination' => false,
        ],
        [
            'key' => 'homepage-news',
            'name' => 'قسم الأخبار — الرئيسية',
            'title' => 'آخر الأخبار',
            'subtitle' => 'تابع آخر أخبار وفعاليات بلدية إذنا',
            'page' => 'home',
            'section' => 'news',
            'type' => 'section',
            'autoplay' => false,
            'show_navigation' => false,
            'show_pagination' => false,
        ],
        [
            'key' => 'homepage-announcements',
            'name' => 'قسم الإعلانات — الرئيسية',
            'title' => 'الإعلانات',
            'subtitle' => 'تابع آخر الإعلانات والتنبيهات البلدية',
            'page' => 'home',
            'section' => 'announcements',
            'type' => 'section',
            'autoplay' => false,
            'show_navigation' => false,
            'show_pagination' => false,
        ],
        [
            'key' => 'homepage-council-decisions',
            'name' => 'قسم قرارات المجلس — الرئيسية',
            'title' => 'قرارات المجلس البلدي',
            'subtitle' => 'اطلع على أحدث قرارات المجلس البلدي',
            'page' => 'home',
            'section' => 'council_decisions',
            'type' => 'section',
            'autoplay' => false,
            'show_navigation' => false,
            'show_pagination' => false,
        ],
    ];

    /**
     * Sync all discovered carousels into the database.
     * Non-destructive: only adds missing entries, never deletes or overwrites existing settings.
     */
    public static function sync(): int
    {
        $added = 0;

        foreach (self::$discovered as $def) {
            $exists = CarouselConfiguration::where('key', $def['key'])->exists();

            if (! $exists) {
                CarouselConfiguration::create(array_merge([
                    'is_active' => true,
                    'sort_order' => 0,
                    'desktop_slides' => 1,
                    'tablet_slides' => 1,
                    'mobile_slides' => 1,
                    'autoplay' => true,
                    'autoplay_delay' => 8000,
                    'loop' => false,
                    'show_navigation' => true,
                    'show_pagination' => true,
                    'pause_on_hover' => true,
                    'direction' => 'rtl',
                    'transition' => 'slide',
                ], $def));
                $added++;
            }
        }

        Cache::forget('carousel-registry');

        return $added;
    }

    /**
     * Get configuration for a specific carousel by key.
     */
    public static function getConfig(string $key): ?CarouselConfiguration
    {
        $row = Cache::remember('carousel-config:'.$key, 600, function () use ($key): ?array {
            $model = CarouselConfiguration::where('key', $key)->first();

            return $model?->toArray();
        });

        if (! $row) {
            return null;
        }

        $model = new CarouselConfiguration;
        $model->forceFill($row);
        $model->exists = true;

        return $model;
    }

    /**
     * Get configuration array for frontend consumption.
     */
    public static function getConfigArray(string $key): array
    {
        $config = self::getConfig($key);

        if (! $config) {
            return self::getDefaultConfig($key);
        }

        return $config->toConfigArray();
    }

    /**
     * Get all registered carousels.
     */
    public static function all(): Collection
    {
        return CarouselConfiguration::orderBy('sort_order')->orderBy('name')->get();
    }

    /**
     * Get default config for an unknown carousel key.
     */
    private static function getDefaultConfig(string $key): array
    {
        foreach (self::$discovered as $def) {
            if ($def['key'] === $key) {
                return array_merge([
                    'key' => $key,
                    'name' => $key,
                    'title' => null,
                    'subtitle' => null,
                    'type' => 'hero',
                    'is_active' => true,
                    'desktop_slides' => 1,
                    'tablet_slides' => 1,
                    'mobile_slides' => 1,
                    'autoplay' => true,
                    'autoplay_delay' => 8000,
                    'loop' => false,
                    'show_navigation' => true,
                    'show_pagination' => true,
                    'pause_on_hover' => true,
                    'direction' => 'rtl',
                    'transition' => 'slide',
                ], $def);
            }
        }

        return [
            'key' => $key,
            'name' => $key,
            'title' => null,
            'subtitle' => null,
            'type' => 'hero',
            'is_active' => true,
            'desktop_slides' => 1,
            'tablet_slides' => 1,
            'mobile_slides' => 1,
            'autoplay' => true,
            'autoplay_delay' => 8000,
            'loop' => false,
            'show_navigation' => true,
            'show_pagination' => true,
            'pause_on_hover' => true,
            'direction' => 'rtl',
            'transition' => 'slide',
        ];
    }

    /**
     * Clear all cached carousel configurations.
     */
    public static function clearCache(): void
    {
        Cache::forget('carousel-registry');
        foreach (self::$discovered as $def) {
            Cache::forget('carousel-config:'.$def['key']);
        }
        // Also clear any dynamically cached keys
        $keys = CarouselConfiguration::pluck('key');
        foreach ($keys as $key) {
            Cache::forget('carousel-config:'.$key);
        }
    }
}
