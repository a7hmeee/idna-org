<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('water_area_id')->constrained('water_areas')->cascadeOnDelete();
            $table->date('schedule_date');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('status')->default('available');
            $table->text('notes')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_public')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['water_area_id', 'schedule_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_schedules');
    }
};
