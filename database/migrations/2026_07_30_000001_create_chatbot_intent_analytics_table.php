<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_intent_analytics', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('conversation_id')->nullable()->constrained('chatbot_conversations')->cascadeOnDelete();
            $table->foreignId('message_id')->nullable()->constrained('chatbot_messages')->cascadeOnDelete();
            $table->string('predicted_intent', 100);
            $table->string('final_intent', 100);
            $table->decimal('confidence', 5, 4)->default(0);
            $table->string('prediction_source', 50)->default('rule');
            $table->string('handler_used', 150)->nullable();
            $table->unsignedInteger('execution_time_ms')->default(0);
            $table->boolean('clarification_happened')->default(false);
            $table->boolean('is_unknown')->default(false);
            $table->timestamps();

            $table->index(['conversation_id', 'predicted_intent']);
            $table->index(['final_intent', 'created_at']);
            $table->index(['is_unknown', 'created_at']);
            $table->index(['prediction_source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_intent_analytics');
    }
};
