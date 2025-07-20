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
        $rarityColors = [
            'common' => 'gray',
            'uncommon' => 'green',
            'rare' => 'blue',
            'legendary' => 'gold',
        ];
    @endphp

    @if($character)
        <div class="flex mb-4 w-full gap-5">

             {{-- Ліва панель ремонту --}}
            <div class="w-1/3 bg-gray-50 p-4 rounded shadow">
                <h2 class="text-xl font-bold mb-4">Кузня</h2>

                @if($repairItem)
                    @php
                        $rarityClass = $rarityColors[$repairItem->pivot->rarity ?? 'common'] ?? 'gray';
                        $isBroken = $repairItem->pivot?->current_durability === 0;
                    @endphp
                    <div class="flex flex-col border p-4 rounded bg-white text-center justify-center">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px] mx-[auto] mb-3"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                                <div wire:click="cancelRepair" class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px] cursor-pointer"
                                    title="Натисніть щоб відмінити ремонт."
                                >
                                <img src="{{ asset('images/items/frame-'.  ($rarityColors[$repairItem->pivot->rarity] ?? 'gray') . '.png') }}"
                                    class="absolute w-[60px]"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%);"
                                    alt="{{ $repairItem->name }}">
                                <img src="{{ asset($repairItem->image) }}"
                                    class="absolute w-[60px]"
                                    class="absolute w-[90px]"
                                    style="top: 50%; left: 50%; transform: translate(-50%, -50%) scale(1.35); filter: brightness(1.2) drop-shadow(-1px 2px 1px rgba(0, 0, 0, 0.5));"
                                    alt="">
                            </div>


                        </div>
                        <p class="text-sm text-gray-600">
                            Міцність: {{ $repairItem->pivot->current_durability }} / {{ $repairItem->pivot->max_durability }}
                        </p>
                        <p>Ціна ремонту: <strong>{{ $this->repair_cost }} золота</strong></p>
                        <div class="flex justify-center mt-4 gap-2">
                            <button wire:click="repairSelected" class="flex px-3 py-1 text-white bg-green-500 text-black">
                                <svg class="mr-2" version="1.0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                                    <g transform="translate(0 512) scale(0.1 -0.1)" fill="#fff" stroke="none">
                                      <path d="M1890 4794c-14-2-52-9-84-15-148-27-323-106-451-204-38-30-215-199-392-377-370-371-357-350-278-438 54-61 60-106 20-145-39-40-84-34-145 20-80 71-90 68-237-77-142-141-173-191-173-278 0-98 22-128 317-422 292-290 314-307 414-308 86 0 136 31 277 173 144 146 148 159 82 231-33 35-40 51-40 83 0 52 31 83 85 83 35 0 47-8 121-80 100-98 120-102 190-38l45 42 202-202 202-202-371-371-370-370-69 12c-80 14-225 7-305-15-207-55-368-170-480-343-92-140-125-259-124-443 1-118 4-145 28-217 18-57 35-88 54-103 50-40 69-28 294 197l207 207 115-28 114-28 28-114 28-115-207-207c-225-225-237-244-197-294 15-19 46-36 103-54 72-24 99-27 217-28 184-1 303 32 443 124 173 112 288 273 343 480 22 80 29 225 15 305l-12 69 370 370 371 371 583-583c533-532 585-582 615-582 44 0 82 38 82 82 0 30-85 118-1082 1115l-1083 1083 183 182 182 183 1311-1310c1060-1060 1316-1320 1335-1360 46-96 23-227-52-297-72-67-195-88-284-47-29 13-92 67-181 155-118 116-140 134-168 134-43 0-81-38-81-82 0-29 17-49 143-174 157-155 206-187 321-207 277-47 526 202 479 479-25 142 9 104-887 1002l-821 822 291 291 290 290 69-12c80-14 225-7 305 15 207 55 368 170 480 343 92 140 125 259 124 443-1 118-4 145-28 217-18 57-35 88-54 103-50 40-69 28-294-197l-207-207-115 28-114 28-28 114-28 114 212 213c230 231 237 243 188 292-52 52-240 86-406 73-93-7-231-48-283-82-62-42-38-146 33-146 15 0 52 11 81 24 30 13 81 29 114 36 62 13 231 17 242 6 4-4-73-87-170-184-123-124-177-185-177-201 0-29 77-340 92-368 6-12 22-26 37-32 36-14 346-91 367-91 10 0 97 79 194 175 97 97 179 172 183 169 4-4 7-41 7-83-1-415-374-721-775-636-38 8-79 15-90 15-12 0-136-117-337-317l-318-318-182 183-183 182 318 318c200 201 317 325 317 337 0 11-7 52-15 90-37 175 4 365 109 510 43 59 46 96 11 130-53 54-106 26-180-96-97-159-138-391-99-569 4-22-30-60-286-315l-290-290-202 202-202 202 42 45c70 76 67 83-108 261-138 139-150 155-150 188 0 70 94 142 226 173 52 12 101 15 199 12 117-4 141-9 238-41 60-21 117-37 128-37 31 0 69 44 69 81 0 25-14 45-72 107-218 226-496 357-778 367-63 2-126 2-140-1zm234-169c110-19 191-47 290-99l78-41-128-6c-154-8-260-38-353-100-104-69-151-146-151-246 1-88 26-132 153-260l111-114-301-301-301-301-56 50c-86 79-172 100-270 67-47-16-112-69-134-109-10-18-24-7-164 133-140 140-151 154-133 164 42 23 93 88 110 138 21 65 16 130-15 195l-23 48 309 307c262 260 321 314 389 354 201 117 390 156 589 121zm-1339-1445l300-300-85-85c-128-129-99-141-422 182-323 323-311 295-186 420 46 46 85 83 88 83 3 0 140-135 305-300zm1560-840l180-180-398-398c-254-254-397-405-397-417 0-11 7-52 15-90 84-397-220-774-625-775-129 0-132-8 64 189 133 134 176 183 176 202-1 50-89 372-107 390-21 20-321 99-379 99-34 0-47-11-207-176-190-196-185-194-186-69-1 169 65 331 183 449 162 162 368 226 586 182 46-10 93-15 104-12 12 3 196 181 411 396 214 214 392 390 395 390 3 0 86-81 185-180z" />
                                    </g>
                                </svg>
                                Відремонтувати
                            </button>
                            <button wire:click="cancelRepair" class="px-3 py-1 text-white bg-red-500 text-black">x</button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col border p-4 rounded bg-white text-center justify-center">
                        <div class="flex relative items-center justify-center w-[68px] h-[98px] mx-[auto] mb-3"
                            style="background: url({{ asset('images/empty-equipment/empty-ring.png') }}) center center no-repeat; background-size: cover;"
                            >
                                <div class="relative w-[60px] h-[90px]"></div>
                        </div>
                        <p>Оберіть предмет для ремонту.</p>
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

                <div class="flex flex-wrap flex-col min-h-[450] h-full">

                    @forelse($inventory as $item)
                        @php
                            $rarityClass = $rarityColors[$item->pivot->rarity ?? 'common'] ?? 'gray';
                            $isBroken = $item->pivot?->current_durability === 0;
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
                                        wire:click="selectForRepair({{ $item->pivot->id }})"
                                        title="Відремонтувати"
                                        >
                                        <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                                            <g transform="translate(0 512) scale(0.1 -0.1)" fill="#fff" stroke="none">
                                              <path d="M1890 4794c-14-2-52-9-84-15-148-27-323-106-451-204-38-30-215-199-392-377-370-371-357-350-278-438 54-61 60-106 20-145-39-40-84-34-145 20-80 71-90 68-237-77-142-141-173-191-173-278 0-98 22-128 317-422 292-290 314-307 414-308 86 0 136 31 277 173 144 146 148 159 82 231-33 35-40 51-40 83 0 52 31 83 85 83 35 0 47-8 121-80 100-98 120-102 190-38l45 42 202-202 202-202-371-371-370-370-69 12c-80 14-225 7-305-15-207-55-368-170-480-343-92-140-125-259-124-443 1-118 4-145 28-217 18-57 35-88 54-103 50-40 69-28 294 197l207 207 115-28 114-28 28-114 28-115-207-207c-225-225-237-244-197-294 15-19 46-36 103-54 72-24 99-27 217-28 184-1 303 32 443 124 173 112 288 273 343 480 22 80 29 225 15 305l-12 69 370 370 371 371 583-583c533-532 585-582 615-582 44 0 82 38 82 82 0 30-85 118-1082 1115l-1083 1083 183 182 182 183 1311-1310c1060-1060 1316-1320 1335-1360 46-96 23-227-52-297-72-67-195-88-284-47-29 13-92 67-181 155-118 116-140 134-168 134-43 0-81-38-81-82 0-29 17-49 143-174 157-155 206-187 321-207 277-47 526 202 479 479-25 142 9 104-887 1002l-821 822 291 291 290 290 69-12c80-14 225-7 305 15 207 55 368 170 480 343 92 140 125 259 124 443-1 118-4 145-28 217-18 57-35 88-54 103-50 40-69 28-294-197l-207-207-115 28-114 28-28 114-28 114 212 213c230 231 237 243 188 292-52 52-240 86-406 73-93-7-231-48-283-82-62-42-38-146 33-146 15 0 52 11 81 24 30 13 81 29 114 36 62 13 231 17 242 6 4-4-73-87-170-184-123-124-177-185-177-201 0-29 77-340 92-368 6-12 22-26 37-32 36-14 346-91 367-91 10 0 97 79 194 175 97 97 179 172 183 169 4-4 7-41 7-83-1-415-374-721-775-636-38 8-79 15-90 15-12 0-136-117-337-317l-318-318-182 183-183 182 318 318c200 201 317 325 317 337 0 11-7 52-15 90-37 175 4 365 109 510 43 59 46 96 11 130-53 54-106 26-180-96-97-159-138-391-99-569 4-22-30-60-286-315l-290-290-202 202-202 202 42 45c70 76 67 83-108 261-138 139-150 155-150 188 0 70 94 142 226 173 52 12 101 15 199 12 117-4 141-9 238-41 60-21 117-37 128-37 31 0 69 44 69 81 0 25-14 45-72 107-218 226-496 357-778 367-63 2-126 2-140-1zm234-169c110-19 191-47 290-99l78-41-128-6c-154-8-260-38-353-100-104-69-151-146-151-246 1-88 26-132 153-260l111-114-301-301-301-301-56 50c-86 79-172 100-270 67-47-16-112-69-134-109-10-18-24-7-164 133-140 140-151 154-133 164 42 23 93 88 110 138 21 65 16 130-15 195l-23 48 309 307c262 260 321 314 389 354 201 117 390 156 589 121zm-1339-1445l300-300-85-85c-128-129-99-141-422 182-323 323-311 295-186 420 46 46 85 83 88 83 3 0 140-135 305-300zm1560-840l180-180-398-398c-254-254-397-405-397-417 0-11 7-52 15-90 84-397-220-774-625-775-129 0-132-8 64 189 133 134 176 183 176 202-1 50-89 372-107 390-21 20-321 99-379 99-34 0-47-11-207-176-190-196-185-194-186-69-1 169 65 331 183 449 162 162 368 226 586 182 46-10 93-15 104-12 12 3 196 181 411 396 214 214 392 390 395 390 3 0 86-81 185-180z" />
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="item flex flex-col w-[200px] items-center justify-center py-4">

                                <div
                                    wire:click="selectForRepair({{ $item->pivot->id }})" class="{{ $rarityClass }} {{ $isBroken ? 'broken' : '' }} relative w-[60px] h-[90px] cursor-pointer"
                                    title="Обрати для ремонту">
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
                                    <p class="{{ $isBroken ? 'text-red-500 underline' : '' }}">Міцність: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                                @endif

                                <p @class(['!text-red-500' => $character->level < $item->required_level])>
                                <p class="mt-2 text-[14px] min-h-[65px] font-thin italic">{{ $item->description }}</p>
                                <div class="flex flex-wrap items-center justify-start gap-[12px] mt-4">
                                    <button wire:click="selectForRepair({{ $item->pivot->id }})" class="px-3 py-1 text-white bg-blue-500 text-black">Почати ремонт</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 italic font-thin">У вас немає предметів які потрібно відремонтувати або вони одягнуті на вас.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p>У вас ще немає персонажа.</p>
    @endif
</div>
