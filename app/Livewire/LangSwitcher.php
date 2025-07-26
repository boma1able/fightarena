<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\App;

class LangSwitcher extends Component
{
    public string $locale;

    public function mount()
    {
        $this->locale = auth()->check() ? auth()->user()->locale ?? app()->getLocale() : app()->getLocale();
    }

    public function switchTo(string $locale): void
    {
        if (in_array($locale, ['en', 'uk']) && auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
            App::setLocale($locale);
            $this->locale = $locale;
            $this->redirect(request()->header('Referer') ?? route('home'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.lang-switcher');
    }
}
