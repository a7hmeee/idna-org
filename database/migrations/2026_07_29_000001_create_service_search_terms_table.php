<?php

declare(strict_types=1);

use App\Domains\ElectronicServices\Models\ElectronicService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_search_terms', function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(ElectronicService::class)->constrained()->cascadeOnDelete();
            $table->string('term');
            $table->string('normalized_term');
            $table->string('type'); // alias, keyword, phrase, citizen_expression
            $table->unsignedInteger('weight')->default(10);
            $table->unsignedInteger('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['electronic_service_id', 'type']);
            $table->index('normalized_term');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_search_terms');
    }
};
