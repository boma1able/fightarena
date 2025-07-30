<div>
    @if (!request()->routeIs('battle'))
        <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.mods') }}</h2>
    @endif
    <ul class="px-2">
        {{-- <li><strong>Базовий урон:</strong> {{ $character->base_damage }}</li> --}}
        @if($damageRange)
            <li>{{ __('messages.damage') }}: {{ $damageRange['min'] }} – {{ $damageRange['max'] }}</li>
        @endif
        @if($criticalDamageRange)
            <li>{{ __('messages.critDamage') }}: {{ $criticalDamageRange['min'] }} – {{ $criticalDamageRange['max'] }}
            </li>
        @endif
        {{-- <li><strong>Критичний урон:</strong> {{ round($character->base_damage * $character->critical_damage_multiplier) }}</li> --}}
        <li>{{ __('messages.crit') }}: {{ $character->crit_chance }}%</li>
        <li>{{ __('messages.antiCrit') }}: {{ $character->anti_crit_chance }}%</li>
        <li>{{ __('messages.dodge') }}: {{ $character->dodge_chance }}%</li>
        <li>{{ __('messages.antiDodge') }}: {{ $character->anti_dodge_chance }}%</li>
    </ul>
</div>
