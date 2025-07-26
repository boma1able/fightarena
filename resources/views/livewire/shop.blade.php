<div class="flex flex-wrap bg-white p-6 rounded shadow-md">
    <div class="w-full">
        <h2 class="text-xl text-center font-bold mb-4">Магазин</h2>

        <div
            x-data="{ show: false, message: '', type: 'info' }"
            x-init="
                window.addEventListener('notify', event => {
                    message = event.detail.message;
                    type = event.detail.type;
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

        <div class="flex">
            {{-- shop filter --}}
            <div class="w-[250px] mr-4 border-l border-r border-gray-100">
                <div class="p-2 mb-4 bg-gray-100">
                    <p>У вас {{ $character->gold }} золота</p>
                </div>
                <div class="flex flex-col gap-2">
                    {{-- Кнопка "Усі товари" --}}
                    <button
                        wire:click="setFilter('all')"
                        class="p-2 text-sm text-start border-b border-gray-100 {{ $filterType === 'all' ? 'font-semibold text-blue-600' : '' }}"
                    >
                    &mdash; Усі товари
                    </button>

                    @foreach($allTypesGrouped as $groupName => $types)
                        <div class="px-2 border-b border-gray-100">
                            <h3 class="font-bold text-gray-700 bg-gray-100 p-1">{{ $groupName }}</h3>
                            <div class="flex gap-2 mt-1 flex-col">
                                @if($types->isNotEmpty())
                                    @foreach($types as $type)
                                        <button
                                            wire:click="setFilter('{{ $type }}')"
                                            class="py-1 text-sm text-start {{ $filterType === $type ? 'font-semibold text-blue-600' : '' }}">
                                            &mdash; {{ ucfirst($type) }}
                                        </button>
                                    @endforeach
                                @else
                                    <button class="py-1 text-sm text-gray-400 cursor-default" disabled>-</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>

            <div class="flex flex-col" style="width: calc(100% - 250px)">
                @php
                    $labels_ua = [
                        'strength' => 'Сила',
                        'agility' => 'Ловкість',
                        'intelligence' => 'Інтелект',
                        'endurance' => 'Витривалість',
                        'head' => 'голови',
                        'chest' => 'грудей',
                        'belly' => 'живота',
                        'belt' => 'пояса',
                        'legs' => 'ніг'
                    ];
                @endphp
                @forelse($items as $item)
                    <div id="item-{{ $item->id }}" class="flex odd:bg-[#f9f9f9] p-2">
                        <div class="item flex flex-col w-[200px] items-center justify-center py-4">

                            <div wire:click="equipItem({{ $item->pivot->id }})" class="relative w-[60px] h-[90px]"
                                style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                title="Екіпірувати">
                                <img src="{{ asset('images/items/frame-gray.png') }}"
                                    class="absolute w-[60px]"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                    alt="{{ $item->name }}">
                                <img src="{{ asset($item->image) }}"
                                    class="absolute w-[60px]"
                                    class="absolute w-[90px]"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                    alt="img">
                            </div>

                        </div>

                        <div class="w-full flex flex-col p-2">
                            <h3 class="font-semibold mb-1 text-xl">{{ $item->name }} [{{ $item->required_level }}]</h3>
                            @if($item->min_damage)
                                <p class="text-sm">Урон: {{ $item->min_damage }}–{{ $item->max_damage }}</p>
                            @endif
                            @foreach($item->bonuses ?? [] as $stat => $value)
                                <p class="text-sm">{{ $labels_ua[$stat] ?? ucfirst($stat) }}: +{{ $value }}</p>
                            @endforeach
                            @foreach($item->defense_by_zone as $zone => $range)
                                <p class="text-sm">Броня {{ $labels_ua[$zone] ?? ucfirst($zone) }}: {{ $range['min'] }} – {{ $range['max'] }}</p>
                            @endforeach
                            <p class="text-sm">Міцність: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                            <p @class(['text-sm', '!text-red-500' => $character->level < $item->required_level])>
                                {{ $character->level < $item->required_level ? 'Мінімальний рівень: ' : 'Рівень: ' }}{{ $item->required_level }}
                            </p>
                            <p class="mt-2 text-[14px] font-thin italic mb-2 text-sm">{{ $item->description }}</p>
                            <div class="flex">

                                @if($character->gold < $item->buy_price)
                                    <p class="mt-3"><span class="text-red-500 underline">Ціна: {{ $item->buy_price }}.</span> У вас недостатньо коштів!</p>
                                @else
                                    <div wire:click="buyItem({{ $item->id }})" class="btn cursor-pointer bg-green-500 hover:bg-green-400 text-white px-2 py-1">
                                        Купити за {{ $item->buy_price }} золота
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Магазин порожній.</p>
                @endforelse
            </div>


        </div>

    </div>
</div>