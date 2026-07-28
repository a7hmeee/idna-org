<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('municipality_contacts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('municipality_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50);
            $table->string('label', 255);
            $table->text('value')->nullable();
            $table->string('icon', 100)->nullable();
            $table->string('url', 500)->nullable();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['municipality_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipality_contacts');
    }
};
