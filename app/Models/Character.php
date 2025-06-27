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
    ];

    protected static function booted(): void
    {
        static::deleting(function (Character $character) {
            $filePath = "logs/character_{$character->id}.log";

            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
        });
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

        if (!$weapon) {
            $base = $this->base_damage;
            return ['min' => $base, 'max' => $base];
        }

        $base = $this->base_damage;

        $minDamage = ($weapon->min_damage ?? 0) + $base;
        $maxDamage = ($weapon->max_damage ?? 0) + $base;

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

    public function getBonusesAttribute(): array
    {
        $bonuses = [];

        foreach ($this->equippedItems as $item) {
            // Припустимо, $item->bonuses вже масив або null
            $itemBonuses = $item->bonuses ?? [];

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

        // Ось тут правильне обчислення часу
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
            ->withPivot(['id', 'location', 'current_durability', 'max_durability', 'slot'])
            ->wherePivot('location', 'inventory');
    }

    public function equippedItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability'])
            ->wherePivot('location', 'equipped');
    }

    public function allItems()
    {
        return $this->belongsToMany(Item::class, 'character_items')
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability']);
    }

    public function equippedItemsBySlot()
    {
        return $this->equippedItems()
            ->get()
            ->groupBy(fn($item) => $item->pivot->slot);
    }

    public function log(string $message): void
    {
        $timestamp = now()->format('H:i:s');
        $line = "[$timestamp] $message";

        $filePath = "logs/character_{$this->id}.log";
        Storage::disk('local')->append($filePath, $line);
    }
}