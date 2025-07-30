<div class="flex flex-wrap bg-white p-6 rounded shadow-md">
    <div class="w-full">
        <h2 class="text-xl text-center font-bold mb-4">{{ __('messages.shop') }}</h2>

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
                    <p>{{ __('messages.youHave') }} {{ $character->gold }} {{ __('messages.gold') }}</p>
                </div>
                <div class="flex flex-col gap-2">

                    <button
                        wire:click="setFilter('all')"
                        class="p-2 text-sm text-start border-b border-gray-100 {{ $filterType === 'all' ? 'font-semibold text-blue-600' : '' }}"
                    >
                    &mdash; {{ __('messages.allProducts') }}
                    </button>

                    @foreach($allTypesGrouped as $groupName => $types)
                        <div class="px-2 border-b border-gray-100">
                            <h3 class="font-bold text-gray-700 bg-gray-100 p-1">{{ __('filters.groups.' . $groupName) }}</h3>
                            <div class="flex gap-2 mt-1 flex-col">
                                @if($types->isNotEmpty())
                                    @foreach($types as $type)
                                        <button
                                            wire:click="setFilter('{{ $type }}')"
                                            class="py-1 text-sm text-start {{ $filterType === $type ? 'font-semibold text-blue-600' : '' }}">
                                            &mdash; {{ __('filters.types.' . $type) }}
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
                            <h3 class="font-semibold mb-1 text-xl">{{ __('items.' . $item->key . '.name') }} [{{ $item->required_level }}]</h3>
                            @if($item->min_damage)
                                <p class="text-sm">{{ __('messages.damage') }}: {{ $item->min_damage }}–{{ $item->max_damage }}</p>
                            @endif
                            @foreach($item->defense_by_zone as $zone => $range)
                                <p class="text-sm">{{ __('messages.' . $zone) }}: {{ $range['min'] }} – {{ $range['max'] }}</p>
                            @endforeach
                            @foreach($item->bonuses ?? [] as $stat => $value)
                                <p class="text-sm">{{ __('messages.' . $stat) }}: +{{ $value }}</p>
                            @endforeach
                            <p class="text-sm">{{ __('messages.durability') }}: {{ $item->pivot->current_durability }} / {{ $item->pivot->max_durability }}</p>
                            <p @class(['text-sm', '!text-red-500' => $character->level < $item->required_level])>
                                {{ $character->level < $item->required_level ? __('messages.min_level') : __('messages.level') }}: {{ $item->required_level }}
                            </p>
                            <p class="mt-2 text-[14px] font-thin italic mb-2 text-sm">{{ __('items.' . $item->key . '.description') }}</p>

                            <div class="flex items-center gap-2">
                                @if($character->gold < $item->buy_price)
                                    <p class="mt-3 text-sm text-red-600">
                                        <span class="underline">{{ __('messages.price') }}: {{ $item->buy_price }}</span>.
                                        {{ __('messages.not_enough_gold') }}
                                    </p>
                                @else
                                    <div wire:click="buyItem({{ $item->id }})"
                                         class="btn cursor-pointer bg-green-500 hover:bg-green-400 text-white text-sm px-3 py-1 rounded">
                                        {{ __('messages.buy_for_gold', ['price' => $item->buy_price]) }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">{{ __('messages.shopIsEmpty') }}.</p>
                @endforelse
            </div>


        </div>

    </div>
</div>