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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('base_health')->default(0);
            $table->integer('current_health')->default(0);
            $table->timestamp('health_regeneration_started_at')->nullable();
            $table->boolean('is_in_battle')->default(false);
            $table->integer('strength')->default(3);
            $table->integer('agility')->default(3);
            $table->integer('intuition')->default(3);
            $table->integer('endurance')->default(3);
            $table->integer('stat_points')->default(3);
            $table->integer('level')->default(0);
            $table->unsignedBigInteger('experience')->default(0);
            $table->decimal('gold', 10, 2)->default(0);
            $table->unsignedInteger('wins')->default(0);
            $table->unsignedInteger('losses')->default(0);
            $table->unsignedInteger('draws')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
