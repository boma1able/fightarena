<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{

    public function run(): void
    {
        Item::create([
            'key' => 'sharp_tooth',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 15,
            'sell_price' => 5,
            'bonuses' => ['agility' => 1],
            'min_damage' => 1,
            'max_damage' => 2,
            'type' => 'knife',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/knifes/knife-1-art.png',
            'is_shop' => true,
            'debuffs' => [
                ['key' => 'bleeding', 'chance' => 100, 'duration' => 3],
            ],
        ]);

        Item::create([
            'key' => 'butcher_apprentice_axe',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 24,
            'sell_price' => 6,
            'bonuses' => ['strength' => 1],
            'min_damage' => 1,
            'max_damage' => 4,
            'type' => 'axe',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/axe/axe-1-art.png',
            'is_shop' => true,
            'debuffs' => [
                ['key' => 'damage_reduction', 'chance' => 100, 'duration' => 3],
            ],
        ]);

        Item::create([
            'key' => 'rusty_sword',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 20,
            'sell_price' => 7,
            'bonuses' => ['intuition' => 1],
            'min_damage' => 2,
            'max_damage' => 3,
            'type' => 'sword',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/swords/sword-1-art.png',
            'is_shop' => true,
            'debuffs' => [
                ['key' => 'armor_reduction', 'chance' => 100, 'duration' => 3],
            ],
        ]);

        Item::create([
            'key' => 'cracked_mace',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 22,
            'sell_price' => 8,
            'bonuses' => ['endurance' => 1],
            'min_damage' => 1,
            'max_damage' => 5,
            'type' => 'mace',
            'slot' => 'weapon',
            'rarity' => 'common',
            'image' => '/images/items/maces/mace-1-art.png',
            'is_shop' => true,
            'debuffs' => [
                ['key' => 'stun', 'chance' => 100, 'duration' => 1],
            ],
        ]);

        Item::create([
            'key' => 'leather_spirit',
            'required_level' => 1,
            'defense_by_zone' => [
                'chest' => ['min' => 1, 'max' => 4],
                'belly' => ['min' => 1, 'max' => 2]
            ],
            'buy_price' => 12,
            'sell_price' => 4,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'armor',
            'slot' => 'armor',
            'rarity' => 'common',
            'image' => '/images/items/torso/torso-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'metal_helmet',
            'required_level' => 1,
            'defense_by_zone' => [
                'head' => ['min' => 1, 'max' => 4],
            ],
            'buy_price' => 10,
            'sell_price' => 3,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'helmet',
            'slot' => 'helmet',
            'rarity' => 'common',
            'image' => '/images/items/helmet/helmet-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'beginner_amulet',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 7,
            'sell_price' => 2,
            'bonuses' => ['luck' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'neckless',
            'slot' => 'neckless',
            'rarity' => 'common',
            'image' => '/images/items/neckless/neckless-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'apprentice_ring',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 5,
            'sell_price' => 1,
            'bonuses' => ['agility' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'ring',
            'slot' => 'ring',
            'rarity' => 'common',
            'image' => '/images/items/ring/ring-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'worker_gloves',
            'required_level' => 1,
            'defense_by_zone' => [
                'belly' => ['min' => 1, 'max' => 3],
            ],
            'buy_price' => 6,
            'sell_price' => 2,
            'bonuses' => ['strength' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'arms',
            'slot' => 'arms',
            'rarity' => 'common',
            'image' => '/images/items/arms/arms-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'wooden_shield',
            'required_level' => 1,
            'defense_by_zone' => [],
            'buy_price' => 26,
            'sell_price' => 20,
            'bonuses' => ['block' => 2],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'shield',
            'slot' => 'shield',
            'rarity' => 'common',
            'image' => '/images/items/shield/shield-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'apprentice_pants',
            'required_level' => 1,
            'defense_by_zone' => [
                'belt' => ['min' => 1, 'max' => 2],
                'legs' => ['min' => 2, 'max' => 4],
            ],
            'buy_price' => 9,
            'sell_price' => 3,
            'bonuses' => ['endurance' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'legs',
            'slot' => 'legs',
            'rarity' => 'common',
            'image' => '/images/items/legs/legs-1-art.png',
            'is_shop' => true,
        ]);

        Item::create([
            'key' => 'novice_boots',
            'required_level' => 1,
            'defense_by_zone' => [
                'legs' => ['min' => 1, 'max' => 4],
            ],
            'buy_price' => 7,
            'sell_price' => 2,
            'bonuses' => ['agility' => 1],
            'min_damage' => null,
            'max_damage' => null,
            'type' => 'boots',
            'slot' => 'boots',
            'rarity' => 'common',
            'image' => '/images/items/boots/boots-1-art.png',
            'is_shop' => true,
        ]);

    }

}
