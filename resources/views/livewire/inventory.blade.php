<div class="bg-white p-6 rounded shadow-md">

    @php
        $slots = [
            'helmet', 'sholders', 'weapon', 'armor', 'belt',
            'earrings', 'neckless', 'ring1', 'ring2', 'ring3',
            'arms', 'shield', 'legs', 'boots'
        ];
    @endphp

    @if($character)
        <div class="flex mb-4 w-full gap-5">

            <div class="flex flex-col w-full max-w-[345px]">
                <div class="block w-full text-center mb-3"><strong>{{ $character->user->name }}</strong> [{{ $character->level }}]</div>
                <div class="flex">

                    <div>
                        <div id="helmet" class="relative w-[90px] h-[90px] border">
                            @if(isset($equippedBySlot['helmet']))
                                @php
                                    $helmet = $equippedBySlot['helmet'];
                                    $title = $helmet->name . ' [' . $helmet->required_level . ']' . "\n"
                                        . 'Міцність: ' . $helmet->pivot->current_durability . ' / ' . $helmet->pivot->max_durability . "\n";

                                    foreach ($helmet->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['helmet']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[90px]">
                                    <img src="{{ asset($equippedBySlot['helmet']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['helmet']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="shoulders" class="relative mt-[-1px] w-[90px] h-[50px] border">
                            @if(isset($equippedBySlot['shoulders']))
                                @php
                                    $shoulders = $equippedBySlot['shoulders'];
                                    $title = $shoulders->name . ' [' . $shoulders->required_level . ']' . "\n"
                                        . 'Міцність: ' . $shoulders->pivot->current_durability . ' / ' . $shoulders->pivot->max_durability . "\n";

                                    foreach ($shoulders->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['shoulders']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[50px]">
                                    <img src="{{ asset($equippedBySlot['shoulders']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['shoulders']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="weapon" class="relative w-[90px] h-[90px] border">
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
                                <button wire:click="unequipItem({{ $equippedBySlot['weapon']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[90px]">
                                    <img src="{{ asset($equippedBySlot['weapon']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['weapon']->name }}">
                                </div>
                            @else
                                <span class="w-[90px] h-[90px] block"  style="background: url() center center no-repeat; background-size: cover;"></span>
                            @endif
                        </div>
                        <div id="armor" class="relative w-[90px] h-[120px] border">
                            @if(isset($equippedBySlot['armor']))
                                @php
                                    $armor = $equippedBySlot['armor'];
                                    $title = $armor->name . ' [' . $armor->required_level . ']' . "\n"
                                        . 'Міцність: ' . $armor->pivot->current_durability . ' / ' . $armor->pivot->max_durability . "\n";

                                    foreach ($armor->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['armor']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[120px]">
                                    <img src="{{ asset($equippedBySlot['armor']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['armor']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="belt" class="relative w-[90px] h-[50px] border">
                            @if(isset($equippedBySlot['belt']))
                                @php
                                    $belt = $equippedBySlot['belt'];
                                    $title = $belt->name . ' [' . $belt->required_level . ']' . "\n"
                                        . 'Міцність: ' . $belt->pivot->current_durability . ' / ' . $belt->pivot->max_durability . "\n";

                                    foreach ($belt->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['belt']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[50px]">
                                    <img src="{{ asset($equippedBySlot['belt']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['belt']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
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
                        <div id="banner" class="w-[165px] h-[50px] border"></div>
                    </div>

                    <div>
                        <div class="w-[90px] h-[90px] border">
                            <div id="earrings" class="relative h-[30px] border">
                                @if(isset($equippedBySlot['earrings']))
                                    @php
                                        $earrings = $equippedBySlot['earrings'];
                                        $title = $earrings->name . ' [' . $earrings->level . ']' . "\n"
                                            . 'Міцність: ' . $earrings->pivot->current_durability . ' / ' . $earrings->pivot->max_durability . "\n";

                                        foreach ($earrings->bonuses ?? [] as $stat => $value) {
                                            $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                        }
                                    @endphp
                                    <button wire:click="unequipItem({{ $equippedBySlot['earrings']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                    <div class="w-[90px] h-[30px]">
                                        <img src="{{ asset($equippedBySlot['earrings']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['earrings']->name }}">
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Порожньо</span>
                                @endif
                            </div>
                            <div id="neckless" class="relative h-[30px] border">
                                @if(isset($equippedBySlot['neckless']))
                                @php
                                    $neckless = $equippedBySlot['neckless'];
                                    $title = $neckless->name . ' [' . $neckless->required_level . ']' . "\n"
                                        . 'Міцність: ' . $neckless->pivot->current_durability . ' / ' . $neckless->pivot->max_durability . "\n";

                                    foreach ($neckless->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                    <button wire:click="unequipItem({{ $equippedBySlot['neckless']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                    <div class="w-[90px] h-[30px]">
                                        <img src="{{ asset($equippedBySlot['neckless']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['neckless']->name }}">
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Порожньо</span>
                                @endif
                            </div>
                            <div class="flex h-[30px] border">
                                @foreach(['ring1', 'ring2', 'ring3'] as $ringSlot)
                                    <div id="{{ $ringSlot }}" class="relative w-[30px] border">
                                        @if(isset($equippedBySlot[$ringSlot]))
                                            @php
                                                $ring = $equippedBySlot[$ringSlot];
                                                $title = $ring->name . ' [' . $ring->required_level . ']' . "\n"
                                                    . 'Міцність: ' . $ring->pivot->current_durability . ' / ' . $ring->pivot->max_durability . "\n";

                                                foreach ($ring->bonuses ?? [] as $stat => $value) {
                                                    $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                                }
                                            @endphp
                                            <button wire:click="unequipItem({{ $ring->pivot->id }})" class="absolute inset-0" title="{{ trim($title) }}"></button>
                                            <div class="w-[30px] h-[30px]">
                                                <img src="{{ asset($ring->image) }}" class="w-full h-full object-cover p-1" alt="{{ $ring->name }}">
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">П</span>
                                        @endif
                                    </div>
                                @endforeach

                            </div>

                        </div>
                        <div id="arms" class="relative w-[90px] h-[50px] border">
                            @if(isset($equippedBySlot['arms']))
                                @php
                                    $arms = $equippedBySlot['arms'];
                                    $title = $arms->name . ' [' . $arms->required_level . ']' . "\n"
                                        . 'Міцність: ' . $arms->pivot->current_durability . ' / ' . $arms->pivot->max_durability . "\n";

                                    foreach ($arms->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['arms']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[50px]">
                                    <img src="{{ asset($equippedBySlot['arms']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['arms']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="shield" class="relative w-[90px] h-[90px] border">
                            @if(isset($equippedBySlot['shield']))
                                @php
                                    $shield = $equippedBySlot['shield'];
                                    $title = $shield->name . ' [' . $shield->required_level . ']' . "\n"
                                        . 'Міцність: ' . $shield->pivot->current_durability . ' / ' . $shield->pivot->max_durability . "\n";

                                    foreach ($shield->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['shield']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[90px]">
                                    <img src="{{ asset($equippedBySlot['shield']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['shield']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="legs" class="relative w-[90px] h-[120px] border">
                            @if(isset($equippedBySlot['legs']))
                                @php
                                    $legs = $equippedBySlot['legs'];
                                    $title = $legs->name . ' [' . $legs->required_level . ']' . "\n"
                                        . 'Міцність: ' . $legs->pivot->current_durability . ' / ' . $legs->pivot->max_durability . "\n";

                                    foreach ($legs->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['legs']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[120px]">
                                    <img src="{{ asset($equippedBySlot['legs']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['legs']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                        <div id="boots" class="relative w-[90px] h-[50px] border">
                            @if(isset($equippedBySlot['boots']))
                                @php
                                    $boots = $equippedBySlot['boots'];
                                    $title = $boots->name . ' [' . $boots->required_level . ']' . "\n"
                                        . 'Міцність: ' . $boots->pivot->current_durability . ' / ' . $boots->pivot->max_durability . "\n";

                                    foreach ($boots->bonuses ?? [] as $stat => $value) {
                                        $title .= ($labels_ua[$stat] ?? ucfirst($stat)) . ': +' . $value . "\n";
                                    }
                                @endphp
                                <button wire:click="unequipItem({{ $equippedBySlot['boots']->pivot->id }})" class="absolute w-full h-full left-0 top-0" title="{{ trim($title) }}"></button>
                                <div class="w-[90px] h-[50px]">
                                    <img src="{{ asset($equippedBySlot['boots']->image) }}" class="w-full h-full object-cover p-1" alt="{{ $equippedBySlot['boots']->name }}">
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Порожньо</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="min-w-[320px]">
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
            </div>

            <div class="w-[700px] ml-[auto]">
                <h2 class="text-xl font-bold mb-2">Інвентар</h2>

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
                            setTimeout(() => show = false, 2000);
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
                <div class="mb-4">
                    <div class="flex flex-wrap gap-2">
                        <button
                            wire:click="setFilter('all')"
                            class="px-3 py-1 rounded border text-sm {{ $filterType === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-100' }}"
                        >
                            Усі
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
                    @php
                        $labels_ua = [
                            'strength' => 'Сила',
                            'agility' => 'Ловкість',
                            'intelligence' => 'Інтелект',
                            'endurance' => 'Витривалість',
                            'head' => 'Голови',
                            'chest' => 'Грудей',
                            'belly' => 'Живота',
                            'belt' => 'Пояса',
                            'legs' => 'Ніг',
                        ];
                    @endphp
                    @forelse($inventory as $item)
                        <div id="item-{{ $item->id }}" class="flex odd:bg-[#f9f9f9] p-2">
                            <div class="item flex flex-col w-[200px] items-center justify-center py-4">
                                @php
                                    $heightClass = match(true) {
                                        in_array($item->type, ['knife', 'sword', 'axe', 'mace', 'helmet', 'shield']) => 'h-[90px]',
                                        in_array($item->type, ['armor', 'legs']) => 'h-[120px]',
                                        in_array($item->type, ['shoulders', 'belt', 'arms', 'boots']) => 'h-[50px]',
                                        in_array($item->type, ['earrings', 'neckless', 'ring']) => 'h-[30px]',
                                        default => '',
                                    };
                                @endphp
                                <div wire:click="equipItem({{ $item->pivot->id }})" class="{{ $item->type === 'ring' ? 'h-[30px]' : 'w-[90px]' }} {{ $heightClass }} shadow-md cursor-pointer" title="Екіпірувати">
                                    <img src="{{ asset($item->image) }}" class="w-full h-full object-cover" alt="{{ $item->name }}">
                                </div>
                            </div>

                            <div class="w-full flex flex-col p-2">
                                <h3 class="font-semibold mb-1">{{ $item->name }} [{{ $item->required_level }}]</h3>
                                @if($item->min_damage)
                                    <p>Урон: {{ $item->min_damage }}–{{ $item->max_damage }}</p>
                                @endif
                                @foreach($item->defense_by_zone as $zone => $range)
                                    <p>Броня {{ $labels_ua[$zone] ?? ucfirst($zone) }}: {{ $range['min'] }}–{{ $range['max'] }}</p>
                                @endforeach

                                @foreach($item->bonuses ?? [] as $stat => $value)
                                    <p>{{ $labels_ua[$stat] ?? ucfirst($stat) }}: +{{ $value }}</p>
                                @endforeach
                                <p>Міцність: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                                <p @class(['!text-red-500' => $character->level < $item->required_level])>
                                    {{ $character->level < $item->required_level ? 'Мінімальний рівень: ' : 'Рівень: ' }}{{ $item->required_level }}
                                </p>
                                <p class="mt-2 text-[14px] font-thin italic">{{ $item->description }}</p>
                                <div class="flex flex-wrap items-center justify-start gap-[12px] mt-4">
                                    <button
                                            wire:click="sellItem({{ $item->pivot->id }})"
                                            title="Продати"
                                            class="cursor-pointer"
                                        >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.0006 9.37657C14.7569 9.37657 14.5597 9.18903 14.4885 8.95598C14.3701 8.56829 14.1413 8.24663 13.8021 7.99099C13.347 7.64799 12.7748 7.47649 12.0854 7.47649C11.5998 7.47649 11.1719 7.55969 10.8017 7.7261C10.4349 7.8925 10.1463 8.12343 9.93573 8.41889C9.72857 8.71095 9.62499 9.04376 9.62499 9.41732C9.62499 9.6924 9.68442 9.93352 9.80328 10.1407C9.92214 10.3478 10.0818 10.5261 10.2821 10.6755C10.4859 10.8216 10.71 10.9472 10.9545 11.0525C11.2024 11.1578 11.4521 11.2461 11.7034 11.3174L12.8037 11.6332C13.1365 11.7249 13.4693 11.8421 13.8021 11.9847C14.1349 12.1274 14.4389 12.3073 14.7139 12.5247C14.9924 12.7386 15.2149 13.0018 15.3813 13.3143C15.5511 13.6233 15.636 13.9952 15.636 14.4299C15.636 14.9868 15.4916 15.4877 15.203 15.9326C14.9143 16.3775 14.5 16.7307 13.96 16.9922C13.4201 17.2503 12.7731 17.3793 12.0192 17.3793C11.3094 17.3793 10.693 17.2621 10.1701 17.0278C9.64706 16.7901 9.23614 16.4624 8.93729 16.0447C8.73631 15.7638 8.59446 15.4529 8.51175 15.1121C8.44467 14.8357 8.67431 14.5929 8.95872 14.5929C9.21434 14.5929 9.41935 14.7922 9.49463 15.0365C9.56373 15.2607 9.6717 15.4609 9.81856 15.6371C10.0563 15.9224 10.3653 16.1398 10.7457 16.2892C11.126 16.4352 11.5505 16.5082 12.0192 16.5082C12.5388 16.5082 13.0006 16.4216 13.4048 16.2484C13.8123 16.0718 14.1315 15.8273 14.3625 15.5149C14.5968 15.1991 14.7139 14.8323 14.7139 14.4146C14.7139 14.0614 14.6222 13.7659 14.4389 13.5282C14.2555 13.2871 13.9991 13.085 13.6697 12.922C13.3436 12.759 12.965 12.6147 12.5337 12.489L11.2856 12.1223C10.4672 11.8777 9.83215 11.5398 9.38048 11.1085C8.9288 10.6772 8.70297 10.1271 8.70297 9.45807C8.70297 8.89773 8.85239 8.4053 9.15124 7.9808C9.45349 7.5529 9.86101 7.22009 10.3738 6.98237C10.89 6.74125 11.469 6.62069 12.1109 6.62069C12.7595 6.62069 13.3335 6.73955 13.8327 6.97727C14.3319 7.215 14.7275 7.54271 15.0196 7.96042C15.221 8.24289 15.3585 8.55219 15.4321 8.88832C15.4896 9.15109 15.2696 9.37657 15.0006 9.37657Z" fill="black"/>
                                            <path d="M11.5709 5.94828C11.5709 5.7007 11.7716 5.5 12.0192 5.5C12.2668 5.5 12.4675 5.7007 12.4675 5.94828L12.4675 6.84483C12.4675 7.0924 12.2668 7.2931 12.0192 7.2931C11.7716 7.2931 11.5709 7.0924 11.5709 6.84483V5.94828Z" fill="black"/>
                                            <path d="M11.5709 17.1552C11.5709 16.9076 11.7716 16.7069 12.0192 16.7069C12.2668 16.7069 12.4675 16.9076 12.4675 17.1552L12.4675 18.0517C12.4675 18.2993 12.2668 18.5 12.0192 18.5C11.7716 18.5 11.5709 18.2993 11.5709 18.0517V17.1552Z" fill="black"/>
                                            <circle cx="12" cy="12" r="11.5" stroke="#666"/>
                                        </svg>
                                    </button>
                                    <button title="Додати у обране">
                                        <svg width="20" height="20" viewBox="0 0 24 35" fill="" style="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M23 2C23 1.44772 22.5523 1 22 1H2C1.44772 1 1 1.44771 1 2V33.1309L11.4453 26.168L11.5752 26.0947C11.8887 25.9475 12.2606 25.9719 12.5547 26.168L23 33.1309V2ZM24 35L12 27L0 35V2C0 0.964349 0.787223 0.113005 1.7959 0.0107422L2 0H22C23.1046 0 24 0.895431 24 2V35Z" fill=""/>
                                            <path d="M14 11H18.7714C19.0094 11 19.1127 11.3012 18.9249 11.4473L15 14.5L16.3005 18.8351C16.3678 19.0594 16.1173 19.2449 15.9224 19.1149L12 16.5L8.07761 19.1149C7.8827 19.2449 7.63217 19.0594 7.69948 18.8351L9 14.5L5.07515 11.4473C4.88725 11.3012 4.9906 11 5.22863 11H10L11.7651 6.14611C11.8448 5.92669 12.1552 5.92669 12.2349 6.14611L14 11ZM10.9395 11.3418C10.7957 11.7369 10.4205 12 10 12H7.41406L9.61426 13.7109C9.93986 13.9644 10.0765 14.3918 9.95801 14.7871L9.25586 17.127L11.4453 15.668L11.5752 15.5947C11.8887 15.4475 12.2606 15.4719 12.5547 15.668L14.7441 17.127L14.042 14.7871C13.9235 14.3918 14.0601 13.9644 14.3857 13.7109L16.5859 12H14C13.5795 12 13.2043 11.7369 13.0605 11.3418L12 8.4248L10.9395 11.3418Z" fill=""/>
                                        </svg>
                                    </button>
                                    <button title="Викинути">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.84574 20.2112H9.86331C10.1117 20.201 10.304 19.9722 10.2957 19.7012L9.89964 8.96696C9.89027 8.69596 9.67816 8.48888 9.43206 8.49526C9.18363 8.50549 8.99142 8.73431 8.99964 9.00532L9.39573 19.7395C9.40511 20.0041 9.6055 20.2112 9.84574 20.2112Z" fill="black"/>
                                            <path d="M13.9345 20.2071H13.952C14.1923 20.2071 14.3927 20.0012 14.402 19.7353L14.7946 9.00114C14.804 8.73013 14.6118 8.50005 14.3622 8.49108C14.1196 8.48085 13.9028 8.6905 13.8946 8.96278L13.502 19.697C13.4927 19.968 13.6849 20.1981 13.9345 20.2071Z" fill="black"/>
                                            <path d="M3.74612 5.68393L5.5637 22.3113C5.66917 23.2739 6.41918 24 7.30628 24H16.4869C17.3752 24 18.1251 23.2739 18.2295 22.3113L20.047 5.68393C21.0103 5.67754 21.7896 5.36123 21.7896 4.31042V3.72137C21.7896 2.66418 21.0009 2.353 20.0341 2.353H16.7164V1.91495C16.7164 0.861591 15.9278 0 14.961 0H8.82866C7.86302 0 7.07318 0.860314 7.07318 1.91495V2.353H3.75548C2.78984 2.353 2 2.66418 2 3.72137V4.31042C2 5.3612 2.78048 5.67624 3.74258 5.68393H3.74612ZM17.3364 22.1935C17.2849 22.6652 16.9192 23.018 16.488 23.018H7.30739C6.87497 23.018 6.50933 22.6652 6.45896 22.1935L4.65311 5.68393H19.1434L17.3376 22.1935H17.3364ZM7.97672 1.91616C7.97672 1.40228 8.36108 0.982969 8.8322 0.982969H14.9645C15.4356 0.982969 15.82 1.40224 15.82 1.91616V2.35421H7.97768L7.97672 1.91616ZM2.90372 3.72137C2.90372 3.20748 3.28808 3.33482 3.7592 3.33482H20.0378C20.5089 3.33482 20.8933 3.20745 20.8933 3.72137V4.31042C20.8933 4.82431 20.5089 4.70224 20.0378 4.70224H3.7592C3.28811 4.70224 2.90372 4.82434 2.90372 4.31042V3.72137Z" fill="black"/>
                                            </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Інвентар порожній.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p>У вас ще немає персонажа.</p>
    @endif
</div>
