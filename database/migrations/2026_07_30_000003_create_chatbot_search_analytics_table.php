<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_search_analytics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('chatbot_conversations')->nullOnDelete();
            $table->text('search_query');
            $table->string('normalized_query', 500);
            $table->unsignedBigInteger('matched_service_id')->nullable();
            $table->string('matched_service_name', 255)->nullable();
            $table->decimal('search_score', 5, 4)->nullable();
            $table->boolean('clarification_required')->default(false);
            $table->unsignedInteger('search_duration_ms')->default(0);
            $table->boolean('no_result')->default(false);
            $table->timestamps();

            $table->index(['no_result', 'created_at']);
            $table->index(['matched_service_id', 'created_at']);
            $table->index(['clarification_required']);
            $table->index(['search_duration_ms']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_search_analytics');
    }
};
