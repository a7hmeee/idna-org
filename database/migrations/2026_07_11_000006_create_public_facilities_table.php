<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_facilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_category_id')->nullable()->constrained('facility_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('description');
            $table->string('cover_image_path')->nullable();
            $table->json('gallery')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('working_hours')->nullable();
            $table->json('services')->nullable();
            $table->json('features')->nullable();
            $table->json('rules')->nullable();
            $table->string('status')->default('draft');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_public']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_facilities');
    }
};
