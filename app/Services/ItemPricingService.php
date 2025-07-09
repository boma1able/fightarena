<?php

namespace App\Services;

class ItemPricingService
{
    public static function calculate(array $bonuses, string $rarity, int $level): int
    {
        // Ваги кожного бонусу
        $statWeights = [
            'strength' => 1.0,
            'agility' => 1.0,
            'intuition' => 1.0,
            'endurance' => 1.0,
        ];

        $bonusCount = count($bonuses);
        $power = 0;

        foreach ($bonuses as $stat => $value) {
            $weight = $statWeights[$stat] ?? 1.0;
            $power += $value * $weight;
        }

        $rarityMultiplier = match ($rarity) {
            'common' => 1.0,
            'uncommon' => 1.3,
            'rare' => 1.7,
            'legendary' => 2.5,
            default => 1.0,
        };

        return ceil(($bonusCount * 15 + $power * 8 + $level * 3) * $rarityMultiplier);
    }
}
