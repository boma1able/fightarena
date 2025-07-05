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
}
