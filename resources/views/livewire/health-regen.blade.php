@php
    $barColor = match(true) {
        $percent <= 33 => 'bg-red-500',
        $percent <= 66 => 'bg-yellow-400',
        default => 'bg-green-500',
    };
@endphp

<div wire:poll.1s="updateHealth">
    <div class="relative block w-full h-3 bg-gray-100 cursor-pointer" title="Здоровʼя: {{ $character->current_health }} / {{ $character->max_health }}">
        <div class="absolute top-0 left-0 w-full text-[8px] text-center text-black z-[1]"><strong>{{ $character->current_health }} / {{ $character->max_health }}</strong></div>
        <div class="absolute top-0 left-0 {{ $barColor }} h-3" style="width: {{ $percent }}%"></div>
    </div>
    @if($character->current_health < $character->max_health)
        {{-- <div class="text-xs text-green-500">Відновлення здоров’я триває...</div> --}}
    @endif
</div>