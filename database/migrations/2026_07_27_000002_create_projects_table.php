<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->string('name_ar');
            $table->string('name_en')->nullable();
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('project_status')->default('planned');
            $table->string('status')->default('draft');
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->string('location')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('budget_currency', 3)->default('ILS');
            $table->unsignedTinyInteger('implementation_percentage')->default(0);
            $table->string('contractor')->nullable();
            $table->string('funding_entity')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->json('gallery')->nullable();
            $table->json('documents')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->bigInteger('views_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'is_public']);
            $table->index(['is_featured', 'status']);
            $table->index('project_status');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
