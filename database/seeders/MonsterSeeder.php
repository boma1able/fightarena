<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Monster;

class MonsterSeeder extends Seeder
{
    // public function run(): void
    // {
    //     $this->generateMonster(['name' => 'Гоблін']);
    //     $this->generateMonster(['name' => 'Гоблін-злодюжка', 'gold' => 10]);
    // }

    // private function generateMonster(array $base = []): Monster
    // {
    //     $baseStats = [
    //         'strength' => 3,
    //         'agility' => 3,
    //         'intuition' => 3,
    //         'endurance' => 3,
    //     ];

    //     $statPoints = 3;

    //     for ($i = 0; $i < $statPoints; $i++) {
    //         $key = array_rand($baseStats);
    //         $baseStats[$key]++;
    //     }

    //     return Monster::create(array_merge([
    //         'base_health' => 18,
    //         'current_health' => 18,
    //         'level' => 0,
    //         'gold' => 5,
    //     ], $baseStats, $base));
    // }
}