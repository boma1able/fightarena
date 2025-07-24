<?php

namespace App\Livewire;

use Livewire\Component;

class Tooltip extends Component
{
    public string $text = '';
    public bool $visible = false;

    protected $listeners = [
        'showTooltip' => 'show',
        'hideTooltip' => 'hide',
    ];

    public function show(string $text)
    {
        $this->text = $text;
        $this->visible = true;
    }

    public function hide()
    {
        $this->text = '';
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.tooltip');
    }
}
