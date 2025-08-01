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
        Schema::create('character_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('character_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedInteger('min_damage')->nullable();
            $table->unsignedInteger('max_damage')->nullable();
            $table->json('bonuses')->nullable();
            $table->json('defense_by_zone')->nullable();
            $table->string('rarity')->default('common');
            $table->unsignedTinyInteger('current_durability')->default(10);
            $table->unsignedTinyInteger('max_durability')->default(10);
            $table->boolean('is_broken')->default(false);
            $table->string('slot')->nullable();
            $table->unsignedInteger('sell_price')->nullable();
            $table->enum('location', ['inventory', 'equipped', 'shop'])->default('shop');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('character_items');
    }
};
