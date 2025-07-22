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

                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-helmet.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['helmet']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['helmet']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-helmet.png') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-armor.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['armor']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['armor']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-armor.png') }}) center center no-repeat; background-size: cover; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['ring1']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['ring1']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-weapon.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['weapon']->pivot->id }})"
                                    class="{{ $rarityClass }} relative w-[60px] h-[90px] {{ $isBroken ? 'broken' : '' }}"
                                    title="{{ trim($title) }}"
                                    >

                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['weapon']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-weapon.png') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-legs.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['legs']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['legs']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-legs.png') }}) center center no-repeat; background-size: cover; "></span>
                            @endif
                        </div>

                    </div>
                    <div class="w-[205px] h-full mx-[10px]">
                        <h2 class="block w-full text-center mb-3"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</h2>
                        @livewire('health-regen')
                        <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                            <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                            <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                        </div>
                        <div class="avatar w-[205px] h-[410px]" style="background: url({{ asset('images/avatar-female-full.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]"></div>
                        <div id="banner" class="w-[165px] h-[50px]"></div>
                    </div>

                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-neckless.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['neckless']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['neckless']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-neckless.png') }}) center center no-repeat; background-size: contain;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-arms.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['arms']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['arms']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-arms.png') }}) center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['ring2']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['ring2']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-shield.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['shield']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['shield']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-shield.png') }}) center center no-repeat; background-size: cover; background-size: cover; "></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-boots.png') }}) center center no-repeat; background-size: cover;"
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
                                <div wire:click="unequipItem({{ $equippedBySlot['boots']->pivot->id }})"
                                    class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($equippedBySlot['boots']->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/empty-boots.png') }}) center center no-repeat; background-size: cover; "></span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="min-w-[320px]">
                <h2 class="mb-2 mt-1 p-2 font-semibold bg-[#f9f9f9]">Characteristic</h2>

                @php
                    $stats = ['strength' => 'Сила', 'agility' => 'Спритність', 'intuition' => 'Інтуіція', 'endurance' => 'Витривалість'];
                @endphp

                @livewire('stat-points', ['character' => $character])

                <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9]">Information</h2>
                <ul class="mb-2 px-2">
                    <li>Рівень:</> {{ $character->level }} ({{ $character->experience }} / {{ $character->getExperienceToLevelUp() }})</li>
                    <li>Золото:</> {{ $character->gold }}</li>
                </ul>

                @livewire('character-modificators', ['character' => $character])

                @livewire('character-armor', ['character' => $character])

                <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9]">Battle statistics</h2>
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
                    <li>Перемоги: {{ $character->wins }}</li>
                    <li>Програші: {{ $character->losses }}</li>
                    <li>Нічиї: {{ $character->draws }}</li>
                </ul>
            </div>

            <div class="w-[700px] ml-[auto]">

                <h2 class="text-xl font-bold mb-2">Inventory</h2>

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
                            All
                        </button>

                        @foreach($allTypes as $type)
                            <button
                                wire:click="setFilter('{{ addslashes($type) }}')"
                                class="px-3 py-1 rounded border text-sm {{ $filterType === $type ? 'bg-blue-500 text-white' : 'bg-gray-100' }}"
                            >
                                {{ ucfirst($type) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-wrap flex-col min-h-[400]">
                    @forelse($inventory as $item)
                        @php
                            $rarityClass = $rarityColors[$item->pivot->rarity] ?? 'gray';
                        @endphp
                        <div id="item-{{ $item->id }}" class="flex odd:bg-[#f9f9f9] group transition-w duration-300 mb-[3px]">

                            <div class="relative w-[10px] overflow-hidden group-hover:w-[80px] transition-w duration-300">
                                <div class="flex flex-col items-center justify-center gap-1 absolute w-[8px] h-full left-0 top-0 text-white bg-black z-10">
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                </div>
                                <div
                                    class="{{ $rarityClass }} flex flex-col items-center justify-center h-full gap-[15px]">
                                    <button
                                        wire:click="equipItem({{ $item->pivot->id }})"
                                        title="Викинути"
                                        >
                                        <svg width="20" height="20" viewBox="0 0 482 321" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.946" fill-rule="evenodd" clip-rule="evenodd" d="M230.5 0.500003C260.16 -2.4355 279.326 10.231 288 38.5C291.266 58.04 285.433 74.207 270.5 87C264.217 90.721 258.05 94.554 252 98.5C249.079 105.513 247.913 112.846 248.5 120.5C264.308 120.154 277.141 126.154 287 138.5C291.374 145.616 295.374 152.949 299 160.5C302.534 164.369 306.367 167.869 310.5 171C362.329 200.08 413.996 229.414 465.5 259C474.751 266.667 479.917 276.501 481 288.5C481.667 296.833 481.667 305.167 481 313.5C479.244 319.964 475.077 322.13 468.5 320C463.833 317.667 459.167 315.333 454.5 313C311.833 312.333 169.167 312.333 26.5 313C21.8333 315.333 17.1667 317.667 12.5 320C9.03569 320.817 5.70239 320.483 2.49999 319C1.87419 318.25 1.37419 317.416 0.999995 316.5C0.333295 304.833 0.333295 293.167 0.999995 281.5C3.40689 272.428 8.24029 264.928 15.5 259C67.0042 229.414 118.671 200.08 170.5 171C174.633 167.869 178.466 164.369 182 160.5C186.453 148.718 193.286 138.551 202.5 130C211.36 123.325 221.36 120.158 232.5 120.5C228.214 98.229 236.547 83.063 257.5 75C270.435 65.527 274.602 53.027 270 37.5C261.619 20.396 248.119 14.229 229.5 19C220.667 22.5 214.5 28.667 211 37.5C210.233 43.567 208.733 49.4 206.5 55C202.219 57.109 198.052 56.942 194 54.5C191.554 26.757 203.72 8.757 230.5 0.500003ZM225.5 137.5C235.506 137.334 245.506 137.5 255.5 138C261.998 139.497 267.498 142.663 272 147.5C276.374 154.616 280.374 161.949 284 169.5C287.465 173.967 291.298 178.133 295.5 182C349.329 212.414 402.996 243.08 456.5 274C462.896 280.692 464.896 288.525 462.5 297.5C398.167 264.333 333.833 231.167 269.5 198C250.167 190.667 230.833 190.667 211.5 198C146.588 231.372 81.7549 264.872 17 298.5C15.467 288.481 18.3003 279.981 25.5 273C77.8333 243.333 130.167 213.667 182.5 184C187.667 180.167 192.167 175.667 196 170.5C200 163.167 204 155.833 208 148.5C212.88 143.152 218.714 139.486 225.5 137.5ZM230.5 209.5C241.876 208.45 252.876 209.95 263.5 214C316.167 241.333 368.833 268.667 421.5 296C300.833 296.667 180.167 296.667 59.5 296C109.5 270 159.5 244 209.5 218C216.331 214.453 223.331 211.619 230.5 209.5Z" fill="#fff"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="sellItem({{ $item->pivot->id }})"
                                        title="Продати"
                                        >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.0006 9.37657C14.7569 9.37657 14.5597 9.18903 14.4885 8.95598C14.3701 8.56829 14.1413 8.24663 13.8021 7.99099C13.347 7.64799 12.7748 7.47649 12.0854 7.47649C11.5998 7.47649 11.1719 7.55969 10.8017 7.7261C10.4349 7.8925 10.1463 8.12343 9.93573 8.41889C9.72857 8.71095 9.62499 9.04376 9.62499 9.41732C9.62499 9.6924 9.68442 9.93352 9.80328 10.1407C9.92214 10.3478 10.0818 10.5261 10.2821 10.6755C10.4859 10.8216 10.71 10.9472 10.9545 11.0525C11.2024 11.1578 11.4521 11.2461 11.7034 11.3174L12.8037 11.6332C13.1365 11.7249 13.4693 11.8421 13.8021 11.9847C14.1349 12.1274 14.4389 12.3073 14.7139 12.5247C14.9924 12.7386 15.2149 13.0018 15.3813 13.3143C15.5511 13.6233 15.636 13.9952 15.636 14.4299C15.636 14.9868 15.4916 15.4877 15.203 15.9326C14.9143 16.3775 14.5 16.7307 13.96 16.9922C13.4201 17.2503 12.7731 17.3793 12.0192 17.3793C11.3094 17.3793 10.693 17.2621 10.1701 17.0278C9.64706 16.7901 9.23614 16.4624 8.93729 16.0447C8.73631 15.7638 8.59446 15.4529 8.51175 15.1121C8.44467 14.8357 8.67431 14.5929 8.95872 14.5929C9.21434 14.5929 9.41935 14.7922 9.49463 15.0365C9.56373 15.2607 9.6717 15.4609 9.81856 15.6371C10.0563 15.9224 10.3653 16.1398 10.7457 16.2892C11.126 16.4352 11.5505 16.5082 12.0192 16.5082C12.5388 16.5082 13.0006 16.4216 13.4048 16.2484C13.8123 16.0718 14.1315 15.8273 14.3625 15.5149C14.5968 15.1991 14.7139 14.8323 14.7139 14.4146C14.7139 14.0614 14.6222 13.7659 14.4389 13.5282C14.2555 13.2871 13.9991 13.085 13.6697 12.922C13.3436 12.759 12.965 12.6147 12.5337 12.489L11.2856 12.1223C10.4672 11.8777 9.83215 11.5398 9.38048 11.1085C8.9288 10.6772 8.70297 10.1271 8.70297 9.45807C8.70297 8.89773 8.85239 8.4053 9.15124 7.9808C9.45349 7.5529 9.86101 7.22009 10.3738 6.98237C10.89 6.74125 11.469 6.62069 12.1109 6.62069C12.7595 6.62069 13.3335 6.73955 13.8327 6.97727C14.3319 7.215 14.7275 7.54271 15.0196 7.96042C15.221 8.24289 15.3585 8.55219 15.4321 8.88832C15.4896 9.15109 15.2696 9.37657 15.0006 9.37657Z" fill="#fff"/>
                                            <path d="M11.5709 5.94828C11.5709 5.7007 11.7716 5.5 12.0192 5.5C12.2668 5.5 12.4675 5.7007 12.4675 5.94828L12.4675 6.84483C12.4675 7.0924 12.2668 7.2931 12.0192 7.2931C11.7716 7.2931 11.5709 7.0924 11.5709 6.84483V5.94828Z" fill="#fff"/>
                                            <path d="M11.5709 17.1552C11.5709 16.9076 11.7716 16.7069 12.0192 16.7069C12.2668 16.7069 12.4675 16.9076 12.4675 17.1552L12.4675 18.0517C12.4675 18.2993 12.2668 18.5 12.0192 18.5C11.7716 18.5 11.5709 18.2993 11.5709 18.0517V17.1552Z" fill="#fff"/>
                                            <circle cx="12" cy="12" r="11.5" stroke="#fff"/>
                                        </svg>
                                    </button>
                                    <button
                                        title="Додати у обране"
                                        >
                                        <svg width="20" height="20" viewBox="0 0 24 35" fill="#fff" style="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M23 2C23 1.44772 22.5523 1 22 1H2C1.44772 1 1 1.44771 1 2V33.1309L11.4453 26.168L11.5752 26.0947C11.8887 25.9475 12.2606 25.9719 12.5547 26.168L23 33.1309V2ZM24 35L12 27L0 35V2C0 0.964349 0.787223 0.113005 1.7959 0.0107422L2 0H22C23.1046 0 24 0.895431 24 2V35Z" fill=""/>
                                            <path d="M14 11H18.7714C19.0094 11 19.1127 11.3012 18.9249 11.4473L15 14.5L16.3005 18.8351C16.3678 19.0594 16.1173 19.2449 15.9224 19.1149L12 16.5L8.07761 19.1149C7.8827 19.2449 7.63217 19.0594 7.69948 18.8351L9 14.5L5.07515 11.4473C4.88725 11.3012 4.9906 11 5.22863 11H10L11.7651 6.14611C11.8448 5.92669 12.1552 5.92669 12.2349 6.14611L14 11ZM10.9395 11.3418C10.7957 11.7369 10.4205 12 10 12H7.41406L9.61426 13.7109C9.93986 13.9644 10.0765 14.3918 9.95801 14.7871L9.25586 17.127L11.4453 15.668L11.5752 15.5947C11.8887 15.4475 12.2606 15.4719 12.5547 15.668L14.7441 17.127L14.042 14.7871C13.9235 14.3918 14.0601 13.9644 14.3857 13.7109L16.5859 12H14C13.5795 12 13.2043 11.7369 13.0605 11.3418L12 8.4248L10.9395 11.3418Z" fill=""/>
                                        </svg>
                                    </button>
                                    <button title="Викинути">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.84574 20.2112H9.86331C10.1117 20.201 10.304 19.9722 10.2957 19.7012L9.89964 8.96696C9.89027 8.69596 9.67816 8.48888 9.43206 8.49526C9.18363 8.50549 8.99142 8.73431 8.99964 9.00532L9.39573 19.7395C9.40511 20.0041 9.6055 20.2112 9.84574 20.2112Z" fill="#fff"/>
                                            <path d="M13.9345 20.2071H13.952C14.1923 20.2071 14.3927 20.0012 14.402 19.7353L14.7946 9.00114C14.804 8.73013 14.6118 8.50005 14.3622 8.49108C14.1196 8.48085 13.9028 8.6905 13.8946 8.96278L13.502 19.697C13.4927 19.968 13.6849 20.1981 13.9345 20.2071Z" fill="#fff"/>
                                            <path d="M3.74612 5.68393L5.5637 22.3113C5.66917 23.2739 6.41918 24 7.30628 24H16.4869C17.3752 24 18.1251 23.2739 18.2295 22.3113L20.047 5.68393C21.0103 5.67754 21.7896 5.36123 21.7896 4.31042V3.72137C21.7896 2.66418 21.0009 2.353 20.0341 2.353H16.7164V1.91495C16.7164 0.861591 15.9278 0 14.961 0H8.82866C7.86302 0 7.07318 0.860314 7.07318 1.91495V2.353H3.75548C2.78984 2.353 2 2.66418 2 3.72137V4.31042C2 5.3612 2.78048 5.67624 3.74258 5.68393H3.74612ZM17.3364 22.1935C17.2849 22.6652 16.9192 23.018 16.488 23.018H7.30739C6.87497 23.018 6.50933 22.6652 6.45896 22.1935L4.65311 5.68393H19.1434L17.3376 22.1935H17.3364ZM7.97672 1.91616C7.97672 1.40228 8.36108 0.982969 8.8322 0.982969H14.9645C15.4356 0.982969 15.82 1.40224 15.82 1.91616V2.35421H7.97768L7.97672 1.91616ZM2.90372 3.72137C2.90372 3.20748 3.28808 3.33482 3.7592 3.33482H20.0378C20.5089 3.33482 20.8933 3.20745 20.8933 3.72137V4.31042C20.8933 4.82431 20.5089 4.70224 20.0378 4.70224H3.7592C3.28811 4.70224 2.90372 4.82434 2.90372 4.31042V3.72137Z" fill="#fff"/>
                                            </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="item flex flex-col w-[200px] items-center justify-center py-4">
                                @php
                                    $isBroken = $item->pivot?->current_durability === 0;
                                @endphp
                                <div
                                    wire:click="equipItem({{ $item->pivot->id }})" class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px] cursor-pointer"
                                    title="Екіпірувати">
                                    <img src="{{ asset('images/items/frame-'.  ($rarityColors[$item->pivot->rarity] ?? 'gray') . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="{{ $item->name }}">
                                    <img src="{{ asset($item->image) }}"
                                        class="absolute w-[60px]"
                                        class="absolute w-[90px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>

                            </div>

                            <div class="w-full flex flex-col p-2">
                                <h3 class="font-semibold mb-1 item-name">{{ $item->name }} [{{ $item->pivot->level }}]</h3>
                                @if($item->pivot->min_damage)
                                    <p>Урон: {{ $item->pivot->min_damage }}–{{ $item->pivot->max_damage }}</p>
                                @endif
                                @foreach($item->defense_by_zone as $zone => $range)
                                    <p>Броня {{ $labels_ua[$zone] ?? ucfirst($zone) }}: {{ $range['min'] }} – {{ $range['max'] }}</p>
                                @endforeach

                                @foreach(json_decode($item->pivot->bonuses ?? '{}', true) as $stat => $value)
                                    <p>{{ $labels_ua[$stat] ?? ucfirst($stat) }}: +{{ $value }}</p>
                                @endforeach
                                <p class="{{ $isBroken ? 'text-red-500 underline' : '' }}">Міцність: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                                <p @class(['!text-red-500' => $character->level < $item->pivot->level])>
                                    {{ $character->level < $item->pivot->level ? 'Мінімальний рівень: ' : 'Рівень: ' }}{{ $item->pivot->level }}
                                </p>
                                <p>Ціна продажу: {{ number_format($item->getDurabilityAdjustedSellPrice($item->pivot), 2) }} золота</p>
                                <p class="mt-2 text-[14px] font-thin italic">{{ $item->description }}</p>
                            </div>

                        </div>
                    @empty
                        <p class="text-gray-500">Немає предметів.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p>You don't have any character</p>
    @endif
</div>
