<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_external_platforms', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('icon', 100);
            $table->string('url', 500);
            $table->string('category', 100)->nullable();
            $table->string('color', 50)->nullable();
            $table->boolean('open_in_new_tab')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['municipality_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_external_platforms');
    }
};
