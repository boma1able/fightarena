<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;

class Shop extends Component
{
    public string $filterType = 'all';

    public function setFilter(string $type)
    {
        $this->filterType = $type;
    }

    public function getItemsProperty()
    {
        $character = auth()->user()->character;

        $query = $character->allItems()
            ->wherePivot('location', 'shop');

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        return $query->get();
    }

    public function getGroupedTypesProperty()
    {
        return [
            'Зброя' => ['sword', 'axe', 'mace', 'knife'],
            'Броня' => ['helmet', 'armor', 'legs', 'boots', 'belt', 'shoulders', 'shield', 'arms'],
            'Біжутерія' => ['ring', 'neckless', 'earrings'],
        ];
    }

    public function getAllTypesGroupedProperty()
    {
        $character = auth()->user()->character;

        // Отримуємо типи предметів у магазині персонажа
        $types = $character->allItems()
            ->wherePivot('location', 'shop')
            ->pluck('type')
            ->unique();

        $grouped = [];

        foreach ($this->groupedTypes as $label => $typeGroup) {
            $grouped[$label] = $types->filter(fn($t) => in_array($t, $typeGroup))->values();
        }

        return $grouped;
    }


    public function buyItem(int $itemId)
    {
        $item = Item::findOrFail($itemId);
        $character = auth()->user()->character;
        $maxDurability = $character->getMaxDurabilityForItem($item);

        if ($character->gold < $item->buy_price) {
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => 'Недостатньо золота!',
            ]);
            return;
        }

        if ($character->level < $item->required_level) {
            $this->dispatch('trigger-toast', [
                'type' => 'error',
                'message' => 'Ваш рівень занизький для цього предмета.',
            ]);
            return;
        }

        // Віднімаємо золото
        $character->gold -= $item->buy_price;
        $character->save();

        // Додаємо предмет до персонажа
        $character->allItems()->attach($item->id, [
            'location' => 'inventory',
            'current_durability' => $maxDurability,
            'max_durability' => $maxDurability,
        ]);

        $this->dispatch('trigger-toast', [
            'type' => 'success',
            'message' => 'Річ куплена успішно!',
        ]);
    }


    public function render()
    {
        $character = auth()->user()->character;

        return view('livewire.shop', [
            'items' => $this->items,
            'filterType' => $this->filterType,
            'character' => $character,
            'allTypesGrouped' => $this->allTypesGrouped,
        ]);
    }
}
