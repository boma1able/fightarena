<div>
    @if (!request()->routeIs('battle'))
        <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9] text-xl">Модифікатори</h2>
    @endif
    <ul class="px-2">
        {{-- <li><strong>Базовий урон:</strong> {{ $character->base_damage }}</li> --}}
        @if($damageRange)
            <li>Урон: {{ $damageRange['min'] }} – {{ $damageRange['max'] }}</li>
        @endif
        @if($criticalDamageRange)
            <li>Крит урон: {{ $criticalDamageRange['min'] }} – {{ $criticalDamageRange['max'] }}
            </li>
        @endif
        {{-- <li><strong>Критичний урон:</strong> {{ round($character->base_damage * $character->critical_damage_multiplier) }}</li> --}}
        <li>Крит: {{ $character->crit_chance }}%</li>
        <li>Анти-крит: {{ $character->anti_crit_chance }}%</li>
        <li>Ухил: {{ $character->dodge_chance }}%</li>
        <li>Анті-ухил: {{ $character->anti_dodge_chance }}%</li>
    </ul>
</div>
