<div>
    <h2 class="mb-2 mt-3 p-2 text-[14px] font-semibold bg-[#f9f9f9]">Броня</h2>
    <ul class="px-2">
        @php
            $zoneLabels = [
                'head' => 'Броня голови',
                'chest' => 'Броня грудей',
                'belly' => 'Броня живота',
                'belt' => 'Броня пояса',
                'legs' => 'Броня ніг',
            ];
        @endphp

        @foreach($zoneLabels as $zone => $label)
            @php
                $min = $armorByZone[$zone]['min'] ?? 0;
                $max = $armorByZone[$zone]['max'] ?? 0;
            @endphp
            <li>{{ $label }}: @if($min != 0){{ $min }} - @endif{{ $max }}</li>
        @endforeach
    </ul>
</div>
