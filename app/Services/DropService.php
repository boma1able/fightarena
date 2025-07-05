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
            ->first();

        if (!$item) {
            return null;
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

    public static function generateForMonster(Monster $monster): ?Item
    {
        return self::generateDrop(100, $monster->level);
    }
}
