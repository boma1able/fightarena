<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TooltipService;

class InventoryController extends Controller
{
    public function index()
    {
        $character = Auth::user()->character;

        $inventory = $character->inventoryItems()->get();
        $equipped = $character->equippedItems()->get();

        $equippedBySlot = [];

        foreach ($equipped as $item) {
            $slot = $item->pivot->slot ?? 'unknown';
            $equippedBySlot[$slot] = $item;
        }

        return view('inventory', compact('character', 'inventory', 'equipped', 'equippedBySlot'));
    }

    public function showTooltipForItem($item)
    {
        $title = TooltipService::makeTitleFromItem($item);
        $this->setHoveredItemTooltip($title);
    }
}
