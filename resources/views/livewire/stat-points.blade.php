<div>
    <ul class="mb-2 px-2">
        @foreach(['strength' => 'Сила', 'agility' => 'Спритність', 'intuition' => 'Інтуїція', 'endurance' => 'Витривалість'] as $statKey => $statName)
            @php
                $baseValue = $character->$statKey;
                $bonusValue = $character->bonuses[$statKey] ?? 0;
                $totalValue = $character->getTotalStat($statKey);
            @endphp
            <li class="flex justify-between mb-1">
                <span class="flex items-center">
                    <span class="">{{ $statName }}:</span>
                    <span class="text-green-900 font-medium mx-[5px]">{{ $baseValue + $bonusValue }}</span>
                    <span class="font-light">
                        @if($bonusValue > 0)<span class="mr-[3px]">({{ $baseValue }}</span>+<span class="ml-[3px]">{{ $bonusValue }})</span>@endif
                    </span>
                </span>

                @if($character->stat_points > 0)
                    <button wire:click="incrementStat('{{ $statKey }}')" class="w-[24px] h-[24px] flex items-center text-[16px] ml-1 px-2 py-1 bg-green-500 text-white hover:bg-green-600">
                        +
                    </button>
                @endif
            </li>
        @endforeach
        @if($character->stat_points > 0)
            <li class="mt-2 -ml-2"><p class="text-[14px] font-thin italic">Нерозподілені стати: <span class=" ml-1">{{ $character->stat_points }}</span></p></li>
        @endif
    </ul>
</div>
