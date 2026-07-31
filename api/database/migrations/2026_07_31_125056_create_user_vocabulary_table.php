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
        Schema::create('user_vocabulary', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('vocabulary_word_id')->constrained('vocabulary_words')->restrictOnDelete();
            $table->boolean('is_learned')->default(false);
            $table->decimal('ease_factor', 4, 2)->default(2.50);
            $table->integer('interval_days')->default(1);
            $table->integer('repetition_count')->default(0);
            $table->timestampTz('next_review_at')->nullable();
            $table->timestampTz('last_interacted_at')->nullable();
            $table->timestampsTz();

            $table->unique(['user_id', 'vocabulary_word_id'], 'unq_user_vocabulary');
            $table->index(['user_id', 'next_review_at'], 'idx_user_vocab_next_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vocabulary');
    }
};
