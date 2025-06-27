<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class InfoChat extends Component
{
    public $lines = [];

    protected $listeners = ['refreshInfoChat' => 'loadLogs'];

    public function mount()
    {
        $this->loadLogs();
    }

    public function loadLogs()
    {
        $character = auth()->user()->character;
        $filePath = "logs/character_{$character->id}.log";

        if (Storage::disk('local')->exists($filePath)) {
            $allLines = explode("\n", Storage::disk('local')->get($filePath));
            $this->lines = array_slice(array_reverse($allLines), 0, 30);
        } else {
            $this->lines = [];
        }
    }

    public function render()
    {
        return view('livewire.info-chat');
    }
}


