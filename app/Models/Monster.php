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

    public function getMaxHealthAttribute(): int
    {
        return $this->base_health + ($this->endurance * 6);
    }

    public function getBaseDamageAttribute(): int
    {
        return $this->strength;
    }

    public function getCriticalDamageMultiplierAttribute(): float
    {
        $intuition = $this->intuition;
        return round(1.5 + ($this->intuition * 0.005), 2);
    }

    // Крит
    public function getCritChanceAttribute(): float
    {
        $val = max($this->intuition, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 60);
    }

    // Анті-крит
    public function getAntiCritChanceAttribute(): float
    {
        $val = max($this->endurance, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 60);
    }

    // Ухил
    public function getDodgeChanceAttribute(): float
    {
        $val = max($this->agility, 0);
        return min(round(100 * ($val / ($val + 150)), 2), 75);
    }

    // Анті-ухил
    public function getAntiDodgeChanceAttribute(): float
    {
        $val = max($this->intuition, 0);
        return min(round(100 * ($val / ($val + 250)), 2), 75);
    }

}