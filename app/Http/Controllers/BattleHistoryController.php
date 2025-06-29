<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BattleHistoryController extends Controller
{
    public function index()
    {
        $character = auth()->user()->character;

        return view('history', ['character' => $character]);
    }
}
