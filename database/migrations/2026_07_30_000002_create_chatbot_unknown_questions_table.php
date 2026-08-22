<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_unknown_questions', function (Blueprint $table): void {
            $table->id();
            $table->text('question');
            $table->string('normalized_question', 500);
            $table->foreignId('conversation_id')->nullable()->constrained('chatbot_conversations')->nullOnDelete();
            $table->string('detected_intent', 100)->nullable();
            $table->decimal('prediction_confidence', 5, 4)->nullable();
            $table->string('suggested_domain', 100)->nullable();
            $table->unsignedInteger('occurrence_count')->default(1);
            $table->timestamp('last_seen_at')->nullable();
            $table->string('admin_status', 20)->default('new');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['admin_status', 'created_at']);
            $table->index(['occurrence_count']);
            $table->index(['last_seen_at']);
            $table->unique(['normalized_question'], 'unique_normalized_unknown_question');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_unknown_questions');
    }
};
