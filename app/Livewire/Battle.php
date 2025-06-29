<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Monster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Battle extends Component
{
    public $character;
    public $monster;

    public $is_in_battle = false;
    public $attackChoice = null;
    public $defenseChoice = null;

    public bool $battleFinished = false;

    public $messages = [];

    public function mount()
    {
        $this->character = auth()->user()->character;

        if (
            request()->isMethod('get') &&
            auth()->check() &&
            auth()->user()->character->is_in_battle &&
            !request()->routeIs('battle')
        ) {
            return redirect()->route('battle')->with('message', 'Ви не можете залишити бій поки він не завершений');
        }

        if (session()->has('battle_monster_id')) {
            $this->monster = Monster::find(session('battle_monster_id'));
        }

        if (!$this->monster) {
            $this->monster = $this->generateMonster($this->character->level);
            session(['battle_monster_id' => $this->monster->id]);
        }

        $this->character->is_in_battle = true;
        $this->character->save();
    }


    public function generateMonster(int $level): Monster
    {
        $baseStats = [
            'strength' => 3,
            'agility' => 3,
            'intuition' => 3,
            'endurance' => 3,
        ];

        $statPoints = 3 + ($level * 1);

        $statKeys = array_keys($baseStats);
        for ($i = 0; $i < $statPoints; $i++) {
            $randomStat = $statKeys[array_rand($statKeys)];
            $baseStats[$randomStat]++;
        }

        $name = fake()->randomElement(config('monster_names'));

        return Monster::create([
            'name' => $name,
            'level' => $level,
            'gold' => rand(1, 7) + $level,
            'base_health' => $baseStats['endurance'] * 6,
            'current_health' => $baseStats['endurance'] * 6,
            'strength' => $baseStats['strength'],
            'agility' => $baseStats['agility'],
            'intuition' => $baseStats['intuition'],
            'endurance' => $baseStats['endurance'],
            'is_temporary' => true,
        ]);
    }

    public function fightStep()
    {
        if (!$this->attackChoice || !$this->defenseChoice) {
            return;
        }

        $defenseMap = [
            'head_chest' => ['head', 'chest'],
            'chest_belly' => ['chest', 'belly'],
            'belly_belt' => ['belly', 'belt'],
            'belt_legs' => ['belt', 'legs'],
            'legs_head' => ['legs', 'head'],
        ];

        $possibleZones = ['head' => 'Голову', 'chest' => 'Груди', 'belly' => 'Живіт', 'belt' => 'Пояс', 'legs' => 'Ноги'];

        $charAttack = $this->attackChoice;
        $charDefenseZones = $defenseMap[$this->defenseChoice] ?? [];

        $monsterAttackKey = array_rand($possibleZones);
        $monsterAttack = $monsterAttackKey;
        $monsterDefenseKey = array_rand($defenseMap);
        $monsterDefenseZones = $defenseMap[$monsterDefenseKey] ?? [];

        // --- Удар по монстру ---
        if (in_array($charAttack, $monsterDefenseZones)) {
            $msg = "Монстр заблокував ваш удар у " . $possibleZones[$charAttack] . ".";
            $this->messages[] = $msg;
            $this->character->log($msg);
            $this->dispatch('showHit', ['message' => 'Блок!', 'target' => 'monster', 'type' => 'block']);
        } else {
            $range = $this->character->totalDamageRange;
            $charDamage = rand($range['min'], $range['max']);

            // Якщо у монстра є броня по зоні — віднімаємо її
            $monsterDefenseByZone = method_exists($this->monster, 'totalDefenseByZone')
                ? $this->monster->totalDefenseByZone()
                : [];

            $monsterZoneArmor = $monsterDefenseByZone[$charAttack] ?? ['min' => 0, 'max' => 0];
            // Візьмемо середнє значення броні монстра для зони
            $monsterArmorAvg = intval(round(($monsterZoneArmor['min'] + $monsterZoneArmor['max']) / 2));

            $charDamage = max(0, $charDamage - $monsterArmorAvg);

            if (rand(1, 100) <= $this->monster->dodge_chance - $this->character->anti_dodge_chance) {
                $msg = "Монстр ухилився від вашого удару у " . $possibleZones[$charAttack] . ".";
                $this->messages[] = $msg;
                $this->character->log($msg);
                $this->dispatch('showHit', ['message' => "Ухил", 'target' => 'monster', 'type' => 'dodge']);
            } else {
                $isCrit = rand(1, 10000) <= ($this->character->crit_chance - $this->monster->anti_crit_chance) * 100;
                if ($isCrit) {
                    $multiplier = $this->character->critical_damage_multiplier;
                    $charDamage = (int) round($charDamage * $multiplier);
                }

                $this->monster->current_health = max(0, $this->monster->current_health - $charDamage);
                $this->monster->save();

                if ($charDamage > 0){
                    $msg = "Ви вдарили монстра у " . $possibleZones[$charAttack] . " і нанесли $charDamage шкоди.";
                } else{
                    $msg = "Ви вдарили монстра у " . $possibleZones[$charAttack] . " але не нанесли жодної шкоди.";
                }
                if ($isCrit) $msg .= " Критичний удар!";
                $this->messages[] = $msg;
                $this->character->log($msg);

                if ($charDamage > 0){
                    $this->dispatch('showHit', [
                        'message' => "-{$charDamage} хп",
                        'target' => 'monster',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);
                }else{
                    $this->dispatch('showHit', [
                        'message' => "0 хп",
                        'target' => 'monster',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);
                }

            }
        }

        // --- Удар по персонажу ---
        if (in_array($monsterAttack, $charDefenseZones)) {
            $msg = "Ви заблокували удар монстра у " . $possibleZones[$monsterAttack] . ".";
            $this->messages[] = $msg;
            $this->character->log($msg);

            $this->dispatch('showHit', ['message' => "Блок!", 'target' => 'player', 'type' => 'block']);
        } else {
            $range = $this->monster->totalDamageRange ?? ['min' => $this->monster->base_damage, 'max' => $this->monster->base_damage];
            $monsterDamage = rand($range['min'], $range['max']);

            $defenseByZone = $this->character->totalDefenseByZone();
            $zoneDefense = $defenseByZone[$monsterAttack] ?? ['min' => 0, 'max' => 0];
            $armorAvg = intval(round(($zoneDefense['min'] + $zoneDefense['max']) / 2));

            $monsterDamage = max(0, $monsterDamage - $armorAvg);

            if (rand(1, 100) <= $this->character->dodge_chance - $this->monster->anti_dodge_chance) {
                $msg = "Ви ухилилися від удару монстра у " . $possibleZones[$monsterAttack] . ".";
                $this->messages[] = $msg;
                $this->character->log($msg);

                $this->dispatch('showHit', ['message' => "Ухил", 'target' => 'player', 'type' => 'dodge']);
            } else {
                $isCrit = rand(1, 10000) <= ($this->monster->crit_chance - $this->character->anti_crit_chance) * 100;
                if ($isCrit) {
                    $multiplier = $this->monster->critical_damage_multiplier;
                    $monsterDamage = (int) round($monsterDamage * $multiplier);
                }

                $this->character->current_health = max(0, $this->character->current_health - $monsterDamage);
                $this->character->save();

                if ($monsterDamage > 0){
                    $msg = "Монстр вдарив вас у " . $possibleZones[$monsterAttack] . " і наніс $monsterDamage шкоди.";
                } else{
                    $msg = "Монстр вдарив вас у " . $possibleZones[$monsterAttack] . " але не наніс жодної шкоди.";
                }
                if ($isCrit) $msg .= " Критичний удар!";
                $this->messages[] = $msg;
                $this->character->log($msg);

                if ($monsterDamage > 0) {
                    $this->dispatch('showHit', [
                        'message' => "-{$monsterDamage} хп",
                        'target' => 'player',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);
                }else{
                    $this->dispatch('showHit', [
                        'message' => "0 хп",
                        'target' => 'player',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);
                }
            }
        }

        // ... решта логіки бою і підрахунок результатів
        $this->dispatch('refreshInfoChat');

        $this->character->wearDownEquippedItems();

        if ($this->character->current_health <= 0 && $this->monster->current_health <= 0) {
            $result = "Нічия! Обидва опоненти впали.";
            $xpMultiplier = 0;
            $this->character->draws++;
            $this->character->log($result);
        } elseif ($this->character->current_health <= 0) {
            $result = "Ви програли бій!";
            $xpMultiplier = 0.5;
            $this->character->losses++;
            $this->character->log($result);
        } elseif ($this->monster->current_health <= 0) {
            $result = "Ви перемогли " . $this->monster->name . "!";
            $xpMultiplier = 1.2;
            $this->character->wins++;
            $this->character->log($result);
        } else {
            $result = null;
            $xpMultiplier = 0;
        }

        $this->character->save();

        if ($result !== null) {
            $oldLevel = $this->character->level;

            $baseXp = match (true) {
                $this->character->level <= 3 => rand(6, 7),
                $this->character->level <= 6 => rand(8, 10),
                $this->character->level <= 10 => rand(12, 15),
                $this->character->level <= 15 => rand(18, 25),
                $this->character->level <= 20 => rand(30, 40),
                default => rand(40, 60),
            };

            $xpGained = (int) round($baseXp * $xpMultiplier);
            $goldGained = 0;

            if ($xpGained > 0) {
                $this->character->gainExperience($xpGained);
                $this->character->log("Ви отримали $xpGained досвіду!");
            }

            $newLevel = $this->character->level;
            $leveledUp = $newLevel > $oldLevel;

            if ($xpMultiplier === 1.2) {
                $goldGained = $this->monster->gold ?? 0;
                $this->character->gold += $goldGained;
                $this->character->save();
                $this->character->log("Ви здобули $goldGained золотих монет!");
            }

            $this->resetBattle();
            return redirect()->route('history');
        }

        $this->attackChoice = null;
        $this->defenseChoice = null;
    }

    private function resetBattle()
    {
        $this->character->is_in_battle = false;

        if ($this->character->current_health < $this->character->max_health) {
            $this->character->health_regeneration_started_at = now();
        }
        $this->character->save();

        $this->monster->current_health = $this->monster->max_health;
        $this->monster->save();

        session()->forget('battle_monster_id');

        if ($this->monster && $this->monster->is_temporary) {
            $this->monster->delete();
        }

        $this->attackChoice = null;
        $this->defenseChoice = null;
    }

    public function render()
    {
        return view('livewire.battle');
    }
}
