<?php

namespace App\Livewire;

use App\Models\Character;
use Livewire\Component;

class CharacterModificators extends Component
{
    public Character $character;

    protected $listeners = [
        'stat-updated' => 'refreshStats',
        'statUpdated' => 'refreshStats'
    ];

    public function refreshStats()
    {
        $this->character->refresh();
    }

    public function render()
    {
        $damageRange = $this->character->totalDamageRange;
        $criticalDamageRange = $this->character->criticalDamageRange;

        return view('livewire.character-modificators', [
            'character' => $this->character,
            'damageRange' => $damageRange,
            'criticalDamageRange' => $criticalDamageRange,
        ]);
    }
}

