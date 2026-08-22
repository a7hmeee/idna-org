<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chatbot_dataset_versions', function (Blueprint $table): void {
            $table->id();
            $table->string('version_tag', 50);
            $table->text('description')->nullable();
            $table->string('fingerprint', 64);
            $table->unsignedInteger('example_count')->default(0);
            $table->unsignedSmallInteger('intent_count')->default(0);
            $table->json('intent_distribution')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('model_version_id')->nullable()->constrained('chatbot_model_versions')->nullOnDelete();
            $table->boolean('is_baseline')->default(false);
            $table->string('export_path', 500)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['fingerprint']);
            $table->index(['is_baseline']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbot_dataset_versions');
    }
};
