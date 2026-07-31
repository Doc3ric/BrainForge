<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vocabulary_review_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_vocabulary_id')->constrained('user_vocabulary')->cascadeOnDelete();
            $table->uuid('idempotency_key')->unique();
            $table->integer('quality_score'); // 0-5
            
            $table->integer('old_interval_days');
            $table->integer('new_interval_days');
            $table->decimal('old_ease_factor', 4, 2);
            $table->decimal('new_ease_factor', 4, 2);
            $table->integer('old_repetition_count');
            $table->integer('new_repetition_count');
            
            $table->timestampTz('reviewed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary_review_logs');
    }
};
