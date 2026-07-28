<?php

declare(strict_types=1);

use App\Domains\News\Enums\NewsCategory;
use App\Domains\News\Enums\NewsStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_items', function (Blueprint $table): void {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->default(NewsCategory::General->value);
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('mobile_image_path')->nullable();
            $table->json('gallery')->nullable();
            $table->string('author')->nullable();
            $table->string('status')->default(NewsStatus::Draft->value);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->timestamp('publish_at')->nullable();
            $table->bigInteger('views_count')->unsigned()->default(0);
            $table->integer('display_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_items');
    }
};
