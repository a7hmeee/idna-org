<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_type_labels', function (Blueprint $table): void {
            $table->id();
            $table->string('type_key')->unique()->comment('e.g. full_time, part_time, contract');
            $table->string('label_ar');
            $table->string('label_en')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_type_labels');
    }
};
