<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Character;

class CharacterArmor extends Component
{
    public Character $character;
    public array $armorBonuses = [];

    protected $listeners = [
        'item-equipped' => 'refreshCharacter',
        'item-unequipped' => 'refreshCharacter',
        'update-armor' => 'refreshCharacter',
    ];

    public function mount()
    {
        $this->calculateArmorBonuses();
    }

    public function refreshCharacter()
    {
        $this->character->refresh();
        $this->calculateArmorBonuses();
    }

    public function calculateArmorBonuses()
    {
        $this->armorBonuses = [];
        $this->armorByZone = [];

        foreach ($this->character->equippedArmor() as $item) {
            // Бонуси (сила, витривалість і т.д.)
            if (is_array($item->bonuses)) {
                foreach ($item->bonuses as $key => $value) {
                    $this->armorBonuses[$key] = ($this->armorBonuses[$key] ?? 0) + $value;
                }
            }

            // Захист по зонах
            if (is_array($item->defense_by_zone)) {
                foreach ($item->defense_by_zone as $zone => $range) {
                    $this->armorByZone[$zone]['min'] = ($this->armorByZone[$zone]['min'] ?? 0) + $range['min'];
                    $this->armorByZone[$zone]['max'] = ($this->armorByZone[$zone]['max'] ?? 0) + $range['max'];
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.character-armor', [
            'armorBonuses' => $this->armorBonuses,
            'armorByZone' => $this->armorByZone,
        ]);
    }
}

