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

<div class="w-full p-4 rounded shadow">

    <div class="flex justify-between mb-6">

        {{-- Персонаж --}}
       <div>
            <div class="flex flex-col">
                <div class="block w-full text-center mb-3"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</div>
                <div class="flex">
                    <div>
                        <div id="helmet" class="w-[90px] h-[90px] border"></div>
                        <div id="sholders" class="w-[90px] h-[50px] border"></div>
                        <div id="weapon" class="w-[90px] h-[90px] border"></div>
                        <div id="torso" class="w-[90px] h-[120px] border"></div>
                        <div id="belt" class="w-[90px] h-[50px] border"></div>
                    </div>
                    <div class="relative w-[165px] h-full">
                        @livewire('health-regen')
                        <div class="relative block w-full h-1 bg-gray-300 cursor-pointer" title="Досвід: {{ $character->experience }} / {{ $character->getExperienceToLevelUp() }}">
                            <div class="absolute top-0 left-0 w-full text-center text-black z-[1]"></div>
                            <div class="absolute top-0 left-0 bg-red-400 h-1" style="width: {{ $characterExpPercent }}%"></div>
                        </div>
                        <div id="avatar" class="w-[165px] h-[334px]" style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;" title="{{ $character->user->name }} [{{ $character->level }}]"></div>
                        <div id="banner" class="w-[165px] h-[50px] border"></div>
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

                    <div>
                        <div class="w-[90px] h-[90px] border">
                            <div id="earrings" class="h-[30px] border"></div>
                            <div id="neckless" class="h-[30px] border"></div>
                            <div class="flex h-[30px] border">
                                <div id="ring1" class="w-[30px] border"></div>
                                <div id="ring2" class="w-[30px] border"></div>
                                <div id="ring3" class="w-[30px] border"></div>
                            </div>
                        </div>
                        <div id="arms" class="w-[90px] h-[50px] border"></div>
                        <div id="shield" class="w-[90px] h-[90px] border"></div>
                        <div id="legs" class="w-[90px] h-[120px] border"></div>
                        <div id="boots" class="w-[90px] h-[50px] border"></div>
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
                <div class="block w-full text-center mb-3"><strong>{{ $monster->name }}</strong> [{{ $monster->level }}]</div>
                <div class="flex">
                    <div>
                        <div id="helmet" class="w-[90px] h-[90px] border"></div>
                        <div id="sholders" class="w-[90px] h-[50px] border"></div>
                        <div id="weapon" class="w-[90px] h-[90px] border"></div>
                        <div id="torso" class="w-[90px] h-[120px] border"></div>
                        <div id="belt" class="w-[90px] h-[50px] border"></div>
                    </div>
                    <div class="relative w-[165px] h-full">
                        @php
                            $monsterBarColor = match(true) {
                                $monsterPercent <= 33 => 'bg-red-500',
                                $monsterPercent <= 66 => 'bg-yellow-400',
                                default => 'bg-green-500',
                            };
                        @endphp
                        <div>
                            <div class="relative block w-full h-3 bg-gray-100 cursor-pointer"
                                title="Здоровʼя: {{ $monster->current_health }} / {{ $monster->base_health }}"
                            >
                                <div class="absolute top-0 left-0 w-full text-[8px] text-center text-black z-[1]"><strong>{{ $monster->current_health }} / {{ $monster->base_health }}</strong></div>
                                <div class="absolute top-0 left-0 {{ $monsterBarColor }} h-3" style="width: {{ $monsterPercent }}%"></div>
                            </div>
                        </div>
                        <div class="relative block w-full h-1 bg-gray-300"></div>
                        <div id="avatar" class="w-[165px] h-[334px]"
                            style="background: url({{ asset('images/avatar.jpg') }}) center center no-repeat; background-size: cover;"
                            title="{{ $monster->name }} [{{ $monster->level }}]">
                        </div>
                        <div id="banner" class="w-[200px] h-[50px] border"></div>
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

                    <div>
                        <div class="w-[90px] h-[90px] border">
                            <div id="earrings" class="h-[30px] border"></div>
                            <div id="neckless" class="h-[30px] border"></div>
                            <div class="flex h-[30px] border">
                                <div id="ring1" class="w-[30px] border"></div>
                                <div id="ring2" class="w-[30px] border"></div>
                                <div id="ring3" class="w-[30px] border"></div>
                            </div>
                        </div>
                        <div id="arms" class="w-[90px] h-[50px] border"></div>
                        <div id="shield" class="w-[90px] h-[90px] border"></div>
                        <div id="legs" class="w-[90px] h-[120px] border"></div>
                        <div id="boots" class="w-[90px] h-[50px] border"></div>
                    </div>
                </div>
                <div>
                    {{ $monster->strength }}<br>
                    {{ $monster->agility }}<br>
                    {{ $monster->intuition }}<br>
                    {{ $monster->endurance }}
                    <ul class="list-disc list-inside">
                        <li><strong>Базовий урон:</strong> {{ $monster->base_damage }}</li>
                        <li><strong>Критичний урон:</strong> {{ round($monster->base_damage * $monster->critical_damage_multiplier) }}</li>
                        <li>Крит: {{ $monster->crit_chance }}%</li>
                        <li>Анти-крит: {{ $monster->anti_crit_chance }}%</li>
                        <li>Ухил: {{ $monster->dodge_chance }}%</li>
                        <li>Анті-ухил: {{ $monster->anti_dodge_chance }}%</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</div>
