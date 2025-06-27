<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $character = Character::where('user_id', Auth::id())->first();

        if (auth()->check() && auth()->user()->character->is_in_battle && !request()->routeIs('battle')) {
            return redirect()->route('battle')->with('message', 'Ви не можете залишити бій поки він не завершений');
        }

        if ($character) {
            $characterExpPercent = round(
                $character->experience / max(1, $character->getExperienceToLevelUp()) * 100
            );
        } else {
            $characterExpPercent = 0;
        }

        return view('home', compact('character', 'characterExpPercent'));
    }
}
