<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('job_number')->nullable();
            $table->string('employment_type');
            $table->string('location');
            $table->string('salary')->nullable();
            $table->unsignedInteger('vacancies')->default(1);
            $table->text('summary');
            $table->longText('description');
            $table->json('requirements');
            $table->json('responsibilities');
            $table->json('benefits')->nullable();
            $table->json('required_documents');
            $table->string('application_method');
            $table->string('application_url')->nullable();
            $table->string('application_email')->nullable();
            $table->string('application_phone')->nullable();
            $table->string('attachment_path')->nullable();
            $table->date('publish_at');
            $table->date('closing_at');
            $table->string('status')->default('draft');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('display_order')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_public', 'publish_at', 'closing_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_offers');
    }
};
