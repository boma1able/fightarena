<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Monster;
use App\Services\ItemBonusService;

class DropService
{
    public static function generateDrop(float $chancePercent, int $level): ?Item
    {
        if (random_int(1, 100) > $chancePercent) {
            return null;
        }

        $item = Item::query()
            ->where('is_shop', true)
            ->inRandomOrder()
            //->where('slot', 'weapon') ///////////////////////////////// temp
            ->first();

        if (!$item) {
            return null;
        }

        $item = clone $item;

        if ($item->slot === 'weapon') {
            $damage = self::generateWeaponDamage($item->type, $level);
            $item->min_damage = $damage['min'];
            $item->max_damage = $damage['max'];
        }

        if (in_array($item->slot, ['helmet', 'armor', 'legs', 'arms', 'boots'])) {
            $item->defense_by_zone = self::generateArmorDefense($item->type, $level);
        }

        $item->level = $level;
        // Тут виклик сервісу генерації бонусів
        $rarity = self::pickRarity();

        $item->rarity = $rarity;

        $item->bonuses = ItemBonusService::generate($rarity, $item->slot, $level);

        \Log::info('Generated item', [
            'id' => $item->id,
            'slot' => $item->slot,
            'type' => $item->type,
            'defense_by_zone' => $item->defense_by_zone,
        ]);

        return $item;
    }

    public static function pickRarity(): string
    {
        $rarityChances = [
            'common' => 60,
            'uncommon' => 25,
            'rare' => 10,
            'legendary' => 5,
        ];

        $rand = random_int(1, 100);
        $cumulative = 0;

        foreach ($rarityChances as $rarity => $chance) {
            $cumulative += $chance;
            if ($rand <= $cumulative) {
                return $rarity;
            }
        }

        return 'common';
    }

    public static function generateWeaponDamage(string $type, int $level): array
    {
        $ranges = config("weapon_damage.$type");

        if (!$ranges) {
            return ['min' => 1, 'max' => 1];
        }

        foreach ($ranges as $range) {
            if ($level >= $range['level_min'] && $level <= $range['level_max']) {
                $min = rand($range['min_range'][0], $range['min_range'][1]);
                $max = rand($range['max_range'][0], $range['max_range'][1]);
                return [
                    'min' => min($min, $max),
                    'max' => max($min, $max),
                ];
            }
        }

        // fallback на останній рівень
        $last = end($ranges);
        if ($valueRange[0] == $valueRange[1]) {
            $min = $max = $valueRange[0];
        } else {
            $min = rand($valueRange[0], $valueRange[1] - 1);
            $max = rand($min + 1, $valueRange[1]);
        }

        return [
            'min' => min($min, $max),
            'max' => max($min, $max),
        ];
    }

    public static function generateArmorDefense(string $type, int $level): array
    {
        $ranges = config("armor_defense.$type");

        if (!$ranges) {
            return [];
        }

        foreach ($ranges as $range) {
            if ($level >= $range['level_min'] && $level <= $range['level_max']) {
                $defense = [];

                foreach ($range['zones'] as $zone => $valueRange) {
                    // Генеруємо окремо мін і макс для кожної зони
                    $min = rand($valueRange[0], $valueRange[1]);
                    $max = rand($min, $valueRange[1]); // щоб max >= min

                    $defense[$zone] = [
                        'min' => $min,
                        'max' => $max,
                    ];
                }

                return $defense;
            }
        }

        // fallback на останній діапазон
        $last = end($ranges);
        $defense = [];

        foreach ($last['zones'] as $zone => $valueRange) {
            $min = rand($valueRange[0], $valueRange[1]);
            $max = rand($min, $valueRange[1]);

            $defense[$zone] = [
                'min' => $min,
                'max' => $max,
            ];
        }

        return $defense;
    }


    public static function generateForMonster(Monster $monster): ?Item
    {
        return self::generateDrop(100, $monster->level);
    }
}
