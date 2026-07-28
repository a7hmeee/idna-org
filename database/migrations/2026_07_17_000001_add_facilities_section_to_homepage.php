<?php

declare(strict_types=1);

use App\Domains\Homepage\Models\HomepageSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Shift sections that come after departments (sort_order >= 7)
        HomepageSection::where('sort_order', '>=', 7)
            ->where('key', '!=', 'facilities')
            ->orderBy('sort_order', 'desc')
            ->each(function (HomepageSection $section): void {
                $section->increment('sort_order');
            });

        // Insert facilities section at sort_order 7
        HomepageSection::updateOrCreate(
            ['key' => 'facilities'],
            [
                'title' => 'المرافق العامة',
                'subtitle' => 'تعرف على المرافق العامة التي تديرها البلدية والخدمات التي تقدمها للمواطنين.',
                'is_enabled' => true,
                'sort_order' => 7,
                'items_limit' => 4,
            ]
        );
    }

    public function down(): void
    {
        HomepageSection::where('key', 'facilities')->delete();

        // Shift sections back
        HomepageSection::where('sort_order', '>', 7)
            ->orderBy('sort_order')
            ->each(function (HomepageSection $section): void {
                $section->decrement('sort_order');
            });
    }
};
