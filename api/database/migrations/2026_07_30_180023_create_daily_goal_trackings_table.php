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
        Schema::create('daily_goal_trackings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('goal_date');
            $table->integer('target_vocab');
            $table->integer('target_quizzes');
            $table->integer('target_xp');
            $table->integer('current_vocab')->default(0);
            $table->integer('current_quizzes')->default(0);
            $table->integer('current_xp')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            
            // Explicit foreign key index (Finding 1)
            $table->index('user_id', 'idx_daily_goal_user_id');
            
            // Optimized index for queries (Finding 1)
            $table->index(['user_id', 'goal_date'], 'idx_daily_goal_date');
            
            // Idempotency constraint (Finding 2)
            $table->unique(['user_id', 'goal_date'], 'unq_daily_goal_tracking');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_goal_trackings');
    }
};
