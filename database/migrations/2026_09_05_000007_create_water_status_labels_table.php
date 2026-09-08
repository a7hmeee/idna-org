<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_status_labels', function (Blueprint $table): void {
            $table->id();
            $table->string('status_key')->unique()->comment('e.g. available, low_pressure, maintenance');
            $table->string('label_ar');
            $table->string('label_en')->nullable();
            $table->string('color')->default('#176B32');
            $table->string('bg_color')->default('#EAF5EE');
            $table->string('dot_color')->default('#176B32');
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_status_labels');
    }
};
