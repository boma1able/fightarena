<?php

namespace App\Models;

use App\Models\Item;
use App\Services\DropService;
use App\Services\ItemBonusService;
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
        'rarity',
    ];

    protected $casts = [
        'debuffs' => 'array',
    ];

    public function applyDebuff(string $key, int $duration, int $delay = 0): bool
    {
        $debuffs = $this->debuffs ?? [];

        if (isset($debuffs[$key])) {
            return false;
        }

        $debuffs[$key] = [
            'duration'    => $duration,
            'delay'       => $delay,
            'name'        => __('debuffs.' . $key . '.name'),
            'description' => __('debuffs.' . $key . '.description'),
        ];

        // логіка для дебафа deep_cut
        if ($key === 'deep_cut') {
            // тягнемо % із lang
            $raw = __('debuffs.' . $key . '.max_hp_reduction_percent');
            $percent = is_numeric($raw) ? (int)$raw : 10;
            $percent = max(0, $percent);

            $originalBase    = (int)$this->base_health;
            $originalCurrent = (int)$this->current_health;

            $reductionBase    = (int) floor($originalBase    * $percent / 100);
            $reductionCurrent = (int) floor($originalCurrent * $percent / 100);

            // захист від мінусу
            $reductionBase    = max(0, min($reductionBase,    max(0, $originalBase - 1)));
            $reductionCurrent = max(0, min($reductionCurrent, $originalCurrent));

            if ($reductionBase > 0) {
                $this->base_health = $originalBase - $reductionBase;
            }
            if ($reductionCurrent > 0) {
                $this->current_health = $originalCurrent - $reductionCurrent;
            }

            $debuffs[$key]['reduction_base'] = $reductionBase; // повернемо рівно стільки
            $debuffs[$key]['percent']        = $percent;       // для +% до current при знятті
        }

        $this->debuffs = $debuffs;
        $this->save();

        return true;
    }

    public function updateDebuffs()
    {
        $debuffs = $this->debuffs ?? [];

        foreach ($debuffs as $key => $debuff) {
            // Якщо є відкладена активація
            if (!empty($debuff['delay']) && $debuff['delay'] > 0) {
                $debuff['delay']--;

                // Якщо ще є затримка — оновлюємо й пропускаємо далі
                $debuffs[$key] = $debuff;
                continue;
            }

            if (isset($debuff['duration'])) {
                $debuff['duration']--;

                if ($debuff['duration'] <= 0) {
                    // момент спадання
                    if ($key === 'deep_cut') {
                        //повертаємо base_health рівно на зняту суму
                        $restoreBase = (int)($debuff['reduction_base'] ?? 0);
                        if ($restoreBase > 0) {
                            $this->base_health = (int)$this->base_health + $restoreBase;
                        }

                        //current_health + % від ПОТОЧНОГО значення на момент зняття
                        $percent = (int)($debuff['percent'] ?? 10);
                        if ($percent > 0 && $this->current_health > 0) {
                            $increase = (int) floor($this->current_health * $percent / 100);
                            if ($increase > 0) {
                                $this->current_health = (int) $this->current_health + $increase;
                            }
                        }

                        //не вище за відновлений base_health
                        if ($this->current_health > $this->base_health) {
                            $this->current_health = $this->base_health;
                        }
                    }

                    if ($key === 'sunder') {
                        $raw = __('debuffs.' . $key . '.armor_reduction_percent');
                        $percent = is_numeric($raw) ? (int)$raw : 100; // за замовчуванням 100%
                        $percent = max(0, min(100, $percent));

                        $debuffs[$key]['percent'] = $percent;

                        // Ми не міняємо базові параметри монстра напряму,
                        // а просто зберігаємо % для використання під час розрахунку бою
                    }

                    unset($debuffs[$key]);
                } else {
                    $debuffs[$key] = $debuff;
                }

                if ($key === 'bleeding') {
                    if ($debuff['duration'] < 0) {
                        unset($debuffs[$key]);
                    } else {
                        $debuffs[$key] = $debuff;
                    }
                } else {
                    if ($debuff['duration'] <= 0) {
                        unset($debuffs[$key]);
                    } else {
                        $debuffs[$key] = $debuff;
                    }
                }
            }
        }

        $this->debuffs = $debuffs;
        $this->save();
    }

    public function processBleedingDebuffMonster(): int
    {
        if (!empty($this->debuffs['bleeding']) &&
            empty($this->debuffs['bleeding']['delay']) &&
            $this->debuffs['bleeding']['duration'] >= 0)
        {
            $bleedingPercent = $this->debuffs['bleeding']['power'] ?? 0.15;
            $bleedingDamage = max(1, ceil($this->current_health * $bleedingPercent));

            $this->current_health = max(0, $this->current_health - $bleedingDamage);
            $this->save();

            return $bleedingDamage;
        }
        return 0;
    }


    public function getBonusesAttribute(): array
    {
        $bonuses = [];

        $items = $this->equippedItems ?? collect();

        foreach ($items as $item) {
            // отримуємо бонуси з PIVOT, не з ITEM
            $itemBonuses = $item->pivot->bonuses ?? [];

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
            ->withPivot('slot', 'rarity', 'bonuses', 'level', 'sell_price');
    }

    public function generateDrop(): ?Item
    {
        return DropService::generateForMonster($this);
    }

}