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
            'common' => 0.5,
            'uncommon' => 0.75,
            'rare' => 1.0,
            'legendary' => 1.5,
            default => 0.5,
        };

        return ceil(($bonusCount * 15 + $power * 8 + $level * 3) * $rarityMultiplier);
    }

}
