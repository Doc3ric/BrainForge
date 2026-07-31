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
        Schema::create('vocabulary_words', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('category_id')->constrained('vocabulary_categories')->cascadeOnDelete();
            $table->foreignUuid('difficulty_id')->constrained('difficulty_levels')->restrictOnDelete();
            $table->string('word', 100)->index();
            $table->string('part_of_speech', 50);
            $table->text('definition');
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary_words');
    }
};
