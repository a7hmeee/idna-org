<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenders', function (Blueprint $table): void {
            $table->id();

            $table->string('tender_number')->nullable();
            $table->string('title_ar');
            $table->string('title_en')->nullable();
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('category')->nullable();
            $table->string('issuing_department')->nullable();
            $table->date('publication_date')->nullable();
            $table->date('submission_deadline')->nullable();
            $table->date('opening_date')->nullable();
            $table->string('status')->default('draft');

            $table->json('eligibility_requirements')->nullable();
            $table->json('application_instructions')->nullable();
            $table->text('contact_info')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->json('tender_documents')->nullable();
            $table->json('result_documents')->nullable();
            $table->string('budget')->nullable();
            $table->string('budget_currency', 10)->default('ILS');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(false);
            $table->integer('display_order')->default(0);
            $table->integer('views_count')->default(0);

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('is_public');
            $table->index('is_featured');
            $table->index('submission_deadline');
            $table->index('publication_date');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenders');
    }
};
