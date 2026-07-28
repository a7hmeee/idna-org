<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_custom_fields', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('key', 255);
            $table->text('value');
            $table->string('type', 50);
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['municipality_id', 'key']);
            $table->index(['municipality_id', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_custom_fields');
    }
};
