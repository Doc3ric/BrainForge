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
        Schema::create('vocabulary_study_session_words', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('study_session_id')->constrained('vocabulary_study_sessions')->cascadeOnDelete();
            $table->foreignUuid('vocabulary_word_id')->constrained('vocabulary_words')->restrictOnDelete();
            $table->timestampTz('studied_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary_study_session_words');
    }
};
