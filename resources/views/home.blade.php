@extends('layouts.app')

@section('content')
    <div class="flex flex-wrap justify-center bg-white p-6 rounded shadow-md">

        @if($character)
            <div class="flex mb-4 w-full gap-5">

                <div class="flex flex-col w-full max-w-[345px]">
                    <div class="block w-full text-center mb-3"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</div>
                    <div class="flex">

                        <div>
                            <div id="helmet" class="relative w-[90px] h-[90px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['helmet']))
                                    @php
                                        $helmet = $equippedBySlot['helmet'];
                                        $title = $helmet->name . ' [' . $helmet->required_level . ']' . "\n"
                                            . 'Міцність: ' . $helmet->pivot->current_durability . ' / ' . $helmet->pivot->max_durability . "\n";

                                        foreach ($helmet->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[90px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['helmet']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['helmet']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/helmet.png') }}) center center no-repeat; background-size: cover;background-size: 75%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="shoulders" class="relative mt-[-1px] w-[90px] h-[50px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['shoulders']))
                                    @php
                                        $shoulders = $equippedBySlot['shoulders'];
                                        $title = $shoulders->name . ' [' . $shoulders->required_level . ']' . "\n"
                                            . 'Міцність: ' . $shoulders->pivot->current_durability . ' / ' . $shoulders->pivot->max_durability . "\n";

                                        foreach ($shoulders->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[50px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['shoulders']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['shoulders']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/shoulders.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="weapon" class="relative w-[90px] h-[90px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['weapon']))
                                    @php
                                        $weapon = $equippedBySlot['weapon'];
                                        $title = $weapon->name . ' [' . $weapon->required_level . ']' . "\n"
                                            . 'Урон: ' . $weapon->min_damage . '–' . $weapon->max_damage . "\n"
                                            . 'Міцність: ' . $weapon->pivot->current_durability . ' / ' . $weapon->pivot->max_durability . "\n";

                                        foreach ($weapon->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[90px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['weapon']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['weapon']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/weapon.png') }}) center center no-repeat; background-size: 70%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="armor" class="relative w-[90px] h-[120px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['armor']))
                                    @php
                                        $armor = $equippedBySlot['armor'];
                                        $title = $armor->name . ' [' . $armor->required_level . ']' . "\n"
                                            . 'Міцність: ' . $armor->pivot->current_durability . ' / ' . $armor->pivot->max_durability . "\n";

                                        foreach ($armor->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[120px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['armor']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['armor']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/armor.png') }}) center center no-repeat; background-size: cover; background-size: 85%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="belt" class="relative w-[90px] h-[50px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['belt']))
                                    @php
                                        $belt = $equippedBySlot['belt'];
                                        $title = $belt->name . ' [' . $belt->required_level . ']' . "\n"
                                            . 'Міцність: ' . $belt->pivot->current_durability . ' / ' . $belt->pivot->max_durability . "\n";

                                        foreach ($belt->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[50px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['belt']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['belt']->name }}">
                                    </div>
                                @else
                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/belt.png') }}) center center no-repeat; background-size: cover; transform: rotate(45deg); background-size: 60%; opacity: .3"></span>
                                @endif
                            </div>
                        </div>
                        <div class="w-[165px] h-full">
                            @livewire('health-regen')
                            <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                                <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                                <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                            </div>
                            <div id="avatar" class="w-[165px] h-[334px]" style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]"></div>
                            <div id="banner" class="w-[165px] h-[50px]"></div>
                        </div>

                        <div>
                            <div class="w-[90px] h-[90px]">
                                <div id="earrings" class="relative h-[30px]" style="box-shadow: inset 0px 1px 5px 1px #5a5a5a36;">
                                    @if(isset($equippedBySlot['earrings']))
                                        @php
                                            $earrings = $equippedBySlot['earrings'];
                                            $title = $earrings->name . ' [' . $earrings->level . ']' . "\n"
                                                . 'Міцність: ' . $earrings->pivot->current_durability . ' / ' . $earrings->pivot->max_durability . "\n";

                                            foreach ($earrings->bonuses ?? [] as $stat => $value) {
                                                $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                            }
                                        @endphp
                                        <div class="w-[90px] h-[30px]" title="{{ trim($title) }}">
                                            <img src="{{ asset($equippedBySlot['earrings']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['earrings']->name }}">
                                        </div>
                                    @else
                                        <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/earrings.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                                    @endif
                                </div>
                                <div id="neckless" class="relative h-[30px]" style="box-shadow: inset 0px 1px 2px 1px #5a5a5a36;">
                                    @if(isset($equippedBySlot['neckless']))
                                    @php
                                        $neckless = $equippedBySlot['neckless'];
                                        $title = $neckless->name . ' [' . $neckless->required_level . ']' . "\n"
                                            . 'Міцність: ' . $neckless->pivot->current_durability . ' / ' . $neckless->pivot->max_durability . "\n";

                                        foreach ($neckless->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                        <div class="w-[90px] h-[30px]" title="{{ trim($title) }}">
                                            <img src="{{ asset($equippedBySlot['neckless']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['neckless']->name }}">
                                        </div>
                                    @else
                                        <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/neckless.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                                    @endif
                                </div>
                                <div class="flex h-[30px]">
                                    @foreach(['ring1', 'ring2', 'ring3'] as $ringSlot)
                                        <div id="{{ $ringSlot }}" class="relative w-[30px]" style="box-shadow: inset 0px 0px 1px 1px #5a5a5a36;">
                                            @if(isset($equippedBySlot[$ringSlot]))
                                                @php
                                                    $ring = $equippedBySlot[$ringSlot];
                                                    $title = $ring->name . ' [' . $ring->required_level . ']' . "\n"
                                                        . 'Міцність: ' . $ring->pivot->current_durability . ' / ' . $ring->pivot->max_durability . "\n";

                                                    foreach ($ring->bonuses ?? [] as $stat => $value) {
                                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                                    }
                                                @endphp
                                                <div class="w-[30px] h-[30px]" title="{{ trim($title) }}">
                                                    <img src="{{ asset($ring->image) }}" class="w-full h-full object-cover p-1" alt="{{ $ring->name }}">
                                                </div>
                                            @else
                                                <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/ring.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                            <div id="arms" class="relative w-[90px] h-[50px] p-[5px]" style="box-shadow: inset 0px 2px 4px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['arms']))
                                    @php
                                        $arms = $equippedBySlot['arms'];
                                        $title = $arms->name . ' [' . $arms->required_level . ']' . "\n"
                                            . 'Міцність: ' . $arms->pivot->current_durability . ' / ' . $arms->pivot->max_durability . "\n";

                                        foreach ($arms->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[50px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['arms']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['arms']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/arms.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="shield" class="relative w-[90px] h-[90px]" style="box-shadow: inset 0px 2px 5px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['shield']))
                                    @php
                                        $shield = $equippedBySlot['shield'];
                                        $title = $shield->name . ' [' . $shield->required_level . ']' . "\n"
                                            . 'Міцність: ' . $shield->pivot->current_durability . ' / ' . $shield->pivot->max_durability . "\n";

                                        foreach ($shield->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[90px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['shield']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['shield']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/shield.png') }}) center center no-repeat; background-size: cover; background-size: 80%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="legs" class="relative w-[90px] h-[120px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['legs']))
                                    @php
                                        $legs = $equippedBySlot['legs'];
                                        $title = $legs->name . ' [' . $legs->required_level . ']' . "\n"
                                            . 'Міцність: ' . $legs->pivot->current_durability . ' / ' . $legs->pivot->max_durability . "\n";

                                        foreach ($legs->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[120px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['legs']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['legs']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/legs.png') }}) center center no-repeat; background-size: 80%; opacity: .3"></span>
                                @endif
                            </div>
                            <div id="boots" class="relative w-[90px] h-[50px]" style="box-shadow: inset 0px 2px 7px 1px #5a5a5a36;">
                                @if(isset($equippedBySlot['boots']))
                                    @php
                                        $boots = $equippedBySlot['boots'];
                                        $title = $boots->name . ' [' . $boots->required_level . ']' . "\n"
                                            . 'Міцність: ' . $boots->pivot->current_durability . ' / ' . $boots->pivot->max_durability . "\n";

                                        foreach ($boots->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <div class="w-[90px] h-[50px]" title="{{ trim($title) }}">
                                        <img src="{{ asset($equippedBySlot['boots']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['boots']->name }}">
                                    </div>
                                @else
                                    <span class="block w-full h-full" style="background: url({{ asset('images/empty-equipment/boots.png') }}) center center no-repeat; background-size: contain; opacity: .3"></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-[320px]">
                    <h2 class="mb-2 mt-1 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Характеристики</h2>

                    @php
                        $stats = ['strength' => 'Сила', 'agility' => 'Спритність', 'intuition' => 'Інтуїція', 'endurance' => 'Витривалість'];
                    @endphp

                    @livewire('stat-points', ['character' => $character])

                    <h2 class="mb-2 mt-3 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Інформація</h2>
                    <ul class="mb-2 px-2">
                        <li>Рівень:</> {{ $character->level }}</li>
                        <li>Золото:</> {{ $character->gold }}</li>
                        <li>Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}</li>
                    </ul>

                    @livewire('character-modificators', ['character' => $character])

                    @livewire('character-armor', ['character' => $character])

                    <h2 class="mb-2 mt-3 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Статистика боїв</h2>
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

                <div class="w-[700] ml-[auto]">
                    {{-- <h2 class="text-center mb-4">Центральна площа</h2>
                    <div class="h-[400] flex justify-end">
                        <img src="{{ asset('images/central-squere.jpg') }}" class="w-[auto] max-h-full" alt="image">
                    </div> --}}
                </div>
            </div>

            <div class="w-full flex justify-center py-5">
                <a href="{{ route('battle') }}" class="inline-block bg-blue-500 text-white px-4 py-2 hover:bg-blue-600">
                    Перейти до бою
                </a>
            </div>

            <div class="w-[600px] flex justify-center">
                @livewire('info-chat')
            </div>


        @else
            <p>У вас ще немає персонажа.</p>
        @endif
    </div>
@endsection