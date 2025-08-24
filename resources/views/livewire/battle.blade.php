@php
    $characterPercent = round(($character->current_health / $character->max_health) * 100);
    $monsterPercent = round(($monster->current_health / $monster->base_health) * 100);
    $characterBarColor = $characterPercent < 33 ? 'bg-red-500' : ($characterPercent < 66 ? 'bg-yellow-400' : 'bg-green-500');
    $monsterBarColor = $monsterPercent < 33 ? 'bg-red-500' : ($monsterPercent < 66 ? 'bg-yellow-400' : 'bg-green-500');

    $characterExpPercent = 0;
    if ($character && $character->getExperienceToLevelUp() > 0) {
        $characterExpPercent = round(($character->experience / $character->getExperienceToLevelUp()) * 100);
    }
    $labels_ua = [
        'strength' => 'Сила',
        'agility' => 'Ловкість',
        'intuition' => 'Інтуїція',
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

<div class="w-full p-6 rounded shadow bg-white">
    <div class="flex justify-between mb-6">

        {{-- Персонаж --}}
       <div>
            <div class="flex flex-col w-full max-w-[362px]">

                <div class="flex">
                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-helmet.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['helmet']))
                                @php
                                    $helmet = (object) $equippedBySlot['helmet'];
                                    $helmet->pivot = (object) $helmet->pivot;

                                    $itemName = __('items.' . $helmet->key . '.name');
                                    $title = $itemName . ' [' . $helmet->required_level . ']' . "\n"
                                        . 'Міцність: ' . $helmet->pivot->current_durability . ' / ' . $helmet->pivot->max_durability . "\n";

                                    $bonuses = $helmet->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = $rarityColors[$helmet->pivot->rarity] ?? 'gray';
                                    $isBroken = $helmet->pivot?->current_durability === 0;
                                @endphp

                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}">
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($helmet->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/helmet.png') }}) center center no-repeat; background-size: cover;background-size: 75%; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-armor.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['armor']))
                                @php
                                    $armor = (object) $equippedBySlot['armor'];
                                    $armor->pivot = (object) $armor->pivot;
                                    $itemName = __('items.' . $armor->key . '.name');

                                    $title = $itemName . ' [' . $armor->required_level . ']' . "\n"
                                        . 'Міцність: ' . $armor->pivot->current_durability . ' / ' . $armor->pivot->max_durability . "\n";

                                    $bonuses = $armor->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$armor->pivot->rarity] ?? 'gray';
                                    $isBroken = $armor->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($armor->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/armor.png') }}) center center no-repeat; background-size: cover; background-size: 85%; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['ring1']))
                                @php
                                    $ring1 = (object) $equippedBySlot['ring1'];
                                    $ring1->pivot = (object) $ring1->pivot;
                                    $itemName = __('items.' . $ring1->key . '.name');

                                    $title = $itemName . ' [' . $ring1->required_level . ']' . "\n"
                                        . 'Міцність: ' . $ring1->pivot->current_durability . ' / ' . $ring1->pivot->max_durability . "\n";

                                    $bonuses = $ring1->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$ring1->pivot->rarity] ?? 'gray';
                                    $isBroken = $ring1->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($ring1->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-weapon.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['weapon']))
                                @php
                                    $weapon = (object) $equippedBySlot['weapon'];
                                    $weapon->pivot = (object) $weapon->pivot;
                                    $itemName = __('items.' . $weapon->key . '.name');
                                    $title = $itemName;

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
                                    $rarityClass = $rarityColors[$weapon->pivot->rarity] ?? 'gray';
                                    $isBroken = $weapon->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($weapon->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/weapon.png') }}) center center no-repeat; background-size: 70%; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-legs.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['legs']))
                                @php
                                    $legs = (object) $equippedBySlot['legs'];
                                    $legs->pivot = (object) $legs->pivot;
                                    $itemName = __('items.' . $legs->key . '.name');

                                    $title = $itemName . ' [' . $legs->required_level . ']' . "\n"
                                        . 'Міцність: ' . $legs->pivot->current_durability . ' / ' . $legs->pivot->max_durability . "\n";

                                    $bonuses = $legs->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$legs->pivot->rarity] ?? 'gray';
                                    $isBroken = $legs->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($legs->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/legs.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                            @endif
                        </div>

                    </div>
                    <div class="relative w-[205px] h-full mx-[10px]">
                        <div class="block w-full text-center mb-3">
                            <strong>{{ $character->user->name }}</strong> [{{ $character->level }}]
                        </div>
                        @livewire('health-regen')
                        <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                            <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                            <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                        </div>
                        <div class="relative avatar w-[205px] h-[410px] overflow-hidden group transition-left duration-300"
                            style="background: url({{ asset('images/avatar-' . $character->user->gender . '-art.jpg') }}) center center no-repeat; background-size: cover;"
                            title="{{ $character->user->name }} [{{ $character->level }}]
                        ">
                            @php
                                $zoneLabels = [
                                    'head' => 'Броня голови',
                                    'chest' => 'Броня грудей',
                                    'belly' => 'Броня живота',
                                    'belt' => 'Броня пояса',
                                    'legs' => 'Броня ніг',
                                ];
                            @endphp
                            <div class="char-stats absolute bg-black/60 text-white w-full h-full overflow-auto py-2 top-0 -left-full ml-2 group-hover:left-[0] group-hover:ml-0 transition-w duration-300">

                                <ul class="list-inside px-2">
                                    <li>{{ __('messages.strength') }}: {{ $character->strength }}</li>
                                    <li>{{ __('messages.agility') }}: {{ $character->agility }}</li>
                                    <li>{{ __('messages.intuition') }}: {{ $character->intuition }}</li>
                                    <li>{{ __('messages.endurance') }}: {{ $character->endurance }}</li>
                                </ul>

                                @livewire('character-modificators', ['character' => $character])
                                @livewire('character-armor', ['character' => $character])

                                <div class="flex flex-col items-center justify-center gap-1 absolute w-[8px] h-full right-0 top-0 text-white bg-black z-10">
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                </div>
                            </div>

                        </div>
                        <div
                            x-data="{
                                showPlayerHit: false,
                                playerHitMessage: '',
                                playerHitType: 'hit'
                            }"
                            x-init="window.addEventListener('alpine-hit', e => {
                                playerHitMessage = e.detail.message;
                                playerHitType = e.detail.type;
                                showPlayerHit = true;
                                setTimeout(() => showPlayerHit = false, 1000);
                            })"
                            x-show="showPlayerHit"
                            x-transition
                            class="absolute top-20 left-1/3 transform -translate-x-1/3 text-4xl font-bold z-50"
                            :class="{
                                'text-red-600': playerHitType === 'hit',
                                'text-green-600': playerHitType === 'block',
                                'text-yellow-400': playerHitType === 'crit',
                                'text-blue-500': playerHitType === 'dodge'
                            }"
                        >
                            <span class="battle-info" x-text="playerHitMessage"></span>
                        </div>

                        @if (!empty($character->debuffs))
                            <div class="monster-debuffs flex gap-2 absolute top-[65px] right-[15px]">
                                @foreach ($character->debuffs as $debuffKey => $debuff)
                                    @php
                                        $activeTurns = 0;
                                        if ($debuffKey === 'stun' && isset($debuff['delay']) && $debuff['delay'] > 0) {
                                            $activeTurns = $debuff['delay'];
                                        } elseif ($debuffKey !== 'stun' && isset($debuff['duration']) && $debuff['duration'] > 0) {
                                            $activeTurns = $debuff['duration'];
                                        }
                                    @endphp

                                    @if ($activeTurns > 0)
                                        <div class="debuff-icon relative" title="{{ $debuff['description'] }}">
                                            <img src="{{ $debuff['icon'] ?? '/images/debuffs/default.png' }}" alt="{{ $debuff['name'] }}" class="w-10 h-10">
                                            <span class="absolute w-[15px] h-[15px] flex items-center justify-center bottom-[-5px] right-[-3px] bg-white text-black text-[11px] rounded rounded-[50%] px-1">
                                                {{ $activeTurns }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif


                    </div>

                    <div class="flex flex-col" style="gap: 10px">

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-neckless.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['neckless']))
                                @php
                                    $neckless = (object) $equippedBySlot['neckless'];
                                    $neckless->pivot = (object) $neckless->pivot;
                                    $itemName = __('items.' . $neckless->key . '.name');

                                    $title = $itemName . ' [' . $neckless->required_level . ']' . "\n"
                                        . 'Міцність: ' . $neckless->pivot->current_durability . ' / ' . $neckless->pivot->max_durability . "\n";

                                    $bonuses = $neckless->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$neckless->pivot->rarity] ?? 'gray';
                                    $isBroken = $neckless->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($neckless->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/neckless.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-arms.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['arms']))
                                @php
                                    $arms = (object) $equippedBySlot['arms'];
                                    $arms->pivot = (object) $arms->pivot;
                                    $itemName = __('items.' . $arms->key . '.name');

                                    $title = $itemName . ' [' . $arms->required_level . ']' . "\n"
                                        . 'Міцність: ' . $arms->pivot->current_durability . ' / ' . $arms->pivot->max_durability . "\n";

                                    $bonuses = $arms->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$arms->pivot->rarity] ?? 'gray';
                                    $isBroken = $arms->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($arms->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/arms.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['ring2']))
                                @php
                                    $ring2 = (object) $equippedBySlot['ring2'];
                                    $ring2->pivot = (object) $ring2->pivot;
                                    $itemName = __('items.' . $ring2->key . '.name');

                                    $title = $itemName . ' [' . $ring2->required_level . ']' . "\n"
                                        . 'Міцність: ' . $ring2->pivot->current_durability . ' / ' . $ring2->pivot->max_durability . "\n";

                                    $bonuses = $ring2->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$ring2->pivot->rarity] ?? 'gray';
                                    $isBroken = $ring2->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($ring2->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-shield.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['shield']))
                                @php
                                    $shield = (object) $equippedBySlot['shield'];
                                    $shield->pivot = (object) $shield->pivot;
                                    $itemName = __('items.' . $shield->key . '.name');

                                    $title = $itemName . ' [' . $shield->required_level . ']' . "\n"
                                        . 'Міцність: ' . $shield->pivot->current_durability . ' / ' . $shield->pivot->max_durability . "\n";

                                    $bonuses = $shield->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$shield->pivot->rarity] ?? 'gray';
                                    $isBroken = $shield->pivot?->current_durability === 0;
                                @endphp
                                <div  class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($shield->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/shield.png') }}) center center no-repeat; background-size: cover; background-size: 80%; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-boots.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['boots']))
                                @php
                                    $boots = (object) $equippedBySlot['boots'];
                                    $boots->pivot = (object) $boots->pivot;
                                    $itemName = __('items.' . $boots->key . '.name');

                                    $title = $itemName . ' [' . $boots->required_level . ']' . "\n"
                                        . 'Міцність: ' . $boots->pivot->current_durability . ' / ' . $boots->pivot->max_durability . "\n";

                                    $bonuses = $boots->pivot->bonuses ?? [];
                                    if (is_string($bonuses)) {
                                        $bonuses = json_decode($bonuses, true) ?? [];
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                    $rarityClass = $rarityColors[$boots->pivot->rarity] ?? 'gray';
                                    $isBroken = $boots->pivot?->current_durability === 0;
                                @endphp
                                <div class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($boots->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/boots.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
       </div>

        <div class="w-[600px] bg-white p-4 rounded shadow">

            <h2 class="text-center mb-5 text-bold text-[20px]">{{ __('fight.battleBigan') }}!</h2>

            @if(session('message'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 2000)"
                    x-show="show"
                    x-transition
                    class="fixed !battle-info top-5 right-5 bg-red-100 border-l-4 p-4 border-red-500 text-red-700 mb-4 text-[14px]"
                >
                    {{ session('message') }}
                </div>
            @endif

            @php
                $isStunned = !empty($character->debuffs['stun']) && ($character->debuffs['stun']['delay'] ?? 0) == 1;
            @endphp

            <form wire:submit.prevent="fightStep" class="relative pb-6">

                <div class="flex relative">
                    @if($isStunned)
                        <div class="absolute top-0 left-0 w-full h-full bg-white/50 z-10"></div>
                    @endif

                    <div class="w-1/2">
                        <h3 class="font-semibold mb-2 bg-gray-100 p-1 text-xl">{{ __('fight.attack') }}</h3>

                        @php
                            $attackZones = [
                                'head' => ['uk' => 'Голову', 'en' => 'Head'],
                                'chest' => ['uk' => 'Груди', 'en' => 'Chest'],
                                'belly' => ['uk' => 'Живіт', 'en' => 'Belly'],
                                'belt' => ['uk' => 'Пояс', 'en' => 'Waist'],
                                'legs' => ['uk' => 'Ноги', 'en' => 'Legs'],
                            ];
                        @endphp

                        <div>
                            @foreach($attackZones as $key => $labels)
                                <label class="relative block mr-3 mb-1 text-sm cursor-pointer">
                                    @if($isStunned)<span class="absolute top-[-1px]">🚫</span>@endif
                                    <input type="radio"
                                        wire:model="attackChoice"
                                        name="attackChoice"
                                        value="{{ $key }}"
                                        @if($isStunned && $key === 'head') disabled @endif>
                                    {{ __('fight.'.$key) }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-1/2">
                        <h3 class="font-semibold mb-2 bg-gray-100 p-1 text-xl">{{ __('fight.defending') }}</h3>

                        @php
                            $defenseOptions = [
                                'head_chest',
                                'chest_belly',
                                'belly_belt',
                                'belt_legs',
                                'legs_head',
                            ];
                        @endphp

                        <div>
                            @foreach($defenseOptions as $key)
                                <label class="relative block mr-3 mb-1 text-sm cursor-pointer">
                                    @if($isStunned)<span class="absolute top-[-1px]">🚫</span>@endif
                                    <input type="radio"
                                        wire:model="defenseChoice"
                                        name="defenseChoice"
                                        value="{{ $key }}"
                                        @if($isStunned && $key === 'head_chest') disabled @endif>
                                    {{ __('fight.defense.' . $key) }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex mt-2 justify-center bg-gray-100 p-1">
                    <button type="submit"
                            class="bg-blue-600 text-white px-10 py-1 rounded hover:bg-blue-700"
                            @if(!$isStunned) :disabled="!$wire.attackChoice || !$wire.defenseChoice" @endif>
                        @if($isStunned)
                            {{ __('fight.miss_step') }}!
                        @else
                            {{ __('fight.attack') }}!
                        @endif
                    </button>
                </div>

                {{-- Повідомлення, якщо не обрано --}}
                <div x-show="!$wire.attackChoice || !$wire.defenseChoice"
                    class="absolute w-full left-1/2 translate-x-[-50%] bottom-[-8px] text-gray-500 text-sm text-center italic"
                    >
                    @if (!empty($character->debuffs['stun']['delay'] ?? 0) == 0)
                        {{ __('fight.select_attack_and_defense') }}
                    @else
                        {{ __('fight.next_step') }}
                    @endif
                </div>

            </form>



            {{-- Чат бою --}}
            @livewire('info-chat')

        </div>

        {{-- Монстр --}}
        <div>
            <div class="flex flex-col">

                <div class="flex">
                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-helmet.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['helmet']))
                                @php
                                    $helmet = $monsterEquippedBySlot['helmet'];
                                    $rarityClass = 'gray';
                                    if ($helmet->pivot && isset($helmet->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$helmet->pivot->rarity] ?? 'gray';
                                    }

                                    $level = $helmet->pivot->level ?? $helmet->required_level;
                                    $title = $helmet->name . ' [' . $level . ']' . "\n";
                                    $bonuses = [];

                                    if ($helmet->pivot !== null && !empty($helmet->pivot->bonuses)) {
                                        $bonuses = json_decode($helmet->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp

                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]" title="{{ trim($title) }}">
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);" alt="">
                                    <img src="{{ asset($helmet->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35);
                                                filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                    style="background: url({{ asset('images/empty-equipment/helmet.png') }}) center center no-repeat; background-size: 70%;">
                                </span>
                            @endif
                        </div>


                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-armor.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['armor']))
                                @php
                                    $armor = (object) $monsterEquippedBySlot['armor'];
                                    $armor->pivot = isset($armor->pivot) ? (object) $armor->pivot : null;

                                    $level = $armor->pivot->level ?? $armor->required_level;
                                    $title = $armor->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($armor->pivot !== null && !empty($armor->pivot->bonuses)) {
                                        $bonuses = json_decode($armor->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($armor->pivot !== null && isset($armor->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$armor->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}">
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($armor->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35);
                                                filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/armor.png') }}) center center no-repeat; background-size: 70%;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                             @if(isset($monsterEquippedBySlot['ring1']))
                                @php
                                    $ring1 = (object) $monsterEquippedBySlot['ring1'];
                                    $ring1->pivot = isset($ring1->pivot) ? (object) $ring1->pivot : null;

                                    $level = $ring1->pivot->level ?? $ring1->required_level;
                                    $title = $ring1->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($ring1->pivot !== null && !empty($ring1->pivot->bonuses)) {
                                        $bonuses = json_decode($ring1->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($ring1->pivot !== null && isset($ring1->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$ring1->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($ring1->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: contain;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-weapon.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['weapon']))
                                @php
                                    $weapon = (object) $monsterEquippedBySlot['weapon'];
                                    $weapon->pivot = isset($weapon->pivot) ? (object) $weapon->pivot : null;

                                    $level = $weapon->pivot->level ?? $weapon->required_level;
                                    $title = $weapon->name . ' [' . $level . ']' . "\n"
                                        . 'Урон: ' . $weapon->min_damage . '–' . $weapon->max_damage . "\n";

                                    $bonuses = [];

                                    if ($weapon->pivot !== null && !empty($weapon->pivot->bonuses)) {
                                        $bonuses = json_decode($weapon->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($weapon->pivot !== null && isset($weapon->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$weapon->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($weapon->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                    style="background: url({{ asset('images/empty-equipment/empty-weapon.png') }}) center center no-repeat; background-size: 70%;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-legs.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['legs']))
                                @php
                                    $legs = (object) $monsterEquippedBySlot['legs'];
                                    $legs->pivot = isset($legs->pivot) ? (object) $legs->pivot : null;

                                    $level = $legs->pivot->level ?? $legs->required_level;
                                    $title = $legs->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($legs->pivot !== null && !empty($legs->pivot->bonuses)) {
                                        $decoded = json_decode($legs->pivot->bonuses, true);

                                        if (is_array($decoded)) {
                                            $bonuses = $decoded['stats'] ?? $decoded;
                                        }
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($legs->pivot !== null && isset($legs->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$legs->pivot->rarity] ?? 'gray';
                                    }
                                @endphp

                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($legs->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else

                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/legs.png') }}) center center no-repeat; background-size: 80%;">
                                </span>
                            @endif
                        </div>

                    </div>
                    <div class="relative w-[205px] h-full mx-[10px]">
                        @php
                            $monsterBarColor = match(true) {
                                $monsterPercent <= 33 => 'bg-red-500',
                                $monsterPercent <= 66 => 'bg-yellow-400',
                                default => 'bg-green-500',
                            };
                        @endphp
                        <div>
                            <div class="block w-full text-center mb-3"><strong>{{ $monster->name }}</strong> [{{ $monster->level }}]</div>
                            <div class="relative block w-full h-3 bg-gray-100 cursor-pointer"
                                title="Здоровʼя: {{ $monster->current_health }} / {{ $monster->base_health }}"
                            >
                                <div class="absolute top-0 left-0 w-full text-[8px] text-center text-black z-[1]"><strong>{{ $monster->current_health }} / {{ $monster->base_health }}</strong></div>
                                <div class="absolute top-0 left-0 {{ $monsterBarColor }} h-3" style="width: {{ $monsterPercent }}%"></div>
                            </div>
                        </div>
                        <div class="relative block w-full h-1 bg-gray-300"></div>
                        <div class="relative avatar w-[205px] h-[410px] overflow-hidden group transition-left duration-300"
                            style="background: url({{ asset('images/avatar-male-art.jpg') }}) center center no-repeat; background-size: cover;"
                            title="{{ $monster->name }} [{{ $monster->level }}]">

                            <div class="absolute bg-black/60 text-white w-full h-full overflow-auto p-2 top-0 -left-full ml-2 group-hover:left-[0] group-hover:ml-0 transition-w duration-300">
                                <ul class="monster-stats list-inside">
                                    <li>{{ __('messages.strength') }}: {{ $monster->strength }}</li>
                                    <li>{{ __('messages.agility') }}: {{ $monster->agility }}</li>
                                    <li>{{ __('messages.intuition') }}: {{ $monster->intuition }}</li>
                                    <li>{{ __('messages.endurence') }} {{ $monster->endurance }}</li>
                                    <li>{{ __('messages.baseDamage') }}: {{ $monster->total_damage_range['min'] ?? '0' }} - {{ $monster->total_damage_range['max'] ?? '0' }}</li>
                                    <li>{{ __('messages.critDamage') }}: {{ $monster->critical_damage_range['min'] ?? '0' }} - {{ $monster->critical_damage_range['max'] ?? '0' }}</li>
                                    <li>{{ __('messages.crit') }}: {{ $monster->crit_chance }}%</li>
                                    <li>{{ __('messages.antiCrit') }}: {{ $monster->anti_crit_chance }}%</li>
                                    <li>{{ __('messages.dodge') }}: {{ $monster->dodge_chance }}%</li>
                                    <li>{{ __('messages.antiDodge') }}: {{ $monster->anti_dodge_chance }}%</li>
                                    @php
                                        $defenseByZone = $monster->totalDefenseByZone();
                                    @endphp

                                    @foreach($zoneLabels as $zone => $label)
                                        @php
                                            $min = $defenseByZone[$zone]['min'] ?? 0;
                                            $max = $defenseByZone[$zone]['max'] ?? 0;
                                        @endphp
                                        <li>{{ __("messages.$zone") }}: @if($min != 0){{ $min }} - @endif{{ $max }}</li>
                                    @endforeach
                                </ul>
                                <div class="flex flex-col items-center justify-center gap-1 absolute w-[8px] h-full right-0 top-0 text-white bg-black z-10">
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                    <span class="bg-white w-[3px] h-[3px] rounded-[50%]"></span>
                                </div>
                            </div>

                        </div>
                        <div
                            x-data="{
                                showMonsterHit: false,
                                monsterHitMessage: '',
                                monsterHitType: 'hit'
                            }"
                            x-init="window.addEventListener('alpine-monster-hit', e => {
                                monsterHitMessage = e.detail.message;
                                monsterHitType = e.detail.type;
                                showMonsterHit = true;
                                setTimeout(() => showMonsterHit = false, 1000);
                            })"
                            x-show="showMonsterHit"
                            x-transition
                            class="absolute top-20 right-1/3 transform translate-x-1/3 text-4xl font-bold z-50"
                            :class="{
                                'text-red-600': monsterHitType === 'hit',
                                'text-green-600': monsterHitType === 'block',
                                'text-yellow-400': monsterHitType === 'crit',
                                'text-blue-500': monsterHitType === 'dodge'
                            }"
                        >
                            <span class="battle-info" x-text="monsterHitMessage"></span>
                        </div>

                        @if (!empty($monster->debuffs))
                        <div class="monster-debuffs flex gap-2 absolute top-[65px] right-[15px]">
                            @foreach ($monster->debuffs as $debuffKey => $debuff)
                                @php
                                    $activeTurns = 0;
                                    if ($debuffKey === 'stun' && isset($debuff['delay']) && $debuff['delay'] > 0) {
                                        $activeTurns = $debuff['delay'];
                                    } elseif ($debuffKey !== 'stun' && isset($debuff['duration']) && $debuff['duration'] > 0) {
                                        $activeTurns = $debuff['duration'];
                                    }
                                @endphp

                                @if ($activeTurns > 0)
                                    <div class="debuff-icon relative" title="{{ $debuff['description'] }}">
                                        <img src="{{ $debuff['icon'] ?? '/images/debuffs/default.png' }}" alt="{{ $debuff['name'] }}" class="w-10 h-10">
                                        <span class="absolute w-[15px] h-[15px] flex items-center justify-center bottom-[-5px] right-[-3px] bg-white text-black text-[11px] rounded rounded-[50%] px-1">
                                            {{ $activeTurns }}
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif


                    </div>

                    <div class="flex flex-col" style="gap: 10px">

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-neckless.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['neckless']))
                                @php
                                    $neckless = $monsterEquippedBySlot['neckless'];
                                    $neckless->pivot = isset($neckless->pivot) ? (object) $neckless->pivot : null;

                                    $level = $neckless->pivot->level ?? $neckless->required_level;
                                    $title = $neckless->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($neckless->pivot !== null && !empty($neckless->pivot->bonuses)) {
                                        $bonuses = json_decode($neckless->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($neckless->pivot !== null && isset($neckless->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$neckless->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($neckless->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/neckless.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-arms.png') }}) center center no-repeat; background-size: cover;"
                            >
                             @if(isset($monsterEquippedBySlot['arms']))
                                @php
                                    $arms = (object) $monsterEquippedBySlot['arms'];
                                    $arms->pivot = isset($arms->pivot) ? (object) $arms->pivot : null;

                                    $level = $arms->pivot->level ?? $arms->required_level;
                                    $title = $arms->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($arms->pivot !== null && !empty($arms->pivot->bonuses)) {
                                        $bonuses = json_decode($arms->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($arms->pivot !== null && isset($arms->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$arms->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($arms->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/arms.png') }}) center center no-repeat; background-size: contain;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                             @if(isset($monsterEquippedBySlot['ring2']))
                                @php
                                    $ring2 = (object) $monsterEquippedBySlot['ring2'];
                                    $ring2->pivot = isset($ring2->pivot) ? (object) $ring2->pivot : null;

                                    $level = $ring2->pivot->level ?? $ring2->required_level;
                                    $title = $ring2->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($ring2->pivot !== null && !empty($ring2->pivot->bonuses)) {
                                        $bonuses = json_decode($ring2->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($ring2->pivot !== null && isset($ring2->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$ring2->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($ring2->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: contain;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-shield.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['shield']))
                                @php
                                    $shield = (object) $monsterEquippedBySlot['shield'];
                                    $shield->pivot = isset($shield->pivot) ? (object) $shield->pivot : null;

                                    $level = $shield->pivot->level ?? $shield->required_level;
                                    $title = $shield->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($shield->pivot !== null && !empty($shield->pivot->bonuses)) {
                                        $bonuses = json_decode($shield->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($shield->pivot !== null && isset($shield->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$shield->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div  class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($shield->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/shield.png') }}) center center no-repeat; background-size: 80%;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-boots.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['boots']))
                                @php
                                    $boots = (object) $monsterEquippedBySlot['boots'];
                                    $boots->pivot = isset($boots->pivot) ? (object) $boots->pivot : null;

                                    $level = $boots->pivot->level ?? $boots->required_level;
                                    $title = $boots->name . ' [' . $level . ']' . "\n";

                                    $bonuses = [];

                                    if ($boots->pivot !== null && !empty($boots->pivot->bonuses)) {
                                        $bonuses = json_decode($boots->pivot->bonuses, true);
                                    }

                                    foreach ($bonuses as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }

                                    $rarityClass = 'gray';
                                    if ($boots->pivot !== null && isset($boots->pivot->rarity)) {
                                        $rarityClass = $rarityColors[$boots->pivot->rarity] ?? 'gray';
                                    }
                                @endphp
                                <div class="{{ $rarityClass }} relative w-[60px] h-[90px]"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-' . $rarityClass . '.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($boots->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                      style="background: url({{ asset('images/empty-equipment/boots.png') }}) center center no-repeat; background-size: contain;">
                                </span>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
