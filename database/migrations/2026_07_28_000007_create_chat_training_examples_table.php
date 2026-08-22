<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chat_training_examples')) {
            Schema::create('chat_training_examples', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('chat_intent_id')->constrained('chat_intents')->cascadeOnDelete();
                $table->text('text');
                $table->text('normalized_text');
                $table->string('source')->default('seed');
                $table->string('locale', 10)->default('ar');
                $table->boolean('is_active')->default(true);
                $table->boolean('is_verified')->default(true);
                $table->decimal('weight', 5, 2)->default(1.00);
                $table->text('notes')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['chat_intent_id', 'is_active', 'is_verified']);
                $table->index('locale');
                $table->index('source');
                $table->unique(['normalized_text', 'chat_intent_id'], 'unique_active_example');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_training_examples');
    }
};
