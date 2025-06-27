<?php

namespace App\Livewire;

use App\Models\Character;
use Livewire\Component;

class HealthRegen extends Component
{
    public Character $character;
    public int $percent = 0;

    protected $listeners = [
        'statUpdated' => 'refreshStats',
    ];

    public function mount()
    {
        $this->character = auth()->user()->character;
        $this->updateHealth();
    }

    public function updateHealth()
    {
        $this->character->regenerateHealthDynamic();
        $this->percent = round(($this->character->current_health / $this->character->max_health) * 100);
    }

    public function render()
    {
        return view('livewire.health-regen');
    }
}