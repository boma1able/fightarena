<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Monster;
use App\Services\ItemBonusService;

class DropService
{
    /**
     * Генерує дроп предмета з шансом.
     *
     * @param float $chancePercent Шанс у відсотках (0-100)
     * @param int $level Рівень (для бонусів)
     * @return Item|null
     */
    public static function generateDrop(float $chancePercent, int $level): ?Item
    {
        if (random_int(1, 100) > $chancePercent) {
            return null;
        }

        $item = Item::query()
            ->where('is_shop', true)
            ->inRandomOrder()
            ->where('slot', 'weapon') ///////////////////////////////// temp
            ->first();

        if (!$item) {
            return null;
        }

        if ($item->slot === 'weapon') {
            $damage = self::generateWeaponDamage($item->type, $level);
            $item->min_damage = $damage['min'];
            $item->max_damage = $damage['max'];
        }

        $item = clone $item;
        $item->level = $level;
        // Тут виклик сервісу генерації бонусів
        $rarity = self::pickRarity();

        $item->rarity = $rarity;

        $item->bonuses = ItemBonusService::generate($rarity, $item->slot, $level);

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
        // dd(config("weapon_damage.$type"));
        // dd(config('weapon_damage'));
        $ranges = config("weapon_damage.$type");

        if (!$ranges) {
            return ['min' => 1, 'max' => 1]; // дефолт, якщо конфігу нема
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
        $min = rand($last['min_range'][0], $last['min_range'][1]);
        $max = rand($last['max_range'][0], $last['max_range'][1]);

        return [
            'min' => min($min, $max),
            'max' => max($min, $max),
        ];
    }


    public static function generateForMonster(Monster $monster): ?Item
    {
        return self::generateDrop(100, $monster->level);
    }
}
