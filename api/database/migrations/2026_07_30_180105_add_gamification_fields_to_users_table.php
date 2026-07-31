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
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone', 100)->nullable();
            $table->string('theme', 20)->default('system');
            $table->bigInteger('total_xp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            $table->integer('daily_target_vocab')->default(10);
            $table->integer('daily_target_quizzes')->default(2);
            $table->integer('daily_target_xp')->default(50);
            $table->date('last_streak_increment_at')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'timezone', 'theme', 'total_xp', 'level', 
                'current_streak', 'longest_streak', 
                'daily_target_vocab', 'daily_target_quizzes', 'daily_target_xp',
                'last_streak_increment_at'
            ]);
		});
    }
};
