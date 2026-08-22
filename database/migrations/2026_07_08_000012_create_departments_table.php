<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        try {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropForeign('users_department_id_foreign');
            });
        } catch (Throwable) {
            // Foreign key may not exist; safe to ignore
        }

        Schema::dropIfExists('departments');

        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('manager_name')->nullable();
            $table->string('manager_position')->nullable();
            $table->string('phone')->nullable();
            $table->string('extension')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('office_location')->nullable();
            $table->string('working_hours')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->string('status')->default('active');
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('display_order');
            $table->index('is_public');
            $table->index('is_featured');
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        try {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropForeign('users_department_id_foreign');
            });
        } catch (Throwable) {
            // Foreign key may not exist; safe to ignore
        }

        Schema::dropIfExists('departments');
    }
};
