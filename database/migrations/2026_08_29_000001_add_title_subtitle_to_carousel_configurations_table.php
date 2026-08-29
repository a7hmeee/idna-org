<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousel_configurations', function (Blueprint $table): void {
            $table->string('title', 255)->nullable()->after('name');
            $table->string('subtitle', 500)->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('carousel_configurations', function (Blueprint $table): void {
            $table->dropColumn(['title', 'subtitle']);
        });
    }
};
