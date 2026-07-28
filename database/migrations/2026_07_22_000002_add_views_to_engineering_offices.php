<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('engineering_offices', function (Blueprint $table): void {
            $table->unsignedBigInteger('views')->default(0)->after('sort_order');
            $table->boolean('is_featured')->default(false)->after('is_public');
        });
    }

    public function down(): void
    {
        Schema::table('engineering_offices', function (Blueprint $table): void {
            $table->dropColumn('views');
        });
    }
};