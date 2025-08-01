<div>
    @php
        $armorByZone = $character->totalDefenseByZone();
    @endphp
    @if (!request()->routeIs('battle'))
        <h2 class="mb-2 mt-3 p-2 font-semibold bg-[#f9f9f9] text-xl">{{ __('messages.armor') }}</h2>
    @endif
    <ul class="px-2">
        @foreach(['head', 'chest', 'belly', 'belt', 'legs'] as $zone)
        @php
            $label = __('messages.' . $zone);
            $min = $armorByZone[$zone]['min'] ?? 0;
            $max = $armorByZone[$zone]['max'] ?? 0;
        @endphp
        <li>{{ $label }}: {{ $min }} - {{ $max }}</li>
    @endforeach
    </ul>
</div>
