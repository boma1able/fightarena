<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $character = Character::where('user_id', Auth::id())->first();

        $equippedItems = $character->equippedItems()
            ->withPivot('id', 'current_durability', 'max_durability')
            ->get()
            ->groupBy('pivot.slot');

        if (auth()->check() && auth()->user()->character->is_in_battle && !request()->routeIs('battle')) {
            return redirect()->route('battle')->with('message', 'Ви не можете залишити бій поки він не завершений');
        }

        $characterExpPercent = $character
        ? round($character->experience / max(1, $character->getExperienceToLevelUp()) * 100)
        : 0;

        return view('home', [
            'character' => $character,
            'equippedBySlot' => $equippedItems->map->first(),
            'characterExpPercent' => $characterExpPercent,
        ]);
    }
}
