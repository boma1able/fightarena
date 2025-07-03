@php
    $characterPercent = round(($character->current_health / $character->max_health) * 100);
    $monsterPercent = round(($monster->current_health / $monster->base_health) * 100);
    $characterBarColor = $characterPercent < 33 ? 'bg-red-500' : ($characterPercent < 66 ? 'bg-yellow-400' : 'bg-green-500');
    $monsterBarColor = $monsterPercent < 33 ? 'bg-red-500' : ($monsterPercent < 66 ? 'bg-yellow-400' : 'bg-green-500');

    $characterExpPercent = 0;
    if ($character && $character->getExperienceToLevelUp() > 0) {
        $characterExpPercent = round(($character->experience / $character->getExperienceToLevelUp()) * 100);
    }
@endphp

<div class="w-full p-6 rounded shadow bg-white">
    <div class="flex justify-between mb-6">

        {{-- Персонаж --}}
       <div>
            <div class="flex flex-col w-full max-w-[362px]">

                <div class="flex">
                    <div class="flex flex-col" style="gap: 10px">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['helmet']))
                                @php
                                    $helmet = (object) $equippedBySlot['helmet'];
                                    $helmet->pivot = (object) $helmet->pivot;

                                    $title = $helmet->name . ' [' . $helmet->required_level . ']' . "\n"
                                        . 'Міцність: ' . $helmet->pivot->current_durability . ' / ' . $helmet->pivot->max_durability . "\n";

                                    foreach ($helmet->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['armor']))
                                @php
                                    $armor = (object) $equippedBySlot['armor'];
                                    $armor->pivot = (object) $armor->pivot;

                                    $title = $armor->name . ' [' . $armor->required_level . ']' . "\n"
                                        . 'Міцність: ' . $armor->pivot->current_durability . ' / ' . $armor->pivot->max_durability . "\n";

                                    foreach ($armor->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                            @if(isset($equippedBySlot['arms']))
                                @php
                                    $arms = (object) $equippedBySlot['arms'];
                                    $arms->pivot = (object) $arms->pivot;

                                    $title = $arms->name . ' [' . $arms->required_level . ']' . "\n"
                                        . 'Міцність: ' . $arms->pivot->current_durability . ' / ' . $arms->pivot->max_durability . "\n";

                                    foreach ($arms->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                            @if(isset($equippedBySlot['legs']))
                                @php
                                    $legs = (object) $equippedBySlot['legs'];
                                    $legs->pivot = (object) $legs->pivot;

                                    $title = $legs->name . ' [' . $legs->required_level . ']' . "\n"
                                        . 'Міцність: ' . $legs->pivot->current_durability . ' / ' . $legs->pivot->max_durability . "\n";

                                    foreach ($legs->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['boots']))
                                @php
                                    $boots = (object) $equippedBySlot['boots'];
                                    $boots->pivot = (object) $boots->pivot;

                                    $title = $boots->name . ' [' . $boots->required_level . ']' . "\n"
                                        . 'Міцність: ' . $boots->pivot->current_durability . ' / ' . $boots->pivot->max_durability . "\n";

                                    foreach ($boots->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                    <div class="relative w-[205px] h-full mx-[10px]">
                        <div class="block w-full text-center mb-3">
                            <strong>{{ $character->user->name }}</strong> [{{ $character->level }}]
                        </div>
                        @livewire('health-regen')
                        <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                            <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                            <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                        </div>
                        <div class="avatar w-[205px] h-[410px]" style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]"></div>
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
                            <span x-text="playerHitMessage"></span>
                        </div>

                    </div>

                    <div class="flex flex-col" style="gap: 10px">

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['neckless']))
                                @php
                                    $neckless = (object) $equippedBySlot['neckless'];
                                    $neckless->pivot = (object) $neckless->pivot;

                                    $title = $neckless->name . ' [' . $neckless->required_level . ']' . "\n"
                                        . 'Міцність: ' . $neckless->pivot->current_durability . ' / ' . $neckless->pivot->max_durability . "\n";

                                    foreach ($neckless->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                        <div class="flex flex-col w-[68px] gap-[10px]">
                            @foreach(['ring1', 'ring2'] as $ringSlot)
                                <div id="{{ $ringSlot }}"
                                    class="flex relative items-center justify-center w-[68px] h-[98px]"
                                    style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                                    >
                                    @if(isset($equippedBySlot[$ringSlot]))
                                        @php
                                            $ring = (object) $equippedBySlot[$ringSlot];
                                            $ring->pivot = (object) $ring->pivot;

                                            $title = $ring->name . ' [' . $ring->required_level . ']' . "\n"
                                                . 'Міцність: ' . $ring->pivot->current_durability . ' / ' . $ring->pivot->max_durability . "\n";

                                            foreach ($ring->bonuses ?? [] as $stat => $value) {
                                                $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                            }
                                        @endphp
                                        <div class="relative w-[60px] h-[90px]"
                                            style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                            title="{{ trim($title) }}"
                                            >
                                            <img src="{{ asset('images/items/frame-gray.png') }}"
                                                class="absolute w-[60px]"
                                                style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                alt="">
                                            <img src="{{ asset($ring->image) }}"
                                                class="absolute w-[60px]"
                                                style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                                alt="">
                                        </div>
                                    @else
                                        <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                                    @endif
                                </div>
                            @endforeach

                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['weapon']))
                                @php
                                    $weapon = (object) $equippedBySlot['weapon'];
                                    $weapon->pivot = (object) $weapon->pivot;

                                    $title = $weapon->name . ' [' . $weapon->required_level . ']' . "\n"
                                        . 'Урон: ' . $weapon->min_damage . '–' . $weapon->max_damage . "\n"
                                        . 'Міцність: ' . $weapon->pivot->current_durability . ' / ' . $weapon->pivot->max_durability . "\n";

                                    foreach ($weapon->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($equippedBySlot['shield']))
                                @php
                                    $shield = (object) $equippedBySlot['shield'];
                                    $shield->pivot = (object) $shield->pivot;

                                    $title = $shield->name . ' [' . $shield->required_level . ']' . "\n"
                                        . 'Міцність: ' . $shield->pivot->current_durability . ' / ' . $shield->pivot->max_durability . "\n";

                                    foreach ($shield->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div  class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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

                    </div>
                </div>
            </div>
       </div>

        <div class="w-[600px] bg-white p-4 rounded shadow">

            <h2 class="text-center mb-5 text-bold text-[20px]">Бій розпочався!</h2>

            @if(session('message'))
                <div
                    x-data="{ show: true }"
                    x-init="setTimeout(() => show = false, 2000)"
                    x-show="show"
                    x-transition
                    class="fixed top-5 right-5 bg-red-100 border-l-4 p-4 border-red-500 text-red-700 mb-4 text-[14px]"
                >
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="fightStep">

                <div class="flex">
                    <div class="w-1/2">
                        <h3 class="font-semibold mb-2 bg-gray-100 p-1">Атакувати</h3>
                        <div>
                            @foreach(['head' => 'Голову', 'chest' => 'Груди', 'belly' => 'Живіт', 'belt' => 'Пояс', 'legs' => 'Ноги'] as $key => $label)
                                <label class="block mr-3 mb-1">
                                    <input type="radio" wire:model="attackChoice" name="attackChoice" value="{{ $key }}">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-1/2">
                        <h3 class="font-semibold mb-2 bg-gray-100 p-1">Захищатись</h3>
                        @php
                            $defenseOptions = [
                                'head_chest' => 'Захищати голову та груди',
                                'chest_belly' => 'Захищати груди та живіт',
                                'belly_belt' => 'Захищати живіт та пояс',
                                'belt_legs' => 'Захищати пояс та ноги',
                                'legs_head' => 'Захищати ноги та голову',
                            ];
                        @endphp
                        <div>
                            @foreach($defenseOptions as $key => $label)
                                <label class="block mr-3 mb-1">
                                    <input type="radio" wire:model="defenseChoice" name="defenseChoice" value="{{ $key }}">
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex mt-2 justify-center bg-gray-100 p-1">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-1 rounded hover:bg-blue-700">
                        Бій!
                    </button>
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
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['helmet']))
                                @php
                                    $helmet = (object) $monsterEquippedBySlot['helmet'];
                                    $helmet->pivot = isset($helmet->pivot) ? (object) $helmet->pivot : null;

                                    $title = $helmet->name . ' [' . $helmet->required_level . ']' . "\n";

                                    foreach ($helmet->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($helmet->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                                </div>
                            @else
                                <span class="block w-full h-full opacity-20"
                                    style="background: url({{ asset('images/empty-equipment/helmet.png') }}) center center no-repeat; background-size: 70%;">
                                </span>
                            @endif
                        </div>


                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['armor']))
                                @php
                                    $armor = (object) $monsterEquippedBySlot['armor'];
                                    $armor->pivot = (object) $armor->pivot;

                                    $title = $armor->name . ' [' . $armor->required_level . ']' . "\n";

                                    foreach ($armor->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($armor->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
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
                             @if(isset($monsterEquippedBySlot['arms']))
                                @php
                                    $arms = (object) $monsterEquippedBySlot['arms'];
                                    $arms->pivot = (object) $arms->pivot;

                                    $title = $arms->name . ' [' . $arms->required_level . ']' . "\n";

                                    foreach ($arms->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                            @if(isset($monsterEquippedBySlot['legs']))
                                @php
                                    $legs = (object) $monsterEquippedBySlot['legs'];
                                    $legs->pivot = (object) $legs->pivot;

                                    $title = $legs->name . ' [' . $legs->required_level . ']' . "\n";

                                    foreach ($legs->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['boots']))
                                @php
                                    $boots = (object) $monsterEquippedBySlot['boots'];
                                    $boots->pivot = (object) $boots->pivot;

                                    $title = $boots->name . ' [' . $boots->required_level . ']' . "\n";

                                    foreach ($boots->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                        <div id="avatar" class="w-[205px] h-[410px]"
                            style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;"
                            title="{{ $monster->name }} [{{ $monster->level }}]">
                        </div>

                        <ul class="list-disc list-inside">
                            <li><strong>Базовий урон:</strong> {{ $monster->total_damage_range['min'] ?? '0' }} - {{ $monster->total_damage_range['max'] ?? '0' }}</li>
                            <li><strong>Критичний урон:</strong> {{ $monster->critical_damage_range['min'] ?? '0' }} - {{ $monster->critical_damage_range['max'] ?? '0' }}</li>
                            <li>Крит: {{ $monster->crit_chance }}%</li>
                            <li>Анти-крит: {{ $monster->anti_crit_chance }}%</li>
                            <li>Ухил: {{ $monster->dodge_chance }}%</li>
                            <li>Анті-ухил: {{ $monster->anti_dodge_chance }}%</li>
                        </ul>
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
                            <span x-text="monsterHitMessage"></span>
                        </div>

                    </div>

                    <div class="flex flex-col" style="gap: 10px">

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['neckless']))
                                @php
                                    $neckless = $monsterEquippedBySlot['neckless'];
                                    $neckless->pivot = (object) $neckless->pivot;

                                    $title = $neckless->name . ' [' . $neckless->required_level . ']' . "\n";

                                    foreach ($neckless->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                        <div class="flex flex-col w-[68px] gap-[10px]">
                            @foreach(['ring1', 'ring2'] as $ringSlot)
                                <div id="{{ $ringSlot }}"
                                    class="flex relative items-center justify-center w-[68px] h-[98px]"
                                    style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                                    >
                                    @if(isset($monsterEquippedBySlot[$ringSlot]))
                                        @php
                                            $ring = (object) $monsterEquippedBySlot[$ringSlot];
                                            $ring->pivot = (object) $ring->pivot;

                                            $title = $ring->name . ' [' . $ring->required_level . ']' . "\n";

                                            foreach ($ring->bonuses ?? [] as $stat => $value) {
                                                $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                            }
                                        @endphp
                                        <div class="relative w-[60px] h-[90px]"
                                            style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                            title="{{ trim($title) }}"
                                            >
                                            <img src="{{ asset('images/items/frame-gray.png') }}"
                                                class="absolute w-[60px]"
                                                style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                                alt="">
                                            <img src="{{ asset($ring->image) }}"
                                                class="absolute w-[60px]"
                                                style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                                alt="">
                                        </div>
                                    @else
                                        <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                                    @endif
                                </div>
                            @endforeach

                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['weapon']))
                                @php
                                    $weapon = (object) $monsterEquippedBySlot['weapon'];
                                    $weapon->pivot = (object) $weapon->pivot;

                                    $title = $weapon->name . ' [' . $weapon->required_level . ']' . "\n"
                                        . 'Урон: ' . $weapon->min_damage . '–' . $weapon->max_damage . "\n";

                                    foreach ($weapon->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                                    style="background: url({{ asset('images/empty-equipment/weapon.png') }}) center center no-repeat; background-size: 70%;">
                                </span>
                            @endif
                        </div>

                        <div class="flex relative items-center justify-center w-[68px] h-[98px]"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                            @if(isset($monsterEquippedBySlot['shield']))
                                @php
                                    $shield = (object) $monsterEquippedBySlot['shield'];
                                    $shield->pivot = (object) $shield->pivot;

                                    $title = $shield->name . ' [' . $shield->required_level . ']' . "\n";

                                    foreach ($shield->bonuses ?? [] as $stat => $value) {
                                        $title .= ucfirst($stat) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <div  class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="{{ trim($title) }}"
                                    >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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

                    </div>
                </div>
                <div>
                    {{-- {{ $monster->strength }}<br>
                    {{ $monster->agility }}<br>
                    {{ $monster->intuition }}<br>
                    {{ $monster->endurance }} --}}

                </div>
            </div>
        </div>

    </div>

</div>
