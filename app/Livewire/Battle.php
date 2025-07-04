<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\Monster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Battle extends Component
{
    public $character;
    public $monster;

    public array $equippedBySlot = [];
    public array $monsterEquippedBySlot = [];

    public $is_in_battle = false;
    public $attackChoice = null;
    public $defenseChoice = null;

    public bool $battleFinished = false;

    public $messages = [];

    protected array $rarityChances = [
        'common' => 60,
        'uncommon' => 25,
        'rare' => 10,
        'legendary' => 5,
    ];

    public function mount()
    {
        $this->character = auth()->user()->character;

        $this->equippedBySlot = $this->character->equippedItems()
            ->withPivot('id', 'current_durability', 'max_durability')
            ->get()
            ->groupBy('pivot.slot')
            ->map->first()
            ->toArray();

        if (
            request()->isMethod('get') &&
            auth()->check() &&
            auth()->user()->character->is_in_battle &&
            !request()->routeIs('battle')
        ) {
            return redirect()->route('battle')->with('message', 'Ви не можете залишити бій поки він не завершений');
        }

        if (session()->has('battle_monster_id')) {
            $this->monster = Monster::with('items')->find(session('battle_monster_id'));
        }

        if (!$this->monster) {
            $this->monster = $this->generateMonster($this->character->level);
            session(['battle_monster_id' => $this->monster->id]);
        }

        // Отримуємо екіпіровку монстра
        $equippedItems = $this->monster->items->groupBy(function ($item) {
            return $item->pivot->slot;
        });

        // Вказуємо всі слоти, які потрібно мати
        $allSlots = [
            'helmet', 'armor', 'boots', 'weapon', 'shield',
            'legs', 'arms', 'neckless',
            'ring1', 'ring2'
        ];

        // Формуємо масив екіпіровки монстра, щоб кожен слот був, навіть якщо null
        $this->monsterEquippedBySlot = [];
        foreach ($allSlots as $slot) {
            $this->monsterEquippedBySlot[$slot] = $equippedItems[$slot][0] ?? null;
        }

        $this->character->is_in_battle = true;
        $this->character->save();
    }

    public function pickRarity(): string
    {
        $rand = random_int(1, 100);
        $cumulative = 0;

        foreach ($this->rarityChances as $rarity => $chance) {
            $cumulative += $chance;
            if ($rand <= $cumulative) {
                return $rarity;
            }
        }

        return 'common'; // запасний варіант
    }

    public function generateDrop(): ?Item
    {
        // Беремо випадковий предмет з усіх доступних у магазині
        $item = Item::inRandomOrder()->first();

        if (!$item) {
            return null; // предметів немає
        }

        // Генеруємо раритет
        $rarity = $this->pickRarity();

        // Динамічно додаємо властивість, щоб передати раритет, не змінюючи БД
        $item->rarity = $rarity;

        return $item;
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

        $monster = Monster::create([
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

        // Додаємо предмети
        if ($level > 0) {
            $this->equipMonster($monster);
        }

        return $monster;
    }

    public function equipMonster(Monster $monster): void
    {
        $allSlots = [
            'helmet', 'armor', 'boots', 'weapon', 'shield', 'legs', 'arms', 'neckless',
        ];

        // Максимальна кількість предметів = 1 + level (але не більше ніж кількість слотів)
        $maxItems = min(count($allSlots), max(1, $monster->level + 1));
        $equippedCount = 0;

        // Якщо рівень >= 3 — зброя обов'язково
        if ($monster->level >= 3) {
            $weapon = Item::query()
                ->where('slot', 'weapon')
                ->where('required_level', '<=', $monster->level)
                ->inRandomOrder()
                ->first();

            if ($weapon) {
                $monster->items()->attach($weapon->id, [
                    'slot' => 'weapon',
                    'is_broken' => false,
                    'rarity' => $monster->pickRarity(),
                ]);
                $equippedCount++;

                foreach ($weapon->bonuses ?? [] as $stat => $value) {
                    if (in_array($stat, ['strength', 'agility', 'intuition', 'endurance'])) {
                        $monster->{$stat} += $value;
                    }
                }
            }
        }

        // Вибираємо випадкові інші слоти (крім weapon, якщо вже є)
        $randomSlots = collect($allSlots)
            ->filter(fn($slot) => !($monster->level >= 3 && $slot === 'weapon'))
            ->shuffle()
            ->take($maxItems - $equippedCount);

        foreach ($randomSlots as $slot) {
            $item = Item::query()
                ->where('slot', $slot)
                ->where('required_level', '<=', $monster->level)
                ->inRandomOrder()
                ->first();

            if ($item) {
                $monster->items()->attach($item->id, [
                    'slot' => $slot,
                    'is_broken' => false,
                    'rarity' => $monster->pickRarity(),
                ]);

                foreach ($item->bonuses ?? [] as $stat => $value) {
                    if (in_array($stat, ['strength', 'agility', 'intuition', 'endurance'])) {
                        $monster->{$stat} += $value;
                    }
                }
            }
        }

        // Додаємо кільця
        for ($i = 1; $i <= 2; $i++) {
            if ($equippedCount >= $maxItems) break;

            $ringSlot = 'ring' . $i;

            $item = Item::query()
                ->where('slot', 'ring')
                ->where('required_level', '<=', $monster->level)
                ->inRandomOrder()
                ->first();

            if ($item) {
                $monster->items()->attach($item->id, [
                    'slot' => $ringSlot,
                    'is_broken' => false,
                    'rarity' => $monster->pickRarity(),
                ]);
                $equippedCount++;

                foreach ($item->bonuses ?? [] as $stat => $value) {
                    if (in_array($stat, ['strength', 'agility', 'intuition', 'endurance'])) {
                        $monster->{$stat} += $value;
                    }
                }
            }
        }

        // Оновлюємо здоров’я
        $monster->base_health = $monster->endurance * 6;
        $monster->current_health = $monster->base_health;
        $monster->save();
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
            $msg = $this->monster->name . " заблокував ваш удар у " . $possibleZones[$charAttack] . ".";
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
                $msg = $this->monster->name . " ухилився від вашого удару у " . $possibleZones[$charAttack] . ".";
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
                    $msg = "Ви вдарили " . $this->monster->name .  " у " . $possibleZones[$charAttack] . " і нанесли $charDamage шкоди.";
                } else{
                    $msg = "Ви вдарили " . $this->monster->name . " у " . $possibleZones[$charAttack] . " але не нанесли жодної шкоди.";
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
            $msg = "Ви заблокували удар " . $this->monster->name . " у " . $possibleZones[$monsterAttack] . ".";
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
                $msg = "Ви ухилилися від удару " . $this->monster->name . " у " . $possibleZones[$monsterAttack] . ".";
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
                    $msg = $this->monster->name . " вдарив вас у " . $possibleZones[$monsterAttack] . " і наніс $monsterDamage шкоди.";
                } else{
                    $msg = $this->monster->name . " вдарив вас у " . $possibleZones[$monsterAttack] . " але не наніс жодної шкоди.";
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

            $drop = $this->monster->generateDrop();
            if ($drop) {
                $maxDurability = $this->character->getMaxDurabilityForItem($drop);
                $this->character->inventoryItems()->attach($drop->id, [
                    'current_durability' => $maxDurability,
                    'max_durability' => $maxDurability,
                    'slot' => null,
                    'rarity' => $drop->rarity,
                    'location' => 'inventory',
                ]);
                $this->character->log("Ви отримали предмет: {$drop->name} ({$drop->rarity})");
            }

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
