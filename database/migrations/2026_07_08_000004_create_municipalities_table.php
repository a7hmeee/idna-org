<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipalities', function (Blueprint $table): void {
            $table->id();
            $table->string('name_ar', 255);
            $table->string('name_en', 255);
            $table->text('short_description')->nullable();
            $table->text('full_description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->jsonb('objectives')->nullable();
            $table->date('foundation_date')->nullable();
            $table->unsignedBigInteger('population')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->string('municipality_code', 50)->nullable()->unique();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipalities');
    }
};
