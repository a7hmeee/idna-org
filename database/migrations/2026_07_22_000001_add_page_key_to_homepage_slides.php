<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homepage_slides', function (Blueprint $table): void {
            $table->string('page_key')->default('home')->after('id');

            $table->index(['page_key', 'is_active', 'sort_order'], 'homepage_slides_page_active_order_idx');
        });

        DB::table('homepage_slides')->whereNull('page_key')->update(['page_key' => 'home']);
    }

    public function down(): void
    {
        Schema::table('homepage_slides', function (Blueprint $table): void {
            $table->dropIndex('homepage_slides_page_active_order_idx');
            $table->dropColumn('page_key');
        });
    }
};
