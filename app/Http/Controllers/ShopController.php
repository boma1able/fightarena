<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('shop', compact('items'));
    }
}