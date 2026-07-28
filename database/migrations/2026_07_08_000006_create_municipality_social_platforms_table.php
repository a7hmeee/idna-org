<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_social_platforms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('icon', 100);
            $table->string('url', 500);
            $table->string('color', 50)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['municipality_id', 'slug']);
            $table->index(['municipality_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_social_platforms');
    }
};
