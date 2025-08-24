<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Forge extends Component
{
    public $character;
    public $inventory = [];
    public $filterType = 'all';
    public $allTypes = [];
    public $repairItem;

    public function mount()
    {
        $this->character = auth()->user()->character;
        $this->loadInventory();
    }

    public function loadInventory()
    {
        $query = $this->character->inventoryItems()
            ->wherePivot('location', 'inventory')
            ->wherePivot('slot', null)
            // ->wherePivot('current_durability', '<', DB::raw('max_durability'))
            ->withPivot(['id', 'location', 'slot', 'current_durability', 'max_durability']);

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $this->inventory = $query->get();
        // $this->allTypes = Item::distinct()->pluck('type')->toArray();
        $this->allTypes = $this->character->inventoryItems()
            ->wherePivot('location', 'inventory')
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();
    }

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->loadInventory();
    }

    public function loadRepairItem()
    {
        $item = $this->character->inventoryItems()
            ->wherePivot('slot', 'repair')
            ->first();

        if ($item && $item->pivot->current_durability >= $item->pivot->max_durability) {
            // Якщо предмет вже відремонтований, прибираємо зі слоту
            DB::table('character_items')
                ->where('id', $item->pivot->id)
                ->update(['slot' => null]);

            $this->repairItem = null;
        } else {
            $this->repairItem = $item;
        }
    }


    public function selectForRepair($pivotId)
    {
        // Зняти предмети, які вже в ремонті (опційно)
        DB::table('character_items')
            ->where('character_id', $this->character->id)
            ->where('slot', 'repair')
            ->update(['slot' => null]);

        // Оновити обраний предмет, перемістити у слот 'repair'
        DB::table('character_items')
            ->where('id', $pivotId)
            ->update(['slot' => 'repair']);

        $this->loadInventory();
        $this->loadRepairItem(); // метод, який знайде обраний предмет у слоті 'repair'
    }

    public function repairSelected()
    {
        if (!$this->repairItem) {
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => 'Немає предмета для ремонту',
            ]);
            return;
        }

        // Оновлюємо repairItem, щоб мати свіжі дані
        $this->repairItem = $this->character->inventoryItems()
            ->wherePivot('slot', 'repair')
            ->first();

        if (!$this->repairItem?->pivot?->id) {
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => 'Немає предмета для ремонту',
            ]);
            return;
        }

        $repairCost = $this->getRepairCostProperty();

        if ($this->character->gold < $repairCost) {
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => 'Недостатньо золота для ремонту!',
            ]);
            return;
        }

        // Знімаємо золото лише раз
        $this->character->decrement('gold', $repairCost);

        // Відновлюємо міцність до максимуму
        DB::table('character_items')
            ->where('id', $this->repairItem->pivot->id)
            ->update([
                'current_durability' => $this->repairItem->pivot->max_durability,
                'is_broken' => 0,
            ]);

        // Звільняємо слот "repair"
        DB::table('character_items')
            ->where('id', $this->repairItem->pivot->id)
            ->update(['slot' => null]);

        $this->repairItem = null;

        $this->loadInventory();
        $this->loadRepairItem();

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => __("Предмет успішно відремонтовано! Витрачено :gold золота.", ['gold' => $repairCost]),
        ]);
    }

    public function getRepairCostProperty()
    {
        if (!$this->repairItem || !$this->repairItem->pivot) {
            return 0;
        }

        $current = $this->repairItem->pivot->current_durability;
        $max = $this->repairItem->pivot->max_durability;

        if ($max === 0 || $current >= $max) {
            return 0;
        }

        // Беремо ціну з pivot
        $sellPrice = $this->repairItem->pivot->sell_price ?? $this->repairItem->sell_price;
        $maxRepairCost = $sellPrice * 0.9;
        $damagePercent = 1 - ($current / $max);

        return round($maxRepairCost * $damagePercent, 2);
    }


    public function cancelRepair()
    {
        DB::table('character_items')
            ->where('character_id', $this->character->id)
            ->where('slot', 'repair')
            ->update(['slot' => null]);

        $this->repairItem = null;
        $this->loadInventory();

        $this->dispatch('trigger-toast', [
            'type' => 'info',
            'message' => 'Ремонт скасовано',
        ]);
    }

    public function render()
    {
        $this->loadRepairItem();
        return view('livewire.forge', [
            'repairItem' => $this->repairItem,
        ]);
    }
}
