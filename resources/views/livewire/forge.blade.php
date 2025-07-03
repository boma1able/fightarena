<div class="bg-white p-6 rounded shadow-md">

    @php
        $slots = [
            'helmet', 'weapon', 'armor',
            'neckless', 'ring1', 'ring2',
            'arms', 'shield', 'legs', 'boots'
        ];
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

    @if($character)
        <div class="flex mb-4 w-full gap-5">

             {{-- Ліва панель ремонту --}}
            <div class="w-1/3 bg-gray-50 p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Кузня</h2>

                @if($repairItem)
                    <div class="flex flex-col border p-4 rounded bg-white text-center justify-center">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px] mx-[auto] mb-3"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                                <div class="relative w-[60px] h-[90px]"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                >
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                        alt="">
                                    <img src="{{ asset($repairItem->image) }}"
                                        class="absolute w-[60px]"
                                        style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                        alt="">
                            </div>


                        </div>
                        <p class="text-sm text-gray-600">
                            Міцність: {{ $repairItem->pivot->current_durability }} / {{ $repairItem->pivot->max_durability }}
                        </p>
                        <p>Ціна ремонту: <strong>{{ $this->repair_cost }} золота</strong></p>
                        <div>
                            <button wire:click="repairSelected" class="mt-5 mr-2">Відремонтувати</button>
                            <button wire:click="cancelRepair">Скасувати</button>
                        </div>
                    </div>
                @endif


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

                    @forelse($inventory as $item)
                        <div id="item-{{ $item->id }}" class="flex odd:bg-[#f9f9f9] p-2">
                            <div class="item flex flex-col w-[200px] items-center justify-center py-4">

                                <div wire:click="selectForRepair({{ $item->pivot->id }})" class="relative w-[60px] h-[90px] cursor-pointer"
                                    style="background: #000 linear-gradient(0deg,rgb(97, 97, 97) 0%, rgba(118, 118, 118, 0.13) 80%)"
                                    title="Обрати для ремонту">
                                    <img src="{{ asset('images/items/frame-gray.png') }}"
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
                                <h3 class="font-semibold mb-1">{{ $item->name }} [{{ $item->required_level }}]</h3>
                                @if($item->min_damage)
                                    <p>Урон: {{ $item->min_damage }}–{{ $item->max_damage }}</p>
                                @endif
                                @foreach($item->defense_by_zone as $zone => $range)
                                    <p>Броня {{ $labels_ua[$zone] ?? ucfirst($zone) }}: {{ $range['min'] }} – {{ $range['max'] }}</p>
                                @endforeach

                                @foreach($item->bonuses ?? [] as $stat => $value)
                                    <p>{{ $labels_ua[$stat] ?? ucfirst($stat) }}: +{{ $value }}</p>
                                @endforeach
                                @if($item->pivot)
                                    <p>Міцність: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                                @else
                                    <p class="text-red-500 text-sm">Помилка: відсутній зв'язок pivot</p>
                                @endif

                                <p @class(['!text-red-500' => $character->level < $item->required_level])>
                                <p class="mt-2 text-[14px] font-thin italic">{{ $item->description }}</p>
                                <div class="flex flex-wrap items-center justify-start gap-[12px] mt-4">
                                    <button wire:click="selectForRepair({{ $item->pivot->id }})">Обрати для ремонту</button>
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
