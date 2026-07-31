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
        Schema::create('xp_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('activity_type_id')->constrained('xp_activity_types')->restrictOnDelete();
            $table->integer('amount');
            $table->nullableUuidMorphs('source'); // source_type, source_id
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            
            // Explicit foreign key indexes (Finding 1)
            $table->index('user_id', 'idx_xp_logs_user_id_fk');
            $table->index('activity_type_id', 'idx_xp_logs_activity_type_id_fk');
            
            // Query optimized composite index (Finding 1)
            $table->index(['user_id', 'created_at'], 'idx_xp_logs_user_created');
            
            // Idempotency composite unique index (Finding 2)
            $table->unique(['user_id', 'activity_type_id', 'source_type', 'source_id'], 'unq_xp_logs_idempotency');
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_logs');
    }
};
