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
        Schema::create('xp_activity_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type_key', 100)->unique();
            $table->string('display_name', 100);
            $table->integer('default_xp_amount');
            $table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xp_activity_types');
    }
};
