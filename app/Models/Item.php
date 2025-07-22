<?php

namespace App\Models;

use App\Models\Character;
use App\Models\Item;
use App\Services\ItemBonusService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'required_level', 'buy_price', 'sell_price',
        'durability_current', 'durability_max',
        'bonuses', 'min_damage', 'max_damage', 'type', 'slot',
        'image', 'description', 'location', 'character_id',
        'defense_by_zone', 'base_max_durability', 'rarity',
    ];

    protected $casts = [
        'defense_by_zone' => 'array',
        'bonuses' => 'array',
        'base_max_durability' => 'integer',
    ];

    public function scopeInShop($query)
    {
        return $query->whereHas('characters', function($q) {
            $q->wherePivot('location', 'shop');
        });
    }

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'character_items')
            ->withPivot('location', 'slot', 'current_durability', 'max_durability', 'level');
    }

    public function getDurabilityAdjustedSellPrice($pivot): float
    {
        $sellPrice = $pivot->sell_price ?? $this->sell_price ?? 1;

        $current = $pivot->current_durability ?? null;
        $max = $pivot->max_durability ?? null;

        if ($current === null || $max === null || $max == 0) {
            return round($sellPrice, 2);
        }

        $percent = $current / $max;

        return round($sellPrice * $percent, 2);
    }

    public function isBroken()
    {
        return $this->pivot?->current_durability === 0;
    }

    public function getMinDamage(int $level): ?int
    {
        return $this->damage_ranges[$level]['min'] ?? null;
    }

    public function getMaxDamage(int $level): ?int
    {
        return $this->damage_ranges[$level]['max'] ?? null;
    }

}
