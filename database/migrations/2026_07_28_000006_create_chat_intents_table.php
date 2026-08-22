<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_intents', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('label_ar');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('minimum_confidence', 5, 4)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_intents');
    }
};
