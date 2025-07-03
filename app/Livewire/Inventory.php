<?php

namespace App\Livewire;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Inventory extends Component
{
    public $character;
    public $characterExpPercent = 0;
    public $allTypes = [];
    public string $filterType = 'all';

    public function mount()
    {
        $this->character = auth()->user()->character;

        if ($this->character) {
            $this->characterExpPercent = round(
                $this->character->experience / max(1, $this->character->getExperienceToLevelUp()) * 100
            );

            $this->allTypes = $this->character
                ->inventoryItems()
                ->pluck('type')
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        }
    }

    public function equipItem($pivotId)
    {
        $itemRow = DB::table('character_items')->find($pivotId);

        if (!$itemRow || $itemRow->location !== 'inventory') return;

        $item = Item::findOrFail($itemRow->item_id);

        if ($this->character->level < $item->required_level) {
            $this->dispatch('trigger-toast', [
                'message' => 'Ваш рівень замалий для екіпірування цього предмета!',
                'type' => 'error',
            ]);
            return;
        }

        if ($item->type === 'ring') {
            $ringSlots = ['ring1', 'ring2'];

            $occupied = $this->character->equippedItems()
                ->whereIn('character_items.slot', $ringSlots)
                ->pluck('character_items.slot')
                ->toArray();

            $freeSlot = collect($ringSlots)->first(fn($slot) => !in_array($slot, $occupied));

            if (!$freeSlot) {
                $this->dispatch('trigger-toast', 'Усі слоти для кілець зайняті!');
                return;
            }

            DB::table('character_items')
                ->where('id', $pivotId)
                ->update(['location' => 'equipped', 'slot' => $freeSlot]);
        } else {
            $slot = $item->slot;

            $alreadyEquipped = $this->character->equippedItems()
                ->where('character_items.slot', $slot)
                ->exists();

            if ($alreadyEquipped) {
                $this->dispatch('trigger-toast', [
                    'message' => "У слоті «{$slot}» вже є предмет!",
                    'type' => 'error',
                ]);
                return;
            }

            DB::table('character_items')
                ->where('id', $pivotId)
                ->update(['location' => 'equipped', 'slot' => $slot]);
        }

        $itemsCount = $this->character->inventoryItems()
            ->when($this->filterType !== 'all', fn($q) => $q->where('type', $this->filterType))
            ->count();

        if ($itemsCount === 0) {
            $this->filterType = 'all';
        }

        $this->refreshCharacter();
        $this->dispatch('trigger-toast', ['message' => 'Предмет одягнуто!', 'type' => 'success']);
    }

    public function unequipItem($pivotId)
    {
        DB::table('character_items')
            ->where('id', $pivotId)
            ->update(['location' => 'inventory', 'slot' => null]);

        $this->refreshCharacter();
        $this->dispatch('trigger-toast', ['message' => 'Предмет знято!', 'type' => 'success']);
    }

    public function refreshCharacter()
    {
        $this->character = $this->character->fresh(['inventoryItems', 'equippedItems']);
        $this->character->adjustCurrentHealth();
        $this->allTypes = $this->character
            ->inventoryItems()
            ->pluck('type')
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        $this->dispatch('statUpdated');
        $this->dispatch('update-armor');
    }

    public function sellItem(int $pivotId)
    {
        $character = $this->character;

        $itemRow = DB::table('character_items')
            ->where('id', $pivotId)
            ->where('character_id', $character->id)
            ->where('location', 'inventory')
            ->first();

        if (!$itemRow) {
            $this->dispatch('trigger-toast', [
                'message' => 'Предмет не знайдено або вже проданий.',
                'type' => 'error',
            ]);
            return;
        }

        // Отримуємо сам предмет
        $item = Item::find($itemRow->item_id);
        if (!$item) {
            $this->dispatch('trigger-toast', [
                'message' => 'Предмет не знайдено в базі.',
                'type' => 'error',
            ]);
            return;
        }

        // Додаємо золото персонажу
        $character->gold += $item->sell_price ?? 1;
        $character->save();

        // Видаляємо рядок з character_items
        DB::table('character_items')->where('id', $pivotId)->delete();

        // Оновлюємо стани
        $this->character = $character->fresh(['inventoryItems', 'equippedItems']);
        $this->allTypes = $this->character->inventoryItems()->pluck('type')->unique()->sort()->values()->toArray();

        $this->dispatch('statUpdated');
        $this->dispatch('trigger-toast', [
            'message' => 'Предмет продано!',
            'type' => 'success',
        ]);
    }

    public function setFilter(string $type): void
    {
        $this->filterType = $type;

        // Перевіряємо, чи є предмети під цим фільтром в інвентарі
        $itemsCount = $this->character->inventoryItems()
            ->when($type !== 'all', fn($q) => $q->where('type', $type))
            ->count();

        if ($itemsCount === 0) {
            $this->filterType = 'all';
        }
    }

    public function render()
    {
        $character = $this->character->load(['inventoryItems', 'equippedItems']);

        $query = $this->character->inventoryItems()->newQuery();
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }
        $inventory = $query->get();

        \Log::debug('Поточний фільтр: ' . $this->filterType);
        foreach ($inventory as $item) {
            \Log::debug("Item: ID {$item->id}, Name: {$item->name}, Type: {$item->type}");
        }

        $equippedBySlot = [];
        foreach ($character->equippedItems as $item) {
            $slot = $item->pivot->slot ?? 'unknown';
            $equippedBySlot[$slot] = $item;
        }

        // Визначаємо всі типи предметів в інвентарі
        $allTypes = $character->inventoryItems()
            ->pluck('type')
            ->unique()
            ->values();

        return view('livewire.inventory', [
            // 'inventory' => $character->inventoryItems,
            'inventory' => $inventory,
            'equippedBySlot' => $equippedBySlot,
            'characterExpPercent' => $this->characterExpPercent,
            'allTypes' => $allTypes,
            'filterType' => $this->filterType,
        ])->layout('layouts.app');
    }
}