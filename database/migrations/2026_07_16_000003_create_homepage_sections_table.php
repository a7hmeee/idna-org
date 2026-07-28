<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedInteger('items_limit')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index('is_enabled');
            $table->index('sort_order');
        });

        $this->seedDefaultSections();
    }

    private function seedDefaultSections(): void
    {
        $sections = [
            ['key' => 'hero', 'title' => 'الشريط الرئيسي', 'sort_order' => 1],
            ['key' => 'quick_links', 'title' => 'الروابط السريعة', 'sort_order' => 2],
            ['key' => 'municipality_intro', 'title' => 'نبذة عن البلدية', 'sort_order' => 3],
            ['key' => 'statistics', 'title' => 'الإحصائيات', 'sort_order' => 4],
            ['key' => 'services', 'title' => 'الخدمات الإلكترونية', 'sort_order' => 5],
            ['key' => 'departments', 'title' => 'الدوائر', 'sort_order' => 6],
            ['key' => 'council_members', 'title' => 'أعضاء المجلس البلدي', 'sort_order' => 7],
            ['key' => 'council_decisions', 'title' => 'قرارات المجلس البلدي', 'sort_order' => 8],
            ['key' => 'engineering_offices', 'title' => 'المكاتب الهندسية', 'sort_order' => 9],
            ['key' => 'latest_news', 'title' => 'آخر الأخبار', 'sort_order' => 10],
            ['key' => 'projects', 'title' => 'المشاريع', 'sort_order' => 11],
            ['key' => 'announcements', 'title' => 'الإعلانات', 'sort_order' => 12],
            ['key' => 'contact_cta', 'title' => 'دعوة للتواصل', 'sort_order' => 13],
        ];

        DB::table('homepage_sections')->insert($sections);
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
