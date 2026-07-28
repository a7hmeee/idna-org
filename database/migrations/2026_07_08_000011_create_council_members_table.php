<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('council_members', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('slug')->unique();
            $table->string('national_number')->nullable();
            $table->string('position');
            $table->string('qualification')->nullable();
            $table->string('profession')->nullable();
            $table->longText('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->date('term_start');
            $table->date('term_end')->nullable();
            $table->unsignedSmallInteger('years_of_experience')->nullable();
            $table->string('committee')->nullable();
            $table->string('status');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_public')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'position']);
            $table->index('slug');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('council_members');
    }
};
