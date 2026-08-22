<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_model_versions', function (Blueprint $table): void {
            $table->id();
            $table->string('version');
            $table->string('status')->default('inactive');
            $table->string('path')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_model_versions');
    }
};
