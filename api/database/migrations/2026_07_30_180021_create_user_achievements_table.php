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
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('achievement_id')->constrained('achievements')->cascadeOnDelete();
            $table->timestampTz('unlocked_at')->useCurrent();
            
            // Explicit foreign key indexes (Finding 1)
            $table->index('user_id', 'idx_usr_achv_user_id');
            $table->index('achievement_id', 'idx_usr_achv_achv_id');
            
            // Idempotency constraint (Finding 2 / already existed but explicitly verified here)
            $table->unique(['user_id', 'achievement_id'], 'unq_user_achievement');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
