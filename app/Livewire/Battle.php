<?php

namespace App\Livewire;

// use \App\Helpers\ItemBonusGenerator;
use App\Models\Item;
use App\Models\Monster;
use App\Services\DropService;
use App\Services\ItemBonusService;
use App\Services\ItemPricingService;
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
    public ?string $attackChoice = null;
    public ?string $defenseChoice = null;

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
        $this->checkStun();

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

        // Викликаємо оновлення екіпіровки монстра через новий метод
        $this->updateMonsterEquipped();

        $this->character->is_in_battle = true;
        $this->character->save();
    }

    public function updateMonsterEquipped()
    {
        $this->monster = Monster::with('items')->find($this->monster->id);

        $equippedItems = $this->monster->items->groupBy(function ($item) {
            return $item->pivot->slot;
        });

        $allSlots = [
            'helmet', 'armor', 'boots', 'weapon', 'shield',
            'legs', 'arms', 'neckless',
            'ring1', 'ring2'
        ];

        $this->monsterEquippedBySlot = [];
        foreach ($allSlots as $slot) {
            $this->monsterEquippedBySlot[$slot] = $equippedItems[$slot][0] ?? null;
        }
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

        // $name = fake()->randomElement(config('monster_names'));
        $monsterNames = __('monster_names');
        $name = fake()->randomElement($monsterNames);

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
            'ring1', 'ring2',
        ];

        $maxItems = min(count($allSlots), max(1, $monster->level + 1));
        $equippedCount = 0;
        $usedSlots = [];

        // Якщо рівень >= 3 — зброя обов'язково
        if ($monster->level >= 3) {
            $weapon = Item::query()
                ->where('slot', 'weapon')
                ->where('type', 'sword') //temp!!!!!
                ->where('required_level', '<=', $monster->level)
                ->inRandomOrder()
                ->first();

            if ($weapon) {
                $rarity = DropService::pickRarity($monster->level);
                $bonuses = ItemBonusService::generate($rarity, 'weapon', $monster->level);
                $price = ItemPricingService::calculate($bonuses, $rarity, $monster->level);

                $monster->items()->attach($weapon->id, [
                    'slot' => 'weapon',
                    'rarity' => $rarity,
                    'bonuses' => json_encode($bonuses),
                    'level' => $monster->level,
                    'sell_price' => $price,
                ]);

                $equippedCount++;
                $usedSlots[] = 'weapon';

                foreach ($bonuses as $stat => $value) {
                    if (in_array($stat, ['strength', 'agility', 'intuition', 'endurance'])) {
                        $monster->{$stat} += $value;
                    }
                }
            }
        }

        // Вибираємо інші випадкові слоти (включаючи кільця)
        $availableSlots = collect($allSlots)
            ->filter(fn($slot) => !in_array($slot, $usedSlots))
            ->shuffle()
            ->take($maxItems - $equippedCount);

        foreach ($availableSlots as $slot) {
            // Для ring1/ring2 шукаємо item зі slot = 'ring'
            $dbSlot = str_starts_with($slot, 'ring') ? 'ring' : $slot;

            $item = Item::query()
                ->where('slot', $dbSlot)
                ->where('required_level', '<=', $monster->level)
                ->inRandomOrder()
                ->first();

            if ($item) {
                $rarity = DropService::pickRarity($monster->level);
                $bonuses = ItemBonusService::generate($rarity, $item->slot, $monster->level);
                $price = ItemPricingService::calculate($bonuses, $rarity, $monster->level);

                $monster->items()->attach($item->id, [
                    'slot' => $slot,
                    'rarity' => $rarity,
                    'bonuses' => json_encode($bonuses),
                    'level' => $monster->level,
                    'sell_price' => $price,
                ]);

                $equippedCount++;
                $usedSlots[] = $slot;

                foreach ($bonuses as $stat => $value) {
                    if (in_array($stat, ['strength', 'agility', 'intuition', 'endurance'])) {
                        $monster->{$stat} += $value;
                    }
                }
            }
        }

        // Підрахунок HP після застосування бонусів
        $monster->base_health = $monster->endurance * 6;
        $monster->current_health = $monster->base_health;
        $monster->save();
    }

    public function fightStep()
    {

        $this->checkStun();

        $this->character->updateDebuffs();
        $this->monster->updateDebuffs();

        $this->processBleedingDebuff();

        $characterStunned = !empty($this->character->debuffs['stun']) && empty($this->character->debuffs['stun']['delay']);

        $defenseMap = [
            'head_chest' => ['head', 'chest'],
            'chest_belly' => ['chest', 'belly'],
            'belly_belt' => ['belly', 'belt'],
            'belt_legs' => ['belt', 'legs'],
            'legs_head' => ['legs', 'head'],
        ];

        $possibleZones = [
            'head' => __('messages.chat_head'),
            'chest' => __('messages.chat_chest'),
            'belly' => __('messages.chat_belly'),
            'belt' => __('messages.chat_belt'),
            'legs' => __('messages.chat_legs'),
        ];

        $charAttack = $this->attackChoice;
        $charDefenseZones = $defenseMap[$this->defenseChoice] ?? [];

        $monsterAttackKey = array_rand($possibleZones);
        $monsterAttack = $monsterAttackKey;

        $monsterStunned = !empty($this->monster->debuffs['stun']);

        $monsterAttackKey = array_rand($possibleZones);
        $monsterAttack = $monsterAttackKey;

        // Якщо монстр застанений — він не обирає оборону
        if ($monsterStunned) {
            $monsterDefenseZones = [];
            $monsterAttack = [];
        } else {
            $monsterDefenseKey = array_rand($defenseMap);
            $monsterDefenseZones = $defenseMap[$monsterDefenseKey] ?? [];
        }

        if ($characterStunned) {
            $charAttack = null;
            $charDefenseZones = [];

        } else{
            // --- Удар по монстру ---
            if (!$monsterStunned && in_array($charAttack, $monsterDefenseZones)) {
                $msg = __('messages.monster_blocked', [
                    'name' => $this->monster->name,
                    'zone' => $possibleZones[$charAttack],
                ]);
                $this->messages[] = $msg;
                $this->character->log($msg);

                $this->dispatch('showHit', [
                    'message' => __('messages.block'),
                    'target' => 'monster',
                    'type' => 'block',
                ]);
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

                if (!$monsterStunned && rand(1, 100) <= $this->monster->dodge_chance - $this->character->anti_dodge_chance) {

                    $msg = __('messages.dodged_attack', [
                        'name' => $this->monster->name,
                        'zone' => $possibleZones[$charAttack],
                    ]);
                    $this->messages[] = $msg;
                    $this->character->log($msg);

                    $this->dispatch('showHit', [
                        'message' => __('messages.dodge'),
                        'target' => 'monster',
                        'type' => 'dodge',
                    ]);
                } else {
                    $weapon = $this->character->equippedItems->where('slot', 'weapon')->first();

                    if (
                        $weapon &&
                        !$weapon->pivot->is_broken &&  // <- перевірка, що зброя персонажа не зламана
                        $charDamage > 0 &&
                        !empty($weapon->debuffs)
                    ){
                        if ($weapon && $charDamage > 0 && !empty($weapon->debuffs)) {
                            foreach ($weapon->debuffs as $debuff) {
                                $chance = $debuff['chance'] ?? 100;
                                $duration = $debuff['duration'] ?? 1;

                                if (rand(1, 100) <= $chance) {

                                    if ($debuff['key'] === 'stun') {
                                        $this->monster->applyDebuff($debuff['key'], $duration, 1);
                                    } elseif ($debuff['key'] === 'bleeding') {
                                        $this->monster->applyDebuff($debuff['key'], $duration, 0);
                                    } else {
                                        $this->monster->applyDebuff($debuff['key'], $duration, $duration);
                                    }
                                    $msg = __('messages.debuff_applied', [
                                        'name' => $this->monster->name,
                                        'debuff' => __('debuffs.' . $debuff['key'] . '.name'),
                                    ]);

                                    $this->messages[] = $msg;
                                    $this->character->log($msg);

                                    $this->dispatch('$refresh');
                                }
                            }
                        }
                    }

                    $isCrit = rand(1, 10000) <= ($this->character->crit_chance - $this->monster->anti_crit_chance) * 100;
                    if ($isCrit) {
                        $multiplier = $this->character->critical_damage_multiplier;
                        $charDamage = (int) round($charDamage * $multiplier);
                    }

                    $this->monster->current_health = max(0, $this->monster->current_health - $charDamage);
                    $this->monster->save();

                    if ($charDamage > 0) {
                        $msg = __('messages.hit_damage', [
                            'name' => $this->monster->name,
                            'zone' => $possibleZones[$charAttack],
                            'damage' => $charDamage,
                        ]);
                    } else {
                        $msg = __('messages.hit_no_damage', [
                            'name' => $this->monster->name,
                            'zone' => $possibleZones[$charAttack],
                        ]);
                    }

                    if ($isCrit) {
                        $msg .= ' ' . __('messages.critical_hit');
                    }
                    $this->messages[] = $msg;
                    $this->character->log($msg);

                    $amount = $charDamage > 0 ? $charDamage : 0;
                    $this->dispatch('showHit', [
                        'message' => __('messages.hp_damage', ['amount' => $amount]),
                        'target' => 'monster',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);

                }
            }

        }

        $monsterStunned = !empty($this->monster->debuffs['stun']) && empty($this->monster->debuffs['stun']['delay']);

        // --- Удар по персонажу ---
        if (!$monsterStunned) {
            if (in_array($monsterAttack, $charDefenseZones)) {

                $msg = __('messages.you_blocked', [
                    'name' => $this->monster->name,
                    'zone' => $possibleZones[$monsterAttack],
                ]);
                $this->messages[] = $msg;
                $this->character->log($msg);

                $this->dispatch('showHit', [
                    'message' => __('messages.block'),
                    'target' => 'player',
                    'type' => 'block',
                ]);
            } else {
                $range = $this->monster->totalDamageRange ?? ['min' => $this->monster->base_damage, 'max' => $this->monster->base_damage];
                $monsterDamage = rand($range['min'], $range['max']);

                $defenseByZone = $this->character->totalDefenseByZone();
                $zoneDefense = $defenseByZone[$monsterAttack] ?? ['min' => 0, 'max' => 0];
                $armorAvg = intval(round(($zoneDefense['min'] + $zoneDefense['max']) / 2));

                $monsterDamage = max(0, $monsterDamage - $armorAvg);

                if (rand(1, 100) <= $this->character->dodge_chance - $this->monster->anti_dodge_chance) {

                    $msg = __('messages.your_dodged_attack', [
                        'name' => $this->monster->name,
                        'zone' => $possibleZones[$monsterAttack],
                    ]);
                    $this->messages[] = $msg;
                    $this->character->log($msg);

                    $this->dispatch('showHit', [
                        'message' => __('messages.dodge'),
                        'target' => 'player',
                        'type' => 'dodge',
                    ]);
                } else {
                    $isCrit = rand(1, 10000) <= ($this->monster->crit_chance - $this->character->anti_crit_chance) * 100;
                    if ($isCrit) {
                        $multiplier = $this->monster->critical_damage_multiplier;
                        $monsterDamage = (int) round($monsterDamage * $multiplier);
                    }

                    //перевірка накладення дебафу на персонажа
                    $monsterWeapon = $this->monster->equippedItems->where('slot', 'weapon')->first();

                    if ($monsterWeapon && $monsterDamage > 0 && !empty($monsterWeapon->debuffs)) {
                        foreach ($monsterWeapon->debuffs as $debuff) {
                            $chance = $debuff['chance'] ?? 100;
                            $duration = $debuff['duration'] ?? 1;

                            if (rand(1, 100) <= $chance) {
                                if ($debuff['key'] === 'stun') {
                                    $this->character->applyDebuff($debuff['key'], $duration, 1);
                                } else {
                                    $this->character->applyDebuff($debuff['key'], $duration, 0);
                                }

                                $msg = __('messages.debuff_applied_to_you', [
                                    'name' => $this->monster->name,
                                    'debuff' => __('debuffs.' . $debuff['key'] . '.name'),
                                ]);
                                $this->messages[] = $msg;
                                $this->character->log($msg);

                                $this->dispatch('$refresh');
                            }
                        }
                    }


                    $this->character->current_health = max(0, $this->character->current_health - $monsterDamage);
                    $this->character->save();

                    if ($monsterDamage > 0) {
                        $msg = __('messages.monster_hit_damage', [
                            'name' => $this->monster->name,
                            'zone' => $possibleZones[$monsterAttack],
                            'damage' => $monsterDamage,
                        ]);
                    } else {
                        $msg = __('messages.monster_hit_no_damage', [
                            'name' => $this->monster->name,
                            'zone' => $possibleZones[$monsterAttack],
                        ]);
                    }

                    if ($isCrit) {
                        $msg .= ' ' . __('messages.critical_hit');
                    }
                    $this->messages[] = $msg;
                    $this->character->log($msg);

                    $amount = $monsterDamage > 0 ? $monsterDamage : 0;
                    $this->dispatch('showHit', [
                        'message' => __('messages.hp_damage', ['amount' => $amount]),
                        'target' => 'player',
                        'type' => $isCrit ? 'crit' : 'hit',
                    ]);
                }
            }
        }

        // ... решта логіки бою і підрахунок результатів
        $this->dispatch('refreshInfoChat');
        $this->updateMonsterEquipped();
        $this->character->adjustCurrentHealth();
        $this->character->wearDownEquippedItems();

        if ($this->character->current_health <= 0 && $this->monster->current_health <= 0) {
            $result = __('messages.battle_draw');
            $xpMultiplier = 0;
            $this->character->draws++;
            $this->character->log($result);
        } elseif ($this->character->current_health <= 0) {
            $result = __('messages.you_lost');
            $xpMultiplier = 0.5;
            $this->character->losses++;
            $this->character->log($result);
        } elseif ($this->monster->current_health <= 0) {
            $result = __('messages.you_won', ['name' => $this->monster->name]);

            if ($this->character->level >= 1) {  // Перевірка рівня персонажа
                $drop = DropService::generateDrop(100, $this->monster->level);

                if ($drop) {
                    // Динамічний урон для зброї
                    if ($drop->type === 'weapon') {
                        [$minDamage, $maxDamage] = WeaponDamageService::getDamageRange($drop->type, $drop->level);
                        $drop->min_damage = $minDamage;
                        $drop->max_damage = $maxDamage;
                    }

                    $price = ItemPricingService::calculate($drop->bonuses, $drop->rarity, $drop->level);
                    $maxDurability = $this->character->getMaxDurabilityForItem($drop);

                    $this->character->inventoryItems()->attach($drop->id, [
                        'current_durability' => $maxDurability,
                        'max_durability' => $maxDurability,
                        'slot' => null,
                        'rarity' => $drop->rarity,
                        'location' => 'inventory',
                        'bonuses' => json_encode($drop->bonuses),
                        'level' => $drop->level,
                        'sell_price' => $price,
                        'min_damage' => $drop->min_damage,
                        'max_damage' => $drop->max_damage,
                        'defense_by_zone' => $drop->defense_by_zone ? json_encode($drop->defense_by_zone) : null,
                    ]);

                    $this->character->log(__('messages.item_received', [
                        'name' => __('items.' . $drop->key . '.name'),
                        'level' => $drop->level,
                        'rarity' => $drop->rarity,
                    ]));
                }
            }

            $xpMultiplier = 1.2;
            $this->character->wins++;
            $this->character->log($result);
        }
        else {
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
                $this->character->log(__('messages.xp_gained', ['xp' => $xpGained]));
            }

            $newLevel = $this->character->level;
            $leveledUp = $newLevel > $oldLevel;

            if ($xpMultiplier === 1.2) {
                $goldGained = $this->monster->gold ?? 0;
                $this->character->gold += $goldGained;
                $this->character->save();
                $this->character->log(__('messages.gold_gained', ['amount' => $goldGained]));
            }

            $this->resetBattle();
            return redirect()->route('history');
        }

        $this->attackChoice = null;
        $this->defenseChoice = null;
    }

    protected function processBleedingDebuff()
    {
        if (!empty($this->monster->debuffs['bleeding']) &&
            empty($this->monster->debuffs['bleeding']['delay']) &&
            $this->monster->debuffs['bleeding']['duration'] >= 0)
        {
            // 15% від поточного здоровʼя
            $bleedingDamage = max(1, ceil($this->monster->current_health * 0.15));

            $this->monster->current_health = max(0, $this->monster->current_health - $bleedingDamage);
            $this->monster->save();

            $msg = __('messages.bleeding_damage', [
                'name' => $this->monster->name,
                'damage' => $bleedingDamage,
            ]);

            $this->messages[] = [
                'text' => $msg,
                'type' => 'bleeding',
            ];
            $this->character->log($msg, 'bleeding');

            $this->dispatch('showHit', [
                'message' => __('messages.hp_damage', ['amount' => $bleedingDamage]),
                'target' => 'monster',
                'type' => 'bleeding',
            ]);
        }
    }


    private function checkStun()
    {
        if (!empty($this->character->debuffs['stun']) && ($this->character->debuffs['stun']['delay'] ?? 0) == 1) {
            // Автовибір зон
            $this->attackChoice = 'head';
            $this->defenseChoice = 'head_chest';
        }
    }

    private function resetBattle()
    {
        $this->character->is_in_battle = false;
        $this->character->debuffs = [];

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
