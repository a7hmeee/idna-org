<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_service_aliases', function (Blueprint $table): void {
            $table->id();
            $table->string('alias')->unique();
            $table->string('service_key');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('service_key');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_service_aliases');
    }
};
