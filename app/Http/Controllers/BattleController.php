<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use Illuminate\Http\Request;

class BattleController extends Controller
{
    public function index()
    {
        $character = Character::where('user_id', Auth::id())->firstOrFail();

        return view('battle', compact('character'));
    }

    public function stats(Request $request)
    {
        $battleStats = $request->session()->get('battle_stats');

        if (!$battleStats) {
            return redirect()->route('home')->with('error', 'Немає статистики бою.');
        }

        return view('battle.stats', compact('battleStats'));
    }
}
