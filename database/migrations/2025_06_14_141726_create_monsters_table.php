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
        Schema::create('monsters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('base_health')->default(20);
            $table->integer('current_health')->default(20);
            $table->integer('strength')->default(3);
            $table->integer('agility')->default(3);
            $table->integer('intuition')->default(3);
            $table->integer('endurance')->default(3);
            $table->integer('level')->default(0);
            $table->integer('gold')->default(0);
            $table->boolean('is_temporary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monsters');
    }
};
