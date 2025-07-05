<?php

namespace App\Helpers;

class ItemBonusGenerator
{
    protected static array $statPool = ['strength', 'agility', 'intuition', 'endurance'];

    protected static array $bonusRanges = [
        'common' => [4, 5],
        'uncommon' => [4, 5],
        'rare' => [4, 5],
        'legendary' => [3, 4],
    ];

    public static function generate(string $rarity, string $slot, int $level): array
    {
        $availableStats = match ($slot) {
            'helmet', 'armor', 'legs', 'boots', 'shield' => ['endurance', 'strength'],
            'weapon' => ['strength', 'agility'],
            'neckless', 'ring1', 'ring2' => ['intuition', 'agility'],
            default => ['strength', 'agility', 'intuition', 'endurance'],
        };

        $range = self::$bonusRanges[$rarity] ?? [1, 2];
        $statCount = rand($range[0], $range[1]);

        $bonuses = [];

        for ($i = 0; $i < $statCount; $i++) {
            $stat = $availableStats[array_rand($availableStats)];

            // Якщо така стата вже є — додаємо до існуючої, інакше створюємо нову
            if (isset($bonuses[$stat])) {
                $bonuses[$stat] += rand(1, max(1, intdiv($level, 2) + 1));
            } else {
                $bonuses[$stat] = rand(1, max(1, intdiv($level, 2) + 1));
            }
        }

        return $bonuses;
    }

}
