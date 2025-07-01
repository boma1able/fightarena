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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('required_level')->default(1);
            $table->unsignedInteger('buy_price')->default(0);
            $table->unsignedInteger('sell_price')->default(0);
            $table->unsignedTinyInteger('min_damage')->nullable();
            $table->unsignedTinyInteger('max_damage')->nullable();
            $table->json('defense_by_zone')->nullable();
            $table->enum('type', [
                'sword', 'axe', 'mace', 'knife',
                'armor', 'helmet', 'shield', 'arms', 'legs', 'boots',
                'ring', 'earrings','neckless'])->nullable();
            $table->string('slot')->nullable();
            $table->json('bonuses')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('base_max_durability')->default(20);
            $table->boolean('is_shop')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
