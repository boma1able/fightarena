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
        $filePath = "logs/character_{$character->user->name}-{$character->id}.log";

        if (Storage::disk('local')->exists($filePath)) {
            $allLines = explode("\n", Storage::disk('local')->get($filePath));
            $recentLines = array_slice(array_reverse($allLines), 0, 30);

            $this->lines = [];

            foreach ($recentLines as $line) {
                if (str_contains($line, '|||')) {
                    [$text, $json] = explode('|||', $line, 2);
                    $meta = json_decode($json, true);
                    $type = $meta['type'] ?? 'normal';
                } else {
                    $text = $line;
                    $type = 'normal';
                }

                $this->lines[] = [
                    'text' => $text,
                    'type' => $type,
                ];
            }
        } else {
            $this->lines = [];
        }
    }

    public function render()
    {
        return view('livewire.info-chat');
    }
}


