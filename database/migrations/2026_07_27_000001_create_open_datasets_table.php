<?php

declare(strict_types=1);

use App\Domains\OpenData\Enums\OpenDataStatus;
use App\Domains\OpenData\Enums\OpenDataType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_datasets', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default(OpenDataType::Dataset->value);
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_format')->nullable();
            $table->string('external_url')->nullable();
            $table->string('status')->default(OpenDataStatus::Draft->value);
            $table->boolean('is_featured')->default(false);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index('category');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_datasets');
    }
};
