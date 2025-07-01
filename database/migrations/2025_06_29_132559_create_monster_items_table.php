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
        Schema::create('monster_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monster_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->unsignedTinyInteger('current_durability')->default(10);
            $table->unsignedTinyInteger('max_durability')->default(10);
            $table->boolean('is_broken')->default(false);
            $table->string('slot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monster_items');
    }
};
