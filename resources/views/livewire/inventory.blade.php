<div class="bg-white p-6 rounded shadow-md">

    @php
        $slots = [
            'helmet', 'weapon', 'armor',
            'neckless', 'ring1', 'ring2',
            'arms', 'shield', 'legs', 'boots'
        ];
        $labels_ua = [
            'strength' => 'Сила',
            'agility' => 'Спритність',
            'intuition' => 'Інтуіція',
            'endurance' => 'Витривалість',
            'head' => 'Голови',
            'chest' => 'Грудей',
            'belly' => 'Живота',
            'belt' => 'Пояса',
            'legs' => 'Ніг',
        ];
        $rarityColors = [
            'common' => 'gray',
            'uncommon' => 'green',
            'rare' => 'blue',
            'legendary' => 'gold',
        ];
    @endphp

    @if($character)
        <div class="flex mb-4 w-full gap-5">

            <div class="flex flex-col w-full max-w-[362px]">
                <div class="flex">

                    <div
                        class="flex flex-col"
                        style="gap: 10px"
                    >
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-helmet.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['helmet']))
                                @php
                                    $helmet = $equippedBySlot['helmet'];
                                    $title = $helmet->name . ' [' . $helmet->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $helmet->pivot->current_durability . ' / ' . $helmet->pivot->max_durability . "\n";

                                    $bonuses = $helmet->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['helmet']->pivot->rarity] ?? 'gray';
                                    $isBroken = $helmet->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['helmet']->pivot->id }})"
                                    class=" {{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $helmet->toArray(),
                                        [
                                            'name' => __('items.' . $helmet->key . '.name'),
                                            'level' => $helmet->pivot->level,
                                            'current_durability' => $helmet->pivot->current_durability,
                                            'max_durability' => $helmet->pivot->max_durability,
                                            'rarity' => $helmet->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $helmet->getDurabilityAdjustedSellPrice($helmet->pivot),
                                            'description' => __('items.' . $helmet->key . '.description'),
                                            'image_url' => asset($helmet->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['helmet']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-helmet.jpg') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-armor.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['armor']))
                                @php
                                    $armor = $equippedBySlot['armor'];
                                    $title = $armor->name . ' [' . $armor->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $armor->pivot->current_durability . ' / ' . $armor->pivot->max_durability . "\n";

                                    foreach ($armor->defense_by_zone ?? [] as $zone => $range) {
                                        $title .= 'Броня ' . ($labels_ua[$zone] ?? ucfirst($zone)) . ': ' . $range['min'] . '–' . $range['max'] . "\n";
                                    }
                                    $bonuses = $armor->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?: [];
                                    }
                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['armor']->pivot->rarity] ?? 'gray';
                                    $isBroken = $armor->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['armor']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $armor->toArray(),
                                        [
                                            'name' => __('items.' . $armor->key . '.name'),
                                            'level' => $armor->pivot->level,
                                            'current_durability' => $armor->pivot->current_durability,
                                            'max_durability' => $armor->pivot->max_durability,
                                            'rarity' => $armor->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $armor->getDurabilityAdjustedSellPrice($armor->pivot),
                                            'description' => __('items.' . $armor->key . '.description'),
                                            'image_url' => asset($armor->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['armor']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-armor.jpg') }}) center center no-repeat; background-size: cover; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['ring1']))
                                @php
                                    $ring1 = $equippedBySlot['ring1'];
                                    $title = $ring1->name . ' [' . $ring1->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $ring1->pivot->current_durability . ' / ' . $ring1->pivot->max_durability . "\n";

                                    $bonuses = $ring1->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['ring1']->pivot->rarity] ?? 'gray';
                                    $isBroken = $ring1->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['ring1']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $ring1->toArray(),
                                        [
                                            'name' => __('items.' . $ring1->key . '.name'),
                                            'level' => $ring1->pivot->level,
                                            'current_durability' => $ring1->pivot->current_durability,
                                            'max_durability' => $ring1->pivot->max_durability,
                                            'rarity' => $ring1->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $ring1->getDurabilityAdjustedSellPrice($ring1->pivot),
                                            'description' => __('items.' . $ring1->key . '.description'),
                                            'image_url' => asset($ring1->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['ring1']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-ring.jpg') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-weapon.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['weapon']))
                                @php
                                    $weapon = $equippedBySlot['weapon'];
                                    $title = $weapon->name;

                                    $level = $weapon->pivot->level ?? $weapon->level;
                                    $minDamage = $weapon->pivot->min_damage ?? $weapon->min_damage;
                                    $maxDamage = $weapon->pivot->max_damage ?? $weapon->max_damage;
                                    $currentDurability = $weapon->pivot->current_durability ?? $weapon->current_durability;
                                    $maxDurability = $weapon->pivot->max_durability ?? $weapon->max_durability;

                                    $title .= ' [' . $level . ']' . "\n";
                                    $title .= 'Урон: ' . $minDamage . '–' . $maxDamage . "\n";
                                    $title .= 'Міцність: ' . $currentDurability . ' / ' . $maxDurability . "\n";

                                    $bonuses = $weapon->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['weapon']->pivot->rarity] ?? 'gray';
                                    $isBroken = $weapon->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['weapon']->pivot->id }})"
                                    class="relative w-full h-full {{ $isBroken ? 'broken' : '' }} cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $weapon->toArray(),
                                        [
                                            'name' => __('items.' . $weapon->key . '.name'),
                                            'level' => $weapon->pivot->level,
                                            'current_durability' => $weapon->pivot->current_durability,
                                            'max_durability' => $weapon->pivot->max_durability,
                                            'rarity' => $weapon->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $weapon->getDurabilityAdjustedSellPrice($weapon->pivot),
                                            'description' => __('items.' . $weapon->key . '.description'),
                                            'image_url' => asset($weapon->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >

                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['weapon']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-weapon.jpg') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-legs.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['legs']))
                                @php
                                    $legs = $equippedBySlot['legs'];
                                    $title = $legs->name . ' [' . $legs->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $legs->pivot->current_durability . ' / ' . $legs->pivot->max_durability . "\n";

                                    $bonuses = $legs->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['legs']->pivot->rarity] ?? 'gray';
                                    $isBroken = $legs->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['legs']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $legs->toArray(),
                                        [
                                            'name' => __('items.' . $legs->key . '.name'),
                                            'level' => $legs->pivot->level,
                                            'current_durability' => $legs->pivot->current_durability,
                                            'max_durability' => $legs->pivot->max_durability,
                                            'rarity' => $legs->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $legs->getDurabilityAdjustedSellPrice($legs->pivot),
                                            'description' => __('items.' . $legs->key . '.description'),
                                            'image_url' => asset($legs->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['legs']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-legs.jpg') }}) center center no-repeat; background-size: cover; "></span>
                            @endif
                        </div>

                    </div>
                    <div class="w-[205px] h-full mx-[10px]">
                        <h2 class="block w-full text-center mb-3 text-xl"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</h2>
                        @livewire('health-regen')
                        <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                            <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                            <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                        </div>
                        <div class="relative avatar w-[205px] h-[410px] mt-1" style="background: url({{ asset('images/avatar-' . $character->user->gender . '-art.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]">
                            <img
                                class="absolute top-0 left-0 scale-[1.02]"
                                src="{{ asset('images/cover-frame.png') }}"
                                alt=""
                            >
                        </div>
                        <div id="banner" class="w-[165px] h-[50px]"></div>

                    </div>

                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-neckless.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['neckless']))
                                @php
                                    $neckless = $equippedBySlot['neckless'];
                                    $title = $neckless->name . ' [' . $neckless->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $neckless->pivot->current_durability . ' / ' . $neckless->pivot->max_durability . "\n";

                                    $bonuses = $neckless->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['neckless']->pivot->rarity] ?? 'gray';
                                    $isBroken = $neckless->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['neckless']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $neckless->toArray(),
                                        [
                                            'name' => __('items.' . $neckless->key . '.name'),
                                            'level' => $neckless->pivot->level,
                                            'current_durability' => $neckless->pivot->current_durability,
                                            'max_durability' => $neckless->pivot->max_durability,
                                            'rarity' => $neckless->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $neckless->getDurabilityAdjustedSellPrice($neckless->pivot),
                                            'description' => __('items.' . $neckless->key . '.description'),
                                            'image_url' => asset($neckless->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['neckless']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-neckless.jpg') }}) center center no-repeat; background-size: contain;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-arms.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['arms']))
                                @php
                                    $arms = $equippedBySlot['arms'];
                                    $title = $arms->name . ' [' . $arms->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $arms->pivot->current_durability . ' / ' . $arms->pivot->max_durability . "\n";

                                    $bonuses = $arms->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['arms']->pivot->rarity] ?? 'gray';
                                    $isBroken = $arms->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['arms']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $arms->toArray(),
                                        [
                                            'name' => __('items.' . $arms->key . '.name'),
                                            'level' => $arms->pivot->level,
                                            'current_durability' => $arms->pivot->current_durability,
                                            'max_durability' => $arms->pivot->max_durability,
                                            'rarity' => $arms->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $arms->getDurabilityAdjustedSellPrice($arms->pivot),
                                            'description' => __('items.' . $arms->key . '.description'),
                                            'image_url' => asset($arms->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['arms']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-arms.jpg') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['ring2']))
                                @php
                                    $ring2 = $equippedBySlot['ring2'];
                                    $title = $ring2->name . ' [' . $ring2->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $ring2->pivot->current_durability . ' / ' . $ring2->pivot->max_durability . "\n";

                                    $bonuses = $ring2->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['ring2']->pivot->rarity] ?? 'gray';
                                    $isBroken = $ring2->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['ring2']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $ring2->toArray(),
                                        [
                                            'name' => __('items.' . $ring2->key . '.name'),
                                            'level' => $ring2->pivot->level,
                                            'current_durability' => $ring2->pivot->current_durability,
                                            'max_durability' => $ring2->pivot->max_durability,
                                            'rarity' => $ring2->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $ring2->getDurabilityAdjustedSellPrice($ring2->pivot),
                                            'description' => __('items.' . $ring2->key . '.description'),
                                            'image_url' => asset($ring2->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['ring2']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-ring.jpg') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-shield.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['shield']))
                                @php
                                    $shield = $equippedBySlot['shield'];
                                    $title = $shield->name . ' [' . $shield->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $shield->pivot->current_durability . ' / ' . $shield->pivot->max_durability . "\n";

                                    $bonuses = $shield->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['shield']->pivot->rarity] ?? 'gray';
                                    $isBroken = $shield->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['shield']->pivot->id }})"
                                    class="{{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $shield->toArray(),
                                        [
                                            'name' => __('items.' . $shield->key . '.name'),
                                            'level' => $shield->pivot->level,
                                            'current_durability' => $shield->pivot->current_durability,
                                            'max_durability' => $shield->pivot->max_durability,
                                            'rarity' => $shield->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $shield->getDurabilityAdjustedSellPrice($shield->pivot),
                                            'description' => __('items.' . $shield->key . '.description'),
                                            'image_url' => asset($shield->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['shield']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-shield.jpg') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-boots.jpg') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['boots']))
                                @php
                                    $boots = $equippedBySlot['boots'];
                                    $title = $boots->name . ' [' . $boots->pivot->level . ']' . "\n"
                                        . 'Міцність: ' . $boots->pivot->current_durability . ' / ' . $boots->pivot->max_durability . "\n";

                                    $bonuses = $boots->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$equippedBySlot['boots']->pivot->rarity] ?? 'gray';
                                    $isBroken = $boots->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="unequipItem({{ $equippedBySlot['boots']->pivot->id }})"
                                    class=" {{ $isBroken ? 'broken' : '' }} relative w-full h-full cursor-pointer"
                                    onmouseenter="showItemTooltip(event, @js(array_merge(
                                        $boots->toArray(),
                                        [
                                            'name' => __('items.' . $boots->key . '.name'),
                                            'level' => $boots->pivot->level,
                                            'current_durability' => $boots->pivot->current_durability,
                                            'max_durability' => $boots->pivot->max_durability,
                                            'rarity' => $boots->pivot->rarity,
                                            'durabilityAdjustedSellPrice' => $boots->getDurabilityAdjustedSellPrice($boots->pivot),
                                            'description' => __('items.' . $boots->key . '.description'),
                                            'image_url' => asset($boots->image),
                                        ]
                                    )))"
                                    onmouseleave="hideItemTooltip()"
                                    onmousemove="moveItemTooltip(event)"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '-art.jpg') }}"
                                        class="absolute w-full h-full"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['boots']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-boots.jpg') }}) center center no-repeat; background-size: cover; "></span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="min-w-[320px]">
                <h2 class="mb-2 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.characteristic') }}</h2>

                @php
                    $stats = ['strength' => 'Сила', 'agility' => 'Спритність', 'intuition' => 'Інтуіція', 'endurance' => 'Витривалість'];
                @endphp

                @livewire('stat-points', ['character' => $character])

                <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.information') }}</h2>
                <ul class="mb-2 px-2">
                    <li>{{ __('messages.level') }}: {{ $character->level }} ({{ $character->experience }} / {{ $character->getExperienceToLevelUp() }})</li>
                    <li>{{ __('messages.gold') }}: {{ $character->gold }}</li>
                </ul>

                @livewire('character-modificators', ['character' => $character])

                @livewire('character-armor', ['character' => $character])

                <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.battleStatistics') }}</h2>
                @php
                    $totalFights = $character->wins + $character->losses + $character->draws;

                    if ($totalFights > 0) {
                        $percentWins = ($character->wins * 100) / $totalFights;
                        $percentLosses = ($character->losses * 100) / $totalFights;
                        $percentDraws = ($character->draws * 100) / $totalFights;
                    } else {
                        $percentWins = $percentLosses = $percentDraws = 0;
                    }
                @endphp
                <div class="flex w-full h-2 overflow-hidden cursor-pointer" title="Перемоги: {{ $character->wins }}&#10;Нічиї: {{ $character->draws }}&#10;Програші: {{ $character->losses }}">
                    <div class="bg-green-500" style="width: {{ $percentWins }}%"></div>
                    <div class="bg-gray-300" style="width: {{ $percentDraws }}%"></div>
                    <div class="bg-red-500" style="width: {{ $percentLosses }}%"></div>
                </div>
                <ul class="my-2 px-2">
                    <li>{{ __('messages.wins') }}: {{ $character->wins }}</li>
                    <li>{{ __('messages.looses') }}: {{ $character->losses }}</li>
                    <li>{{ __('messages.draws') }}: {{ $character->draws }}</li>
                </ul>
            </div>

            <div class="w-[700px] ml-[auto]">

                <h2 class="mb-2 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.inventory') }}</h2>

                <div
                    x-data="{ show: false, message: '', type: 'info' }"
                    x-init="
                        window.addEventListener('notify', event => {
                            const detail = event.detail;

                            message = typeof detail === 'string'
                                ? detail
                                : (detail.message ?? '???');

                            type = typeof detail === 'object' && detail !== null
                                ? (detail.type ?? 'info')
                                : 'info';

                            show = true;
                            setTimeout(() => show = false, 1500);
                        });
                    "
                    x-show="show"
                    x-transition
                    :class="{
                        'bg-green-100 border-green-500 text-green-700': type === 'success',
                        'bg-red-100 border-red-500 text-red-700': type === 'error',
                        'bg-yellow-100 border-yellow-500 text-yellow-700': type === 'info'
                    }"
                    class="fixed top-5 right-5 border-l-4 p-4 rounded shadow text-sm z-50"
                    style="display: none;"
                >
                    <span x-text="message"></span>
                </div>

                {{-- inventory filter --}}
                <div class="mb-4 inventory-filter">
                    <div class="flex flex-wrap gap-2">
                        <button
                            wire:click="setFilter('all')"
                            class="px-3 py-1 rounded border text-sm {{ $filterType === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100' }}"
                        >
                            {{ __('filters.all') }}
                        </button>

                        @foreach($allTypes as $type)
                            <button
                                wire:click="setFilter('{{ addslashes($type) }}')"
                                class="px-3 py-1 rounded border text-sm {{ $filterType === $type ? 'bg-blue-500 text-white' : 'bg-gray-100' }}"
                            >
                                {{ __('filters.types.' . $type) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- <div class="flex flex-wrap flex-col min-h-[400]"> --}}
                <div class="flex flex-wrap min-h-[400]">
                    @forelse($inventory as $item)
                        @php
                            $rarityClass = $rarityColors[$item->pivot->rarity] ?? 'gray';
                        @endphp

                        <div wire:key="inventory-item-{{ $item->pivot->id }}" class="flex">

                            @php
                                $isBroken = $item->pivot?->current_durability === 0;
                                $rarityColor = $rarityColors[$item->pivot->rarity] ?? 'gray';
                            @endphp

                            <div
                                wire:click="equipItem({{ $item->pivot->id }})"
                                class="{{ $rarityColor }} {{ $isBroken ? 'broken' : '' }} relative w-[68px] h-[98px] cursor-pointer"
                                onmouseenter="showItemTooltip(event, @js(array_merge(
                                    $item->toArray(),
                                    [
                                        'name' => __('items.' . $item->key . '.name'),
                                        'level' => $item->pivot->level,
                                        'current_durability' => $item->pivot->current_durability,
                                        'max_durability' => $item->pivot->max_durability,
                                        'rarity' => $item->pivot->rarity,
                                        'durabilityAdjustedSellPrice' => $item->getDurabilityAdjustedSellPrice($item->pivot),
                                        'description' => __('items.' . $item->key . '.description'),
                                        'image_url' => asset($item->image),
                                    ]
                                )))"
                                onmouseleave="hideItemTooltip()"
                                onmousemove="moveItemTooltip(event)"
                            >
                                <img src="{{ asset('images/items/frame-' . $rarityColor . '-art.jpg') }}"
                                    class="absolute w-full h-full"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%); filter: brightness(1.1);"
                                    alt="{{ $item->name }}">
                                <img src="{{ asset($item->image) }}"
                                    class="item-img absolute w-[60px]"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0,0,0,0.5));"
                                    alt="{{ $item->name }}">
                            </div>
                        </div>

                    @empty
                        <p class="text-gray-500">{{ __('messages.noItems') }}.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p>{{ __('messages.noChar') }}</p>
    @endif

    <div id="item-tooltip"
        class="fixed hidden z-50 text-white shadow-lg max-w-xs pointer-events-none">
    </div>
</div>
