<?php

declare(strict_types=1);

use App\Domains\Homepage\Models\HomepageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Enable latest_news section (was disabled by default)
        HomepageSection::updateOrCreate(
            ['key' => 'latest_news'],
            [
                'title' => 'آخر الأخبار',
                'subtitle' => 'أحدث الأخبار والبيانات الصادرة عن البلدية.',
                'is_enabled' => true,
                'sort_order' => 9,
                'items_limit' => 4,
            ]
        );

        // Add projects section
        HomepageSection::updateOrCreate(
            ['key' => 'projects'],
            [
                'title' => 'المشاريع',
                'subtitle' => 'تعرف على مشاريع البلدية الجاري تنفيذها والمخطط لها.',
                'is_enabled' => true,
                'sort_order' => 10,
                'items_limit' => 3,
            ]
        );

        // Add tenders section
        HomepageSection::updateOrCreate(
            ['key' => 'tenders'],
            [
                'title' => 'المناقصات',
                'subtitle' => 'المناقصات والعطاءات المطروحة.',
                'is_enabled' => true,
                'sort_order' => 11,
                'items_limit' => 4,
            ]
        );
    }

    public function down(): void
    {
        HomepageSection::whereIn('key', ['latest_news', 'projects', 'tenders'])->delete();
    }
};
