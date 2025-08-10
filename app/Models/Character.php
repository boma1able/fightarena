<?php

namespace App\Models;

use App\Models\Character;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Character extends Model
{
    protected $fillable = [
        'user_id', 'base_health', 'current_health', 'strength', 'agility', 'intuition',
        'endurance', 'stat_points', 'level', 'experience', 'gold'
    ];

    protected $casts = [
        'health_regeneration_started_at' => 'datetime',
        'stat_points' => 'integer',
        'bonuses' => 'array',
        'defense_by_zone' => 'array',
        'debuffs' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Character $character) {
            $filePath = "logs/character_{$user->name}-{$character->id}.log";

            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
        });
    }

    //Накласти дебаф на персонажа
    public function applyDebuff(string $key, int $duration, int $delay = 0): void
    {
        $debuffs = $this->debuffs ?? [];

        if (isset($debuffs[$key])) {
            // Якщо дебаф уже є — оновлюємо тільки тривалість
            $debuffs[$key]['duration'] = max($debuffs[$key]['duration'], $duration);
        } else {
            $debuffs[$key] = [
                'duration' => $duration,
                'delay' => $delay,
                'name' => __('debuffs.' . $key . '.name'),
                'description' => __('debuffs.' . $key . '.description'),
            ];
        }

        $this->debuffs = $debuffs;
        $this->save();
    }

    //Оновлює всі дебафи персонажа (зменшує тривалість, знімає закінчені)
    public function updateDebuffs(): void
    {
        $debuffs = $this->debuffs ?? [];

        foreach ($debuffs as $key => $debuff) {
            // Відкладена активація
            if (!empty($debuff['delay']) && $debuff['delay'] > 0) {
                $debuff['delay']--;
                $debuffs[$key] = $debuff;
                continue;
            }

            // Зменшуємо тривалість
            if (!empty($debuff['duration'])) {
                $debuff['duration']--;

                if ($debuff['duration'] <= 0) {
                    unset($debuffs[$key]);
                } else {
                    $debuffs[$key] = $debuff;
                }
            }
        }

        $this->debuffs = $debuffs;
        $this->save();
    }

    public function getMaxDurabilityForItem(Item $item): int
    {
        $level = $item->level ?? $item->required_level ?? 1;

        $baseDurability = $item->base_max_durability ?? 100;
        $levelFactor = 1 + ($level * 0.1);

        return (int) round($baseDurability * $levelFactor);
    }

    public function giveShopItems()
    {
        $shopItems = Item::where('is_shop', true)->get();

        foreach ($shopItems as $item) {
            $maxDurability = $this->getMaxDurabilityForItem($item);

            $this->allItems()->attach($item->id, [
                'location' => 'shop',
                'slot' => $item->slot,
                'current_durability' => $maxDurability,
                'max_durability' => $maxDurability,
                'created_at' => now(),
                'updated_at' => now(),
                'rarity' => $item->rarity,
            ]);
        }
    }

    // Базова витривалість = здоровʼя
    public function getMaxHealthAttribute(): int
    {
        return $this->base_health + ($this->total_endurance * 6);
    }
    public function getTotalEnduranceAttribute(): int
    {
        $base = $this->endurance;
        $bonus = $this->bonuses['endurance'] ?? 0;
        return $base + $bonus;
    }
    public function adjustCurrentHealth(): void
    {
        if ($this->current_health > $this->max_health) {
            $this->current_health = $this->max_health;
            $this->save();
        }
    }

    public function getTotalStrengthAttribute(): int
    {
        $base = $this->strength;
        $bonus = $this->bonuses['strength'] ?? 0;
        return $base + $bonus;
    }

    public function getBaseDamageAttribute(): int
    {
        return (int) round($this->total_strength);
    }

    public function getTotalDamageRangeAttribute(): ?array
    {
        $weapon = $this->equippedItems->first(function($item) {
            return in_array($item->type, ['sword', 'axe', 'knife', 'mace']);
        });

        $base = $this->base_damage;

        if (!$weapon) {
            return ['min' => $base, 'max' => $base];
        }

        if ($weapon->isBroken()) {
            return ['min' => $base, 'max' => $base];
        }

        // Беремо min_damage та max_damage з pivot, якщо вони існують і не null,
        // інакше беремо з основної моделі Item
        $minDamage = $weapon->pivot->min_damage ?? $weapon->min_damage ?? 0;
        $maxDamage = $weapon->pivot->max_damage ?? $weapon->max_damage ?? 0;

        $minDamage += $base;
        $maxDamage += $base;

        return ['min' => $minDamage, 'max' => $maxDamage];
    }

    // Крит-урон
    public function getCriticalDamageMultiplierAttribute(): float
    {
        $intuition = $this->intuition;
        return round(1.5 + ($this->intuition * 0.005), 2);
    }
    // Мін-Макс Крит урон
    public function getCriticalDamageRangeAttribute(): array
    {
        $range = $this->totalDamageRange;

        return [
            'min' => round($range['min'] * $this->critical_damage_multiplier),
            'max' => round($range['max'] * $this->critical_damage_multiplier),
        ];
    }

    // Крит
    public function getCritChanceAttribute(): float
    {
        $val = max($this->total_intuition, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 60);
    }

    // Анті-крит
    public function getAntiCritChanceAttribute(): float
    {
        $val = max($this->total_endurance, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 60);
    }

    // Ухил
    public function getDodgeChanceAttribute(): float
    {
        $val = max($this->total_agility, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 75);
    }
    public function getTotalAgilityAttribute(): int
    {
        $base = $this->agility;
        $bonus = $this->bonuses['agility'] ?? 0;

        return $base + $bonus;
    }

    // Анті-ухил
    public function getAntiDodgeChanceAttribute(): float
    {
        $val = max($this->total_intuition, 0);
        return min(round(100 * ($val / ($val + 250)), 2), 75);
    }
    public function getTotalIntuitionAttribute(): int
    {
        $base = $this->intuition;
        $bonus = $this->bonuses['intuition'] ?? 0;

        return $base + $bonus;
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
             //Пропускаємо зламані предмети
             if (method_exists($item, 'isBroken') && $item->isBroken()) {
                continue;
            }
            $defenseByZone = $item->pivot->defense_by_zone ?? null;

            if (is_string($defenseByZone)) {
                $defenseByZone = json_decode($defenseByZone, true);
            }

            if (empty($defenseByZone)) {
                $defenseByZone = $item->defense_by_zone ?? [];
            }

            foreach ($defenseByZone as $zone => $values) {
                if (!isset($zones[$zone])) continue;

                $min = $values['min'] ?? 0;
                $max = $values['max'] ?? 0;

                $zones[$zone]['min'] += intval($min);
                $zones[$zone]['max'] += intval($max);
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

    public function equippedArmor()
    {
        return $this->equippedItems
            ->whereIn('type', ['armor', 'helmet', 'boots', 'legs', 'arms']);
    }

    public function getBonusesAttribute(): array
    {
        $bonuses = [];

        foreach ($this->equippedItems as $item) {
            // Ігноруємо зламані предмети
            if (method_exists($item, 'isBroken') && $item->isBroken()) {
                continue;
            }

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

    public function getTotalStat(string $statKey): int
    {
        $base = $this->{$statKey} ?? 0;
        $bonus = $this->bonuses[$statKey] ?? 0;
        return $base + $bonus;
    }

    public function regenerateHealthDynamic(): bool
    {
        if ($this->is_in_battle || $this->current_health >= $this->max_health) {
            return false;
        }

        if (!$this->health_regeneration_started_at) {
            $this->health_regeneration_started_at = now();
            $this->save();
            return false;
        }

        // Обчислення часу
        $secondsPassed = $this->health_regeneration_started_at->diffInSeconds(now());

        $percentRecovered = min(100, ($secondsPassed / 10) * 100);
        $expectedHealth = floor(($percentRecovered / 100) * $this->max_health);

        if ($expectedHealth > $this->current_health) {
            $this->current_health = min($expectedHealth, $this->max_health);
            $this->save();
        }

        if ($this->current_health >= $this->max_health) {
            $this->health_regeneration_started_at = null;
            $this->save();
        }

        return true;
    }

    public function gainExperience(int $amount): void
    {
        $this->experience += $amount;
        $this->checkLevelUp();
        $this->save();
    }

    public function checkLevelUp(): void
    {
        $levelUp = false;

        while ($this->experience >= $this->getExperienceToLevelUp()) {
            $this->experience -= $this->getExperienceToLevelUp();
            $this->level++;
            $this->stat_points += 1;
            $this->current_health = $this->max_health;
            $this->health_regeneration_started_at = null;

            $levelUp = true;
        }

        if ($levelUp) {
            $this->save();
        }
    }

    public function getExperienceToLevelUp(): int
    {
        $baseExp = 20;
        $expNeeded = $baseExp * pow(1.2, $this->level);

        return (int) round($expNeeded);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inventoryItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'current_durability', 'max_durability', 'slot', 'is_broken', 'rarity', 'bonuses', 'level', 'sell_price', 'min_damage', 'max_damage', 'defense_by_zone',])
            ->wherePivot('location', 'inventory');
    }

    public function equippedItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability', 'is_broken', 'rarity', 'bonuses', 'level', 'sell_price', 'min_damage', 'max_damage', 'defense_by_zone',])
            ->wherePivot('location', 'equipped')
            ->withCasts(['pivot.bonuses' => 'array']);
    }

    public function allItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability']);
    }

    public function shopItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability'])
            ->wherePivot('location', 'shop');
    }

    public function equippedItemsBySlot()
    {
        return $this->equippedItems()
            ->get()
            ->groupBy(fn($item) => $item->pivot->slot);
    }

    public function wearDownEquippedItems(): void
    {
        foreach ($this->equippedItems as $item) {
            $pivot = $item->pivot;

            // Пропускаємо, якщо немає durability або вже зламано
            if (
                is_null($pivot->current_durability) ||
                $pivot->current_durability <= 0 ||
                $pivot->is_broken
            ) {
                continue;
            }

            // 5% шанс на зношення
            if (rand(1, 100) <= 5) {
                $newDurability = max(0, $pivot->current_durability - 1);

                $updateData = ['current_durability' => $newDurability];
                // $msg = "{$item->name} зазнав шкоди [залишилось $newDurability / $pivot->max_durability].";
                $msg = __('messages.item_damaged', [
                    'name' => __('items.' . $item->key . '.name'),
                    'new' => $newDurability,
                    'max' => $pivot->max_durability,
                ]);

                if ($newDurability === 0) {
                    $updateData['is_broken'] = true;
                    // $msg = "Предмет {$item->name} зламався!";
                    $msg = __('messages.item_broken', [
                        'name' => __('items.' . $item->key . '.name'),
                    ]);
                }

                \DB::table('character_items')
                    ->where('id', $pivot->id)
                    ->update($updateData);

                $this->log($msg);
            }
        }
    }


    public function log(string $message): void
    {
        $timestamp = now()->format('H:i:s');
        $line = "[$timestamp] $message";

        $filePath = "logs/character_{$this->user->name}-{$this->id}.log";
        Storage::disk('local')->append($filePath, $line);
    }
}