@php
    $isHistoryPage = request()->routeIs('history');
    $chatHeightClass = $isHistoryPage ? 'h-[400px]' : 'h-40';
@endphp

<div class="w-full text-sm space-y-1 {{ $chatHeightClass }} overflow-auto p-3 bg-[#f9f9f9] my-5">
    @foreach($lines as $line)
        @php
            $text = $line['text'] ?? $line;
            $type = $line['type'] ?? 'normal';
            $timestamp = $line['timestamp'] ?? null;
        @endphp

        <p @class([
            'text-gray-700',
            'bg-red-100' => $type === 'bleeding',
        ])>
            @if($timestamp)
                <span class="text-gray-400 mr-2">[{{ $timestamp }}]</span>
            @endif
            {{ $text }}
        </p>
    @endforeach

</div>