<?php

namespace App\Livewire;

use App\Models\Character;
use Livewire\Component;

class StatPoints extends Component
{
    public $character;

    protected $listeners = [
        'statUpdated' => 'refreshStats',
    ];

    public function mount(Character $character)
    {
        $this->character = Character::with('equippedItems')->find($character->id);
        // $this->character = $character;
    }

    public function incrementStat($stat)
    {
        if ($this->character->stat_points <= 0) {
            return;
        }

        // Зберігаємо відсоток поточного здоров'я перед зміною
        $healthPercent = $this->character->current_health / max(1, $this->character->max_health);

        $this->character->$stat++;
        $this->character->stat_points--;

        // Якщо змінюється витривалість — оновлюємо HP відповідно до відсотка
        if ($stat === 'endurance') {
            $this->character->current_health = (int) round($this->character->max_health * $healthPercent);
        }

        $this->character->save();
        $this->character = $this->character->fresh();

        $this->dispatch('stat-updated');
    }


    public function render()
    {
        // $this->character = Character::with(['equippedItems'])->find($this->character->id);

        return view('livewire.stat-points', [
            'character' => $this->character,
        ]);
    }

}
