<?php

namespace App\Services;

class ItemBonusService
{
    public static function generate(string $rarity, string $slot, int $level): array
    {
        $availableStats = match ($slot) {
            'helmet', 'armor', 'legs', 'boots', 'shield'    => ['strength', 'agility', 'intuition', 'endurance'],
            'weapon'                                        => ['strength', 'agility', 'intuition', 'endurance'],
            'neckless', 'ring1', 'ring2'                    => ['strength', 'agility', 'intuition', 'endurance'],

            default => ['strength', 'agility', 'intuition', 'endurance'],
        };

        $bonusRanges = [
            'common' => [1, 1],
            'uncommon' => [1, 2],
            'rare' => [2, 3],
            'legendary' => [3, 4],
        ];

        $range = $bonusRanges[$rarity] ?? [1, 1];
        $statCount = rand($range[0], $range[1]);

        $bonuses = [];
        $usedStats = [];

        while (count($bonuses) < $statCount && count($usedStats) < count($availableStats)) {
            $stat = $availableStats[array_rand($availableStats)];

            if (in_array($stat, $usedStats)) {
                continue;
            }

            $usedStats[] = $stat;

            $maxBonus = max(1, intdiv($level, 2)); // гарантуємо хоча б +1
            $bonuses[$stat] = rand(1, $maxBonus);
        }

        return $bonuses;
    }
}
