<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'name',
        'base_health',
        'current_health',
        'strength',
        'agility',
        'intuition',
        'endurance',
        'level',
        'gold',
        'is_temporary',
    ];

    public function getBonusesAttribute(): array
{
    $bonuses = [];

    $items = $this->equippedItems ?? collect();

    foreach ($items as $item) {
        $itemBonuses = $item->bonuses ?? [];

        if (is_string($itemBonuses)) {
            $itemBonuses = json_decode($itemBonuses, true) ?: [];
        }

        foreach ($itemBonuses as $stat => $value) {
            $bonuses[$stat] = ($bonuses[$stat] ?? 0) + $value;
        }
    }

    return $bonuses;
}


public function getTotalStrengthAttribute(): int
{
    return $this->strength + ($this->bonuses['strength'] ?? 0);
}

public function getTotalAgilityAttribute(): int
{
    return $this->agility + ($this->bonuses['agility'] ?? 0);
}

public function getTotalIntuitionAttribute(): int
{
    return $this->intuition + ($this->bonuses['intuition'] ?? 0);
}

public function getTotalEnduranceAttribute(): int
{
    return $this->endurance + ($this->bonuses['endurance'] ?? 0);
}

public function getMaxHealthAttribute(): int
{
    return $this->base_health + ($this->total_endurance * 6);
}

public function getBaseDamageAttribute(): int
{
    return (int) round($this->total_strength);
}

public function getTotalDamageRangeAttribute(): ?array
{
    // Взяв першу екіпіровану зброю (якщо її кілька, можна покращити лоґіку)
    $weapon = $this->equippedItems->first(function ($item) {
        return in_array($item->type, ['weapon', 'sword', 'axe', 'knife', 'mace']);
    });

    $base = $this->base_damage;

    if (!$weapon) {
        return ['min' => $base, 'max' => $base];
    }

    $minDamage = ($weapon->min_damage ?? 0) + $base;
    $maxDamage = ($weapon->max_damage ?? 0) + $base;

    return ['min' => $minDamage, 'max' => $maxDamage];
}

public function getCriticalDamageMultiplierAttribute(): float
{
    return round(1.5 + ($this->intuition * 0.005), 2);
}

public function getCriticalDamageRangeAttribute(): array
{
    $range = $this->totalDamageRange;

    return [
        'min' => round($range['min'] * $this->critical_damage_multiplier),
        'max' => round($range['max'] * $this->critical_damage_multiplier),
    ];
}

public function getCritChanceAttribute(): float
{
    $val = max($this->total_intuition, 0);
    return min(round(100 * ($val / ($val + 150)), 2), 60);
}

public function getAntiCritChanceAttribute(): float
{
    $val = max($this->total_endurance, 0);
    return min(round(100 * ($val / ($val + 150)), 2), 60);
}

public function getDodgeChanceAttribute(): float
{
    $val = max($this->total_agility, 0);
    return min(round(100 * ($val / ($val + 150)), 2), 75);
}

public function getAntiDodgeChanceAttribute(): float
{
    $val = max($this->total_intuition, 0);
    return min(round(100 * ($val / ($val + 250)), 2), 75);
}

public function totalDefenseByZone(): array
{
    $zones = [
        'head' => ['min' => 0, 'max' => 0],
        'chest' => ['min' => 0, 'max' => 0],
        'belly' => ['min' => 0, 'max' => 0],
        'belt' => ['min' => 0, 'max' => 0],
        'legs' => ['min' => 0, 'max' => 0],
    ];

    foreach ($this->equippedItems as $item) {
        $defenseByZone = $item->defense_by_zone ?? [];

        // Якщо defense_by_zone — json, розкодувати:
        if (is_string($defenseByZone)) {
            $defenseByZone = json_decode($defenseByZone, true) ?: [];
        }

        foreach ($defenseByZone as $zone => $values) {
            if (!isset($zones[$zone])) continue;

            $zones[$zone]['min'] += intval($values['min'] ?? 0);
            $zones[$zone]['max'] += intval($values['max'] ?? 0);
        }
    }

    return $zones;
}

public function defenseForZone(string $zone): array
{
    $zones = $this->totalDefenseByZone();

    return $zones[$zone] ?? ['min' => 0, 'max' => 0];
}

public function totalPhysicalDefense(): array
{
    $zones = $this->totalDefenseByZone();

    $totalMin = 0;
    $totalMax = 0;
    foreach ($zones as $zoneData) {
        $totalMin += $zoneData['min'] ?? 0;
        $totalMax += $zoneData['max'] ?? 0;
    }

    return ['min' => $totalMin, 'max' => $totalMax];
}

public function equippedItems()
{
    return $this->items()->wherePivot('slot', '!=', null);
}

    public function items()
    {
        return $this->belongsToMany(Item::class, 'monster_items')
            ->withPivot('slot', 'current_durability', 'max_durability', 'is_broken');
    }

    // public function getMaxDurabilityForItem(Item $item): int
    // {
    //     $baseDurability = $item->base_max_durability ?? 100;
    //     $levelFactor = 1 + ($item->required_level * 0.1);

    //     return (int) round($baseDurability * $levelFactor);
    // }

}